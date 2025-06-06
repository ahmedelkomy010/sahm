<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>الأعمال المدنية - {{ $workOrder->order_number }}</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Tajawal:400,500,700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            padding: 30px;
        }
        
        .page-title {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            border-radius: 15px 15px 0 0 !important;
        font-weight: 600;
            border-bottom: none;
            padding: 20px;
    }

    .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        font-weight: 600;
            border: none;
            padding: 15px 10px;
    }

    .table td {
            padding: 12px 10px;
        vertical-align: middle;
            border-color: #e9ecef;
        }
        
        .table-warning {
            background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%) !important;
            color: #2d3436;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            padding: 10px 15px;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: scale(1.02);
        }
        
        .input-group-text {
            border-radius: 0 10px 10px 0;
            border: 2px solid #e9ecef;
            border-left: none;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-weight: 600;
        }
        
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 12px 30px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75em;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
        }
        
        #summary-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }
        
        #summary-table tbody tr:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }
        
        .subsection-title {
            color: #2d3436;
            font-weight: 700;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 3px solid #667eea;
            display: inline-block;
        }
        
        .dimension-input:focus {
            background: rgba(102, 126, 234, 0.05);
        }
        
        .excavation-calc:focus {
            background: rgba(255, 193, 7, 0.1);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .progress {
            border-radius: 20px;
            height: 10px;
            background: rgba(0, 0, 0, 0.1);
        }
        
        .progress-bar {
            border-radius: 20px;
        }
        
        .form-floating label {
            color: #6c757d;
            font-weight: 500;
        }
        
        .form-floating .form-control:focus ~ label {
            color: #667eea;
        }
        
        .bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

        /* تنسيقات خاصة بجدول الحفريات التفصيلي */
        #excavation-details-table {
            font-size: 0.9rem;
        }

        #excavation-details-table th {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
            color: white !important;
            font-weight: 600;
            text-align: center;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        #excavation-details-table tbody tr {
            transition: all 0.3s ease;
        }

        #excavation-details-table tbody tr:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 5;
        }

        .table-success:hover {
            background-color: rgba(25, 135, 84, 0.2) !important;
        }

        .table-danger:hover {
            background-color: rgba(220, 53, 69, 0.2) !important;
        }

        .table-warning:hover {
            background-color: rgba(255, 193, 7, 0.2) !important;
        }

        .table-info:hover {
            background-color: rgba(13, 202, 240, 0.2) !important;
        }

        .btn-group-sm .btn {
            border-radius: 20px;
            margin: 0 2px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-group-sm .btn.active {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        #excavation-search {
            border-radius: 25px;
            border: 2px solid #e9ecef;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        #excavation-search:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            transform: scale(1.02);
        }

        .stats-cards .card {
            transition: all 0.3s ease;
            border-radius: 15px;
        }

        .stats-cards .card:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        /* تحسين أزرار التصدير */
        .btn.float-end {
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn.float-end:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* تحسين الملخص النهائي */
        .bg-light.rounded {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .bg-light.rounded:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        /* تحسين responsive للجدول */
        @media (max-width: 768px) {
            #excavation-details-table {
                font-size: 0.75rem;
            }
            
            #excavation-details-table th,
            #excavation-details-table td {
                padding: 0.5rem 0.25rem;
            }
            
            .btn-group-sm .btn {
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
            }
        }

        /* تحسين الألوان والتدرجات */
        .bg-gradient.text-white {
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        .card-header.bg-gradient {
            border-radius: 15px 15px 0 0;
        }

        /* تأثيرات بصرية جديدة للجدول التفصيلي */
        .excavation-row-animated {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease-out;
        }

        .excavation-row-animated.fade-in-row {
            opacity: 1;
            transform: translateY(0);
        }

        .excavation-row-animated:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 5;
        }

        /* تحسين الـ badges */
        .badge.bg-outline-primary {
            border: 1px solid #0d6efd;
            color: #0d6efd;
            background: transparent;
        }

        .badge.rounded-pill {
            font-size: 0.8em;
            padding: 0.4em 0.7em;
        }

        /* تأثيرات على الإحصائيات */
        .stats-cards .card {
            transition: all 0.4s ease;
            cursor: pointer;
        }

        .stats-cards .card:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        /* تحسين رسالة لا توجد بيانات */
        .text-muted .fa-excavator {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        /* تحسين أزرار التصفية */
        .btn-group-sm .btn {
            position: relative;
            overflow: hidden;
        }

        .btn-group-sm .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-group-sm .btn:hover::before {
            left: 100%;
        }

        /* تحسين الجدول التفصيلي */
        #excavation-details-table tbody tr {
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }

        #excavation-details-table tbody tr.table-success {
            border-left-color: #198754;
        }

        #excavation-details-table tbody tr.table-danger {
            border-left-color: #dc3545;
        }

        #excavation-details-table tbody tr.table-warning {
            border-left-color: #ffc107;
        }

        #excavation-details-table tbody tr.table-info {
            border-left-color: #0dcaf0;
        }

        /* تحسين الرسالة التوضيحية */
        .table-info td {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%) !important;
            border: 1px solid #b6effb;
        }

        /* تأثير النبضة على الأرقام الجديدة */
        .fw-bold.text-success {
            animation: pulse-green 1s ease-in-out;
        }

        @keyframes pulse-green {
            0% {
                color: #198754;
                transform: scale(1);
            }
            50% {
                color: #0f5132;
                transform: scale(1.1);
            }
            100% {
                color: #198754;
                transform: scale(1);
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <h1 class="page-title">
            <i class="fas fa-hard-hat me-3"></i>
            الأعمال المدنية - أمر العمل رقم {{ $workOrder->work_order_number }}
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
        <div class="text-end mb-4">
            <a href="{{ route('admin.work-orders.execution', $workOrder) }}" 
               class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>عودة للتنفيذ
            </a>
        </div>

        <form method="POST" action="{{ route('admin.work-orders.civil-works.store', $workOrder) }}" 
              enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="row g-4">
                <!-- كارد الحفريات الأساسية -->
                <div class="col-md-6">
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
                                                <th style="width: 35%">نوع الكابل</th>
                                                <th style="width: 15%">الطول (متر)</th>
                                                <th style="width: 15%">العرض (متر)</th>
                                                <th style="width: 15%">العمق (متر)</th>
                                                <th style="width: 20%">الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach([ 'كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط'] as $cable)
                                        <tr>
                                                <td class="align-middle">{{ $cable }}</td>
                                                <td colspan="4">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_unsurfaced_soil[{{ $loop->index }}]" 
                                                               value="{{ old('excavation_unsurfaced_soil.' . $loop->index) }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">متر</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            <!-- حفر مفتوح اكبر من 4 كابلات - حقول منفصلة -->
                                            <tr class="table-warning">
                                                <td class="align-middle fw-bold">حفر مفتوح اكبر من 4 كابلات</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_unsurfaced_soil_open[length]" 
                                                               data-type="length"
                                                               data-target="unsurfaced_soil_open"
                                                               value="{{ old('excavation_unsurfaced_soil_open.length') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_unsurfaced_soil_open[width]" 
                                                               data-type="width"
                                                               data-target="unsurfaced_soil_open"
                                                               value="{{ old('excavation_unsurfaced_soil_open.width') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_unsurfaced_soil_open[depth]" 
                                                               data-type="depth"
                                                               data-target="unsurfaced_soil_open"
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
                                                <th style="width: 35%">نوع الكابل</th>
                                                <th style="width: 15%">الطول (متر)</th>
                                                <th style="width: 15%">العرض (متر)</th>
                                                <th style="width: 15%">العمق (متر)</th>
                                                <th style="width: 20%">الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach([ 'كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط'] as $cable)
                                        <tr>
                                                <td class="align-middle">{{ $cable }}</td>
                                                <td colspan="4">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_surfaced_soil[{{ $loop->index }}]" 
                                                               value="{{ old('excavation_surfaced_soil.' . $loop->index) }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">متر</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            <!-- حفر مفتوح اكبر من 4 كابلات - حقول منفصلة -->
                                            <tr class="table-warning">
                                                <td class="align-middle fw-bold">حفر مفتوح اكبر من 4 كابلات</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_surfaced_soil_open[length]" 
                                                               data-type="length"
                                                               data-target="surfaced_soil_open"
                                                               value="{{ old('excavation_surfaced_soil_open.length') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_surfaced_soil_open[width]" 
                                                               data-type="width"
                                                               data-target="surfaced_soil_open"
                                                               value="{{ old('excavation_surfaced_soil_open.width') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_surfaced_soil_open[depth]" 
                                                               data-type="depth"
                                                               data-target="surfaced_soil_open"
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
                                                <th style="width: 35%">نوع الكابل</th>
                                                <th style="width: 15%">الطول (متر)</th>
                                                <th style="width: 15%">العرض (متر)</th>
                                                <th style="width: 15%">العمق (متر)</th>
                                                <th style="width: 20%">الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach([ 'كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط'] as $cable)
                                        <tr>
                                                <td class="align-middle">{{ $cable }}</td>
                                                <td colspan="4">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_surfaced_rock[{{ $loop->index }}]" 
                                                               value="{{ old('excavation_surfaced_rock.' . $loop->index) }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">متر</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            <!-- حفر مفتوح اكبر من 4 كابلات - حقول منفصلة -->
                                            <tr class="table-warning">
                                                <td class="align-middle fw-bold">حفر مفتوح اكبر من 4 كابلات</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_surfaced_rock_open[length]" 
                                                               data-type="length"
                                                               data-target="surfaced_rock_open"
                                                               value="{{ old('excavation_surfaced_rock_open.length') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_surfaced_rock_open[width]" 
                                                               data-type="width"
                                                               data-target="surfaced_rock_open"
                                                               value="{{ old('excavation_surfaced_rock_open.width') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_surfaced_rock_open[depth]" 
                                                               data-type="depth"
                                                               data-target="surfaced_rock_open"
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
                                                <th style="width: 35%">نوع الكابل</th>
                                                <th style="width: 15%">الطول (متر)</th>
                                                <th style="width: 15%">العرض (متر)</th>
                                                <th style="width: 15%">العمق (متر)</th>
                                                <th style="width: 20%">الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach([ 'كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط'] as $cable)
                                        <tr>
                                                <td class="align-middle">{{ $cable }}</td>
                                                <td colspan="4">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_unsurfaced_rock[{{ $loop->index }}]" 
                                                               value="{{ old('excavation_unsurfaced_rock.' . $loop->index) }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">متر</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            <!-- حفر مفتوح اكبر من 4 كابلات - حقول منفصلة -->
                                            <tr class="table-warning">
                                                <td class="align-middle fw-bold">حفر مفتوح اكبر من 4 كابلات</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_unsurfaced_rock_open[length]" 
                                                               data-type="length"
                                                               data-target="unsurfaced_rock_open"
                                                               value="{{ old('excavation_unsurfaced_rock_open.length') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_unsurfaced_rock_open[width]" 
                                                               data-type="width"
                                                               data-target="unsurfaced_rock_open"
                                                               value="{{ old('excavation_unsurfaced_rock_open.width') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_unsurfaced_rock_open[depth]" 
                                                               data-type="depth"
                                                               data-target="unsurfaced_rock_open"
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
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">الحفر المفتوح </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 35%">العنصر</th>
                                            <th style="width: 15%">الطول (متر)</th>
                                            <th style="width: 15%">العرض (متر)</th>
                                            <th style="width: 15%">العمق (متر)</th>
                                            <th style="width: 20%">الإجمالي (م³)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        
                                        <tr>
                                            <td class="align-middle">أسفلت طبقة أولى</td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.01" class="form-control dimension-input calculate-area" 
                                                           name="open_excavation[first_asphalt][length]" 
                                                           data-row="first_asphalt"
                                                           value="{{ old('open_excavation.first_asphalt.length') }}"
                                                           placeholder="0.00">
                                                    <span class="input-group-text">م</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.01" class="form-control dimension-input calculate-area" 
                                                           name="open_excavation[first_asphalt][width]" 
                                                           data-row="first_asphalt"
                                                           value="{{ old('open_excavation.first_asphalt.width') }}"
                                                           placeholder="0.00">
                                                    <span class="input-group-text">م</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" readonly>
                                                    <span class="input-group-text">-</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control total-input" 
                                                           id="total-first_asphalt" 
                                                           readonly 
                                                           value="0.00">
                                                    <span class="input-group-text">م²</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle">كشط واعادة السفلتة</td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.01" class="form-control dimension-input calculate-area" 
                                                           name="open_excavation[asphalt_scraping][length]" 
                                                           data-row="asphalt_scraping"
                                                           value="{{ old('open_excavation.asphalt_scraping.length') }}"
                                                           placeholder="0.00">
                                                    <span class="input-group-text">م</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.01" class="form-control dimension-input calculate-area" 
                                                           name="open_excavation[asphalt_scraping][width]" 
                                                           data-row="asphalt_scraping"
                                                           value="{{ old('open_excavation.asphalt_scraping.width') }}"
                                                           placeholder="0.00">
                                                    <span class="input-group-text">م</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" readonly>
                                                    <span class="input-group-text">-</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control total-input" 
                                                           id="total-asphalt_scraping" 
                                                           readonly 
                                                           value="0.00">
                                                    <span class="input-group-text">م²</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"><hr class='my-2'></td>
                                    
                                    
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="subsection mb-3">
                                <h6 class="subsection-title">حفريات تربة صخرية مسفلتة</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th style="width: 60%">نوع الكابل</th>
                                                <th style="width: 40%">الطول (متر)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach([ 'كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط', 'حفر مفتوح اكبر من 4 كابلات'] as $cable)

                                                <td class="align-middle">{{ $cable }}</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_surfaced_rock[{{ $loop->index }}]" 
                                                               value="{{ old('excavation_surfaced_rock.' . $loop->index) }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- حفريات دقيقة -->
                            <div class="subsection mb-3">
                                <h6 class="subsection-title">حفريات دقيقة</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th style="width: 40%">نوع الحفر</th>
                                                <th style="width: 30%">الأبعاد</th>
                                                <th style="width: 30%">الطول (متر)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="align-middle">حفر متوسط</td>
                                                <td class="align-middle">20 × 80</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_precise[medium]" 
                                                               value="{{ old('excavation_precise.medium', $workOrder->excavation_precise['medium'] ?? '') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle">حفر منخفض</td>
                                                <td class="align-middle">20 × 56</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_precise[low]" 
                                                               value="{{ old('excavation_precise.low', $workOrder->excavation_precise['low'] ?? '') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                        <td class="align-middle">تمديد كيبل 4x70 منخفض</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[cable_4x70_low][meters]" value="{{ old('electrical_items.cable_4x70_low.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">تمديد كيبل 4x185 منخفض</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[cable_4x185_low][meters]" value="{{ old('electrical_items.cable_4x185_low.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">تمديد كيبل 4x300 منخفض</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[cable_4x300_low][meters]" value="{{ old('electrical_items.cable_4x300_low.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">تمديد كيبل 3x500 متوسط</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[cable_3x500_med][meters]" value="{{ old('electrical_items.cable_3x500_med.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">تمديد كيبل 3x400 متوسط</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[cable_3x400_med][meters]" value="{{ old('electrical_items.cable_3x400_med.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                                <h6 class="fw-bold mb-2">حفريات تربة صخرية غير مسفلتة</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 35%">نوع الكابل</th>
                                                <th style="width: 15%">الطول (متر)</th>
                                                <th style="width: 15%">العرض (متر)</th>
                                                <th style="width: 15%">العمق (متر)</th>
                                                <th style="width: 20%">الإجمالي</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach([ 'كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط'] as $cable)
                                        <tr>
                                                <td class="align-middle">{{ $cable }}</td>
                                                <td colspan="4">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_unsurfaced_rock[{{ $loop->index }}]" 
                                                               value="{{ old('excavation_unsurfaced_rock.' . $loop->index) }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">متر</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                            <!-- حفر مفتوح اكبر من 4 كابلات - حقول منفصلة -->
                                            <tr class="table-warning">
                                                <td class="align-middle fw-bold">حفر مفتوح اكبر من 4 كابلات</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_unsurfaced_rock_open[length]" 
                                                               data-type="length"
                                                               data-target="unsurfaced_rock_open"
                                                               value="{{ old('excavation_unsurfaced_rock_open.length') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_unsurfaced_rock_open[width]" 
                                                               data-type="width"
                                                               data-target="unsurfaced_rock_open"
                                                               value="{{ old('excavation_unsurfaced_rock_open.width') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input excavation-calc" 
                                                               name="excavation_unsurfaced_rock_open[depth]" 
                                                               data-type="depth"
                                                               data-target="unsurfaced_rock_open"
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
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                </div>

                <!-- جدول ملخص البيانات -->
                <div class="col-12">
                    <div class="card shadow-lg mb-4 border-0">
                        <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="fas fa-table me-2"></i>
                            <h5 class="mb-0 d-inline">ملخص الأعمال المدنية المدخلة</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info border-0 shadow-sm">
                                <i class="fas fa-info-circle me-2"></i>
                                هذا الجدول يعرض ملخصاً شاملاً لجميع البيانات التي تم إدخالها في نموذج الأعمال المدنية
                            </div>
                            
                            <!-- إحصائيات سريعة -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="card bg-primary text-white border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="fas fa-cube fa-2x mb-2"></i>
                                            <h4 class="mb-1" id="total-volume">0.00</h4>
                                            <small>إجمالي الحجم (م³)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="fas fa-square fa-2x mb-2"></i>
                                            <h4 class="mb-1" id="total-area">0.00</h4>
                                            <small>إجمالي المساحة (م²)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="fas fa-ruler fa-2x mb-2"></i>
                                            <h4 class="mb-1" id="total-length">0.00</h4>
                                            <small>إجمالي الطول (م)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="fas fa-tasks fa-2x mb-2"></i>
                                            <h4 class="mb-1" id="completed-fields">0</h4>
                                            <small>الحقول المكتملة</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- جدول تفصيلي -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle" id="summary-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 5%">#</th>
                                            <th style="width: 35%">نوع العمل</th>
                                            <th style="width: 15%">الكمية/القيمة</th>
                                            <th style="width: 10%">الوحدة</th>
                                            <th style="width: 20%">الفئة</th>
                                            <th style="width: 15%">الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody id="summary-tbody">
                                        <!-- سيتم ملء البيانات تلقائياً بواسطة JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- ملاحظات إضافية -->
                            <div class="mt-4">
                                <h6 class="text-muted mb-3">
                                    <i class="fas fa-sticky-note me-2"></i>ملاحظات إضافية
                                </h6>
                                <div class="form-floating">
                                    <textarea class="form-control" 
                                              id="additional_notes" 
                                              name="additional_notes" 
                                              style="height: 100px"
                                              placeholder="أضف أي ملاحظات إضافية هنا...">{{ old('additional_notes', $workOrder->additional_notes ?? '') }}</textarea>
                                    <label for="additional_notes">ملاحظات إضافية</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- جدول شامل لبيانات الحفريات -->
                <div class="col-12">
                    <div class="card shadow-lg mb-4 border-0">
                        <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                            <i class="fas fa-shovel me-2"></i>
                            <h5 class="mb-0 d-inline">جدول تفصيلي لجميع بيانات الحفريات المدخلة</h5>
                            <button type="button" class="btn btn-light btn-sm float-end" onclick="exportExcavationData()">
                                <i class="fas fa-download me-1"></i>تصدير Excel
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm me-2 float-end" onclick="updateExcavationDetailsTable()">
                                <i class="fas fa-sync-alt me-1"></i>تحديث الجدول
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning border-0 shadow-sm">
                                <i class="fas fa-excavator me-2"></i>
                                هذا الجدول يعرض تفاصيل شاملة لجميع أنواع الحفريات والأعمال المدنية المدخلة في النموذج
                            </div>
                            
                            <!-- أزرار التصفية -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-primary active" data-filter="all">الكل</button>
                                        <button type="button" class="btn btn-outline-success" data-filter="soil">حفريات تربة</button>
                                        <button type="button" class="btn btn-outline-danger" data-filter="rock">حفريات صخرية</button>
                                        <button type="button" class="btn btn-outline-warning" data-filter="asphalt">أعمال أسفلت</button>
                                        <button type="button" class="btn btn-outline-info" data-filter="precise">حفريات دقيقة</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-sm" id="excavation-search" placeholder="البحث في البيانات...">
                                </div>
                            </div>

                            <!-- الجدول التفصيلي -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle" id="excavation-details-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 5%">#</th>
                                            <th style="width: 20%">نوع الحفر</th>
                                            <th style="width: 15%">تصنيف الكابل</th>
                                            <th style="width: 10%">نوع السطح</th>
                                            <th style="width: 8%">الطول (م)</th>
                                            <th style="width: 8%">العرض (م)</th>
                                            <th style="width: 8%">العمق (م)</th>
                                            <th style="width: 10%">الحجم (م³)</th>
                                            <th style="width: 8%">الكمية</th>
                                            <th style="width: 8%">الوحدة</th>
                                        </tr>
                                    </thead>
                                    <tbody id="excavation-details-tbody">
                                        <!-- سيتم ملء البيانات تلقائياً بواسطة JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- إحصائيات تفصيلية -->
                            <div class="row mt-4 stats-cards">
                                <div class="col-md-3">
                                    <div class="card bg-success text-white border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="fas fa-mountain fa-2x mb-2"></i>
                                            <h5 class="mb-1" id="total-soil-excavation">0.00</h5>
                                            <small>إجمالي حفريات التربة (م)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-danger text-white border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="fas fa-hammer fa-2x mb-2"></i>
                                            <h5 class="mb-1" id="total-rock-excavation">0.00</h5>
                                            <small>إجمالي حفريات الصخر (م)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="fas fa-road fa-2x mb-2"></i>
                                            <h5 class="mb-1" id="total-asphalt-work">0.00</h5>
                                            <small>إجمالي أعمال الأسفلت (م²)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-info text-white border-0 shadow-sm">
                                        <div class="card-body text-center">
                                            <i class="fas fa-crosshairs fa-2x mb-2"></i>
                                            <h5 class="mb-1" id="total-precise-excavation">0.00</h5>
                                            <small>إجمالي الحفريات الدقيقة (م)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ملخص نهائي -->
                            <div class="mt-4 p-3 bg-light rounded">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-chart-bar me-2"></i>ملخص إجمالي للحفريات
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>إجمالي الطول:</strong> <span id="final-total-length" class="text-primary fw-bold">0.00 متر</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>إجمالي الحجم:</strong> <span id="final-total-volume" class="text-success fw-bold">0.00 متر مكعب</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>إجمالي المساحة:</strong> <span id="final-total-area" class="text-warning fw-bold">0.00 متر مربع</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- قسم رفع المرفقات -->
                <div class="col-12">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark">
                            <i class="fas fa-paperclip me-2"></i>
                            المرفقات والمستندات
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle me-2"></i>
                                يمكنك رفع المرفقات والمستندات المتعلقة بالأعمال المدنية (PDF, DOC, XLS, PPT, TXT) - حجم أقصى 20 ميجابايت لكل ملف
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
                                <div class="form-text">أنواع الملفات المسموحة: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, ZIP, RAR</div>
                            </div>

                            <div id="attachment-preview" class="row g-3">
                                <!-- سيتم إضافة معاينات المرفقات هنا -->
                            </div>

                            <div id="attachment-upload-progress" class="progress d-none mt-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" 
                                     role="progressbar" 
                                     style="width: 0%">0%</div>
                            </div>

                            <div id="attachment-upload-status" class="alert d-none mt-3"></div>

                            <!-- عرض المرفقات المرفوعة -->
                            @if(isset($workOrder->civilWorksAttachments) && $workOrder->civilWorksAttachments->count() > 0)
                                <div class="mt-4">
                                    <h5 class="mb-3">المرفقات المرفوعة</h5>
                                    <div class="row g-3">
                                        @foreach($workOrder->civilWorksAttachments as $file)
                                            <div class="col-md-4">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <i class="fas fa-file-{{ getFileIcon($file->original_filename) }} fa-2x text-primary me-3"></i>
                                                            <div class="flex-grow-1">
                                                                <h6 class="card-title mb-1 text-truncate" title="{{ $file->original_filename }}">
                                                                    {{ Str::limit($file->original_filename, 25) }}
                                                                </h6>
                                                                <p class="card-text small text-muted mb-0">
                                                                    {{ round($file->file_size / 1024 / 1024, 2) }} MB
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="btn-group w-100">
                                                            <a href="{{ asset('storage/' . $file->file_path) }}" 
                                                               class="btn btn-sm btn-outline-primary" 
                                                               target="_blank">
                                                                <i class="fas fa-download"></i> تحميل
                                                            </a>
                                                            <form action="{{ route('admin.work-orders.civil-works.delete-attachment', [$workOrder, $file]) }}" 
                                                                  method="POST" 
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المرفق؟');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                    <i class="fas fa-trash"></i> حذف
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- قسم رفع الصور -->
                <div class="col-12">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-images me-2"></i>
                            صور الأعمال المدنية
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                يمكنك رفع حتى 70 صورة بحجم إجمالي لا يتجاوز 30 ميجابايت
                            </div>
                            
                            <div class="mb-3">
                                <label for="civil_works_images" class="form-label">اختر الصور</label>
                                <input type="file" 
                                       class="form-control" 
                                       id="civil_works_images" 
                                       name="civil_works_images[]" 
                                       multiple 
                                       accept="image/*"
                                       data-max-files="70"
                                       data-max-size="31457280">
                                <div class="form-text">يمكنك اختيار عدة صور عن طريق الضغط على Ctrl أثناء الاختيار</div>
                            </div>

                            <div id="image-preview" class="row g-3">
                                <!-- سيتم إضافة معاينات الصور هنا -->
                            </div>

                            <div id="upload-progress" class="progress d-none mt-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: 0%">0%</div>
                            </div>

                            <div id="upload-status" class="alert d-none mt-3"></div>

                            <!-- عرض الصور المرفوعة -->
                            @if($workOrder->civilWorksFiles->count() > 0)
                                <div class="mt-4">
                                    <h5 class="mb-3">الصور المرفوعة</h5>
                                    <div class="row g-3">
                                        @foreach($workOrder->civilWorksFiles as $file)
                                            <div class="col-md-3">
                                                <div class="card h-100">
                                                    <img src="{{ asset('storage/' . $file->file_path) }}" 
                                                         class="card-img-top" 
                                                         alt="{{ $file->original_filename }}"
                                                         style="height: 200px; object-fit: cover;">
                                                    <div class="card-body">
                                                        <p class="card-text small text-truncate" title="{{ $file->original_filename }}">
                                                            {{ Str::limit($file->original_filename, 30) }}
                                                        </p>
                                                        <p class="card-text small text-muted">
                                                            {{ round($file->file_size / 1024 / 1024, 2) }} MB
                                                        </p>
                                                        <div class="btn-group w-100">
                                                            <a href="{{ asset('storage/' . $file->file_path) }}" 
                                                               class="btn btn-sm btn-info" 
                                                               target="_blank">
                                                                <i class="fas fa-eye"></i> عرض
                                                            </a>
                                                            <form action="{{ route('admin.work-orders.civil-works.delete-file', [$workOrder, $file]) }}" 
                                                                  method="POST" 
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fas fa-trash"></i> حذف
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- زر للانتقال إلى الجدول التفصيلي -->
                <div class="col-12 mb-4">
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-lg px-5 py-3 shadow-lg" onclick="scrollToExcavationDetails()">
                            <i class="fas fa-table me-3"></i>
                            عرض الجدول التفصيلي للحفريات
                            <i class="fas fa-arrow-down ms-3"></i>
                        </button>
                    </div>
                    
                    <!-- ملاحظات إضافية -->
                    <div class="mt-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-sticky-note me-2"></i>ملاحظات إضافية
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="form-floating">
                                    <textarea class="form-control" 
                                              id="additional_notes" 
                                              name="additional_notes" 
                                              style="height: 100px"
                                              placeholder="أضف أي ملاحظات إضافية هنا...">{{ old('additional_notes', $workOrder->additional_notes ?? '') }}</textarea>
                                    <label for="additional_notes">ملاحظات إضافية</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5 mb-4">
                <button type="submit" class="btn btn-primary btn-lg px-5 py-3 shadow-lg">
                    <i class="fas fa-save me-3"></i>
                    حفظ الأعمال المدنية
                    <i class="fas fa-arrow-left ms-3"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // دالة لحساب الإجمالي لكل صف (حجم)
        function calculateTotal(rowId) {
            const length = parseFloat(document.querySelector(`input[data-row="${rowId}"][name$="[length]"]`).value) || 0;
            const width = parseFloat(document.querySelector(`input[data-row="${rowId}"][name$="[width]"]`).value) || 0;
            const depth = parseFloat(document.querySelector(`input[data-row="${rowId}"][name$="[depth]"]`).value) || 0;
            
            const total = length * width * depth;
            document.getElementById(`total-${rowId}`).value = total.toFixed(2);
        }

        // دالة لحساب المساحة لكل صف (متر مربع)
        function calculateArea(rowId) {
            const length = parseFloat(document.querySelector(`input[data-row="${rowId}"][name$="[length]"]`).value) || 0;
            const width = parseFloat(document.querySelector(`input[data-row="${rowId}"][name$="[width]"]`).value) || 0;
            
            const total = length * width;
            document.getElementById(`total-${rowId}`).value = total.toFixed(2);
        }

        // إضافة مستمع الحدث لجميع حقول الإدخال (الحجم)
        document.querySelectorAll('.calculate-total').forEach(input => {
            input.addEventListener('input', function() {
                calculateTotal(this.dataset.row);
            });
        });

        // إضافة مستمع الحدث لجميع حقول الإدخال (المساحة)
        document.querySelectorAll('.calculate-area').forEach(input => {
            input.addEventListener('input', function() {
                calculateArea(this.dataset.row);
            });
        });

        // حساب الإجماليات الأولية عند تحميل الصفحة (الحجم)
        const volumeRows = ['medium', 'low', 'sand_under', 'sand_over', 'first_sibz', 'second_sibz', 'concrete'];
        volumeRows.forEach(row => calculateTotal(row));

        // حساب الإجماليات الأولية عند تحميل الصفحة (المساحة)
        const areaRows = ['first_asphalt', 'asphalt_scraping'];
        areaRows.forEach(row => calculateArea(row));

        // دالة لحساب الحجم للحفر المفتوح أكبر من 4 كابلات
        function calculateExcavationVolume(targetId) {
            const lengthInput = document.querySelector(`input[data-target="${targetId}"][data-type="length"]`);
            const widthInput = document.querySelector(`input[data-target="${targetId}"][data-type="width"]`);
            const depthInput = document.querySelector(`input[data-target="${targetId}"][data-type="depth"]`);
            const totalField = document.getElementById(`total_${targetId}`);
            
            if (lengthInput && widthInput && depthInput && totalField) {
                const length = parseFloat(lengthInput.value) || 0;
                const width = parseFloat(widthInput.value) || 0;
                const depth = parseFloat(depthInput.value) || 0;
                
                const volume = length * width * depth;
                totalField.value = volume.toFixed(2);
            }
        }

        // إضافة مستمع الأحداث لحقول الحفر المفتوح
        document.querySelectorAll('.excavation-calc').forEach(input => {
            input.addEventListener('input', function() {
                const targetId = this.dataset.target;
                calculateExcavationVolume(targetId);
            });
        });

        // حساب الأحجام الأولية للحفر المفتوح
        const excavationTargets = ['unsurfaced_soil_open', 'surfaced_soil_open', 'unsurfaced_rock_open', 'surfaced_rock_open', 'surfaced_rock_open_2'];
        excavationTargets.forEach(target => calculateExcavationVolume(target));

        // دالة لتحديث جدول الملخص
        function updateSummaryTable() {
            const tbody = document.getElementById('summary-tbody');
            if (!tbody) return;

            tbody.innerHTML = '';
            let totalVolume = 0;
            let totalArea = 0;
            let totalLength = 0;
            let completedFields = 0;
            let rowIndex = 1;

            // Object to store all categories
            const categories = {
                'حفريات تربة': [],
                'حفريات صخرية': [],
                'أعمال السفلتة': [],
                'تمديدات الكابلات': [],
                'حفريات دقيقة': []
            };

            // 1. جمع بيانات الحفريات الترابية
            const soilExcavations = [
                { name: 'حفريات تربة ترابية غير مسفلتة', prefix: 'excavation_unsurfaced_soil' },
                { name: 'حفريات تربة ترابية مسفلتة', prefix: 'excavation_surfaced_soil' }
            ];

            soilExcavations.forEach(type => {
                document.querySelectorAll(`input[name^="${type.prefix}"]`).forEach(input => {
                    if (input.value && parseFloat(input.value) > 0) {
                        const value = parseFloat(input.value);
                        totalLength += value;
                        completedFields++;
                        
                        // Get cable type from parent row if available
                        const cableType = input.closest('tr')?.querySelector('td')?.textContent || 'غير محدد';
                        
                        categories['حفريات تربة'].push({
                            name: `${type.name} - ${cableType}`,
                            value: value,
                            unit: 'متر'
                        });
                    }
                });
            });

            // 2. جمع بيانات الحفريات الصخرية
            const rockExcavations = [
                { name: 'حفريات تربة صخرية غير مسفلتة', prefix: 'excavation_unsurfaced_rock' },
                { name: 'حفريات تربة صخرية مسفلتة', prefix: 'excavation_surfaced_rock' }
            ];

            rockExcavations.forEach(type => {
                document.querySelectorAll(`input[name^="${type.prefix}"]`).forEach(input => {
                    if (input.value && parseFloat(input.value) > 0) {
                        const value = parseFloat(input.value);
                        totalLength += value;
                        completedFields++;
                        
                        const cableType = input.closest('tr')?.querySelector('td')?.textContent || 'غير محدد';
                        
                        categories['حفريات صخرية'].push({
                            name: `${type.name} - ${cableType}`,
                            value: value,
                            unit: 'متر'
                        });
                    }
                });
            });

            // 3. جمع بيانات الحفر المفتوح والأسفلت
            const openExcavations = [
                { name: 'أسفلت طبقة أولى', id: 'total-first_asphalt', unit: 'م²' },
                { name: 'كشط وإعادة السفلتة', id: 'total-asphalt_scraping', unit: 'م²' }
            ];

            openExcavations.forEach(item => {
                const totalField = document.getElementById(item.id);
                if (totalField && totalField.value && parseFloat(totalField.value) > 0) {
                    const value = parseFloat(totalField.value);
                    totalArea += value;
                    completedFields++;
                    
                    categories['أعمال السفلتة'].push({
                        name: item.name,
                        value: value,
                        unit: item.unit
                    });
                }
            });

            // 4. جمع بيانات الكابلات
            document.querySelectorAll('input[name*="electrical_items"]').forEach(input => {
                if (input.value && parseFloat(input.value) > 0) {
                    const value = parseFloat(input.value);
                    totalLength += value;
                    completedFields++;
                    
                    const cableType = input.closest('tr')?.querySelector('td')?.textContent || 'غير محدد';
                    
                    categories['تمديدات الكابلات'].push({
                        name: cableType,
                        value: value,
                        unit: 'متر'
                    });
                }
            });

            // 5. جمع بيانات الحفريات الدقيقة
            document.querySelectorAll('input[name*="excavation_precise"]').forEach(input => {
                if (input.value && parseFloat(input.value) > 0) {
                    const value = parseFloat(input.value);
                    totalLength += value;
                    completedFields++;
                    
                    const rowName = input.closest('tr')?.querySelector('td')?.textContent || 'غير محدد';
                    
                    categories['حفريات دقيقة'].push({
                        name: rowName,
                        value: value,
                        unit: 'متر'
                    });
                }
            });

            // إضافة البيانات للجدول مع التصنيف
            for (const [category, items] of Object.entries(categories)) {
                if (items.length > 0) {
                    // إضافة عنوان التصنيف
                    const headerRow = tbody.insertRow();
                    headerRow.innerHTML = `
                        <td colspan="6" class="bg-light">
                            <h6 class="mb-0 fw-bold text-primary">
                                <i class="fas fa-folder-open me-2"></i>${category}
                            </h6>
                        </td>
                    `;

                    // إضافة العناصر
                    items.forEach(item => {
                        const row = tbody.insertRow();
                        row.innerHTML = `
                            <td class="text-center">${rowIndex++}</td>
                            <td>${item.name}</td>
                            <td class="text-center fw-bold text-primary">${item.value.toFixed(2)}</td>
                            <td class="text-center">${item.unit}</td>
                            <td><span class="badge bg-info">${category}</span></td>
                            <td><span class="badge bg-success"><i class="fas fa-check me-1"></i>مكتمل</span></td>
                        `;
                    });
                }
            }

            // تحديث الإحصائيات
            document.getElementById('total-volume').textContent = totalVolume.toFixed(2);
            document.getElementById('total-area').textContent = totalArea.toFixed(2);
            document.getElementById('total-length').textContent = totalLength.toFixed(2);
            document.getElementById('completed-fields').textContent = completedFields;

            // إضافة رسالة إذا لم يتم إدخال بيانات
            if (rowIndex === 1) {
                const row = tbody.insertRow();
                row.innerHTML = `
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                        لم يتم إدخال أي بيانات بعد. ابدأ بملء النموذج لرؤية الملخص هنا.
                    </td>
                `;
            }
        }

        // تحديث المستمعين لجميع الحقول
        function setupInputListeners() {
            const allInputs = document.querySelectorAll('input[type="number"], input.dimension-input, input.form-control');
            allInputs.forEach(input => {
                input.addEventListener('input', () => {
                    // حفظ العدد الحالي للصفوف قبل التحديث
                    const currentRows = document.querySelectorAll('#excavation-details-tbody tr:not(.table-info)').length;
                    
                    setTimeout(() => {
                        updateExcavationDetailsTable();
                        
                        // التحقق من إضافة صفوف جديدة
                        const newRows = document.querySelectorAll('#excavation-details-tbody tr:not(.table-info)').length;
                        if (newRows > currentRows) {
                            showNewItemNotification();
                        }
                    }, 150);
                });
            });
        }

        // دالة لإظهار إشعار عند إضافة عنصر جديد
        function showNewItemNotification() {
            // إنشاء إشعار مؤقت
            const notification = document.createElement('div');
            notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
            notification.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                animation: slideInRight 0.5s ease-out;
            `;
            notification.innerHTML = `
                <i class="fas fa-plus-circle me-2"></i>
                <strong>عنصر جديد!</strong> تم إضافة عنصر جديد إلى الجدول التفصيلي
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // إزالة الإشعار تلقائياً بعد 3 ثوانٍ
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.animation = 'slideOutRight 0.5s ease-in';
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }
            }, 3000);
        }

        // إضافة CSS للرسوم المتحركة
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // إعداد المستمعين عند تحميل الصفحة
        setupInputListeners();

        // إعداد الجدول التفصيلي للحفريات
        setupExcavationDetailsTable();
        
        // إعداد المستمعين للتصفية والبحث
        setupExcavationFilters();
        
        // تحديث أولي للجدول التفصيلي
        setTimeout(updateExcavationDetailsTable, 700);
    });

    // دالة لإعداد الجدول التفصيلي للحفريات
    function setupExcavationDetailsTable() {
        // تحديث الجدول عند تغيير أي حقل - تم نقله لدالة setupInputListeners
        // لا حاجة لتكرار المستمعين هنا
    }

    // دالة لتحديث الجدول التفصيلي للحفريات
    function updateExcavationDetailsTable() {
        const tbody = document.getElementById('excavation-details-tbody');
        if (!tbody) return;
        
        tbody.innerHTML = '';
        let rowIndex = 1;
        
        // إحصائيات منفصلة
        let totalSoilExcavation = 0;
        let totalRockExcavation = 0;
        let totalAsphaltWork = 0;
        let totalPreciseExcavation = 0;
        let finalTotalLength = 0;
        let finalTotalVolume = 0;
        let finalTotalArea = 0;

        // 1. جمع بيانات حفريات التربة غير المسفلتة
        const soilUnsurfacedCables = [
            'كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض',
            '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط'
        ];
        
        soilUnsurfacedCables.forEach((cableType, index) => {
            const input = document.querySelector(`input[name="excavation_unsurfaced_soil[${index}]"]`);
            if (input && input.value && parseFloat(input.value) > 0) {
                const value = parseFloat(input.value);
                totalSoilExcavation += value;
                finalTotalLength += value;
                
                addExcavationRow(tbody, rowIndex++, 'حفريات تربة ترابية', cableType, 'غير مسفلتة', value, '', '', '', value, 'متر', 'soil');
            }
        });

        // حفر مفتوح تربة غير مسفلتة
        const unsurfacedSoilLength = document.querySelector('input[name="excavation_unsurfaced_soil_open[length]"]');
        const unsurfacedSoilWidth = document.querySelector('input[name="excavation_unsurfaced_soil_open[width]"]');
        const unsurfacedSoilDepth = document.querySelector('input[name="excavation_unsurfaced_soil_open[depth]"]');
        
        if (unsurfacedSoilLength && unsurfacedSoilWidth && unsurfacedSoilDepth && 
            unsurfacedSoilLength.value && unsurfacedSoilWidth.value && unsurfacedSoilDepth.value &&
            parseFloat(unsurfacedSoilLength.value) > 0 && parseFloat(unsurfacedSoilWidth.value) > 0 && parseFloat(unsurfacedSoilDepth.value) > 0) {
            const length = parseFloat(unsurfacedSoilLength.value);
            const width = parseFloat(unsurfacedSoilWidth.value);
            const depth = parseFloat(unsurfacedSoilDepth.value);
            const volume = length * width * depth;
            
            totalSoilExcavation += length;
            finalTotalLength += length;
            finalTotalVolume += volume;
            
            addExcavationRow(tbody, rowIndex++, 'حفريات تربة ترابية', 'حفر مفتوح أكبر من 4 كابلات', 'غير مسفلتة', length, width, depth, volume, volume, 'م³', 'soil');
        }

        // 2. جمع بيانات حفريات التربة المسفلتة
        soilUnsurfacedCables.forEach((cableType, index) => {
            const input = document.querySelector(`input[name="excavation_surfaced_soil[${index}]"]`);
            if (input && input.value && parseFloat(input.value) > 0) {
                const value = parseFloat(input.value);
                totalSoilExcavation += value;
                finalTotalLength += value;
                
                addExcavationRow(tbody, rowIndex++, 'حفريات تربة ترابية', cableType, 'مسفلتة', value, '', '', '', value, 'متر', 'soil');
            }
        });

        // حفر مفتوح تربة مسفلتة
        const surfacedSoilLength = document.querySelector('input[name="excavation_surfaced_soil_open[length]"]');
        const surfacedSoilWidth = document.querySelector('input[name="excavation_surfaced_soil_open[width]"]');
        const surfacedSoilDepth = document.querySelector('input[name="excavation_surfaced_soil_open[depth]"]');
        
        if (surfacedSoilLength && surfacedSoilWidth && surfacedSoilDepth && 
            surfacedSoilLength.value && surfacedSoilWidth.value && surfacedSoilDepth.value &&
            parseFloat(surfacedSoilLength.value) > 0 && parseFloat(surfacedSoilWidth.value) > 0 && parseFloat(surfacedSoilDepth.value) > 0) {
            const length = parseFloat(surfacedSoilLength.value);
            const width = parseFloat(surfacedSoilWidth.value);
            const depth = parseFloat(surfacedSoilDepth.value);
            const volume = length * width * depth;
            
            totalSoilExcavation += length;
            finalTotalLength += length;
            finalTotalVolume += volume;
            
            addExcavationRow(tbody, rowIndex++, 'حفريات تربة ترابية', 'حفر مفتوح أكبر من 4 كابلات', 'مسفلتة', length, width, depth, volume, volume, 'م³', 'soil');
        }

        // 3. جمع بيانات حفريات الصخر غير المسفلتة
        soilUnsurfacedCables.forEach((cableType, index) => {
            const input = document.querySelector(`input[name="excavation_unsurfaced_rock[${index}]"]`);
            if (input && input.value && parseFloat(input.value) > 0) {
                const value = parseFloat(input.value);
                totalRockExcavation += value;
                finalTotalLength += value;
                
                addExcavationRow(tbody, rowIndex++, 'حفريات تربة صخرية', cableType, 'غير مسفلتة', value, '', '', '', value, 'متر', 'rock');
            }
        });

        // حفر مفتوح صخر غير مسفلت
        const unsurfacedRockLength = document.querySelector('input[name="excavation_unsurfaced_rock_open[length]"]');
        const unsurfacedRockWidth = document.querySelector('input[name="excavation_unsurfaced_rock_open[width]"]');
        const unsurfacedRockDepth = document.querySelector('input[name="excavation_unsurfaced_rock_open[depth]"]');
        
        if (unsurfacedRockLength && unsurfacedRockWidth && unsurfacedRockDepth && 
            unsurfacedRockLength.value && unsurfacedRockWidth.value && unsurfacedRockDepth.value &&
            parseFloat(unsurfacedRockLength.value) > 0 && parseFloat(unsurfacedRockWidth.value) > 0 && parseFloat(unsurfacedRockDepth.value) > 0) {
            const length = parseFloat(unsurfacedRockLength.value);
            const width = parseFloat(unsurfacedRockWidth.value);
            const depth = parseFloat(unsurfacedRockDepth.value);
            const volume = length * width * depth;
            
            totalRockExcavation += length;
            finalTotalLength += length;
            finalTotalVolume += volume;
            
            addExcavationRow(tbody, rowIndex++, 'حفريات تربة صخرية', 'حفر مفتوح أكبر من 4 كابلات', 'غير مسفلتة', length, width, depth, volume, volume, 'م³', 'rock');
        }

        // 4. جمع بيانات حفريات الصخر المسفلتة (القسم الأول)
        soilUnsurfacedCables.forEach((cableType, index) => {
            const input = document.querySelector(`input[name="excavation_surfaced_rock[${index}]"]`);
            if (input && input.value && parseFloat(input.value) > 0) {
                const value = parseFloat(input.value);
                totalRockExcavation += value;
                finalTotalLength += value;
                
                addExcavationRow(tbody, rowIndex++, 'حفريات تربة صخرية', cableType, 'مسفلتة (القسم الأول)', value, '', '', '', value, 'متر', 'rock');
            }
        });

        // حفر مفتوح صخر مسفلت (القسم الأول)
        const surfacedRockLength = document.querySelector('input[name="excavation_surfaced_rock_open[length]"]');
        const surfacedRockWidth = document.querySelector('input[name="excavation_surfaced_rock_open[width]"]');
        const surfacedRockDepth = document.querySelector('input[name="excavation_surfaced_rock_open[depth]"]');
        
        if (surfacedRockLength && surfacedRockWidth && surfacedRockDepth && 
            surfacedRockLength.value && surfacedRockWidth.value && surfacedRockDepth.value &&
            parseFloat(surfacedRockLength.value) > 0 && parseFloat(surfacedRockWidth.value) > 0 && parseFloat(surfacedRockDepth.value) > 0) {
            const length = parseFloat(surfacedRockLength.value);
            const width = parseFloat(surfacedRockWidth.value);
            const depth = parseFloat(surfacedRockDepth.value);
            const volume = length * width * depth;
            
            totalRockExcavation += length;
            finalTotalLength += length;
            finalTotalVolume += volume;
            
            addExcavationRow(tbody, rowIndex++, 'حفريات تربة صخرية', 'حفر مفتوح أكبر من 4 كابلات', 'مسفلتة (القسم الأول)', length, width, depth, volume, volume, 'م³', 'rock');
        }

        // 5. جمع بيانات حفريات الصخر المسفلتة (القسم الثاني)
        soilUnsurfacedCables.forEach((cableType, index) => {
            const input = document.querySelector(`input[name="excavation_surfaced_rock_second[${index}]"]`);
            if (input && input.value && parseFloat(input.value) > 0) {
                const value = parseFloat(input.value);
                totalRockExcavation += value;
                finalTotalLength += value;
                
                addExcavationRow(tbody, rowIndex++, 'حفريات تربة صخرية', cableType, 'مسفلتة (القسم الثاني)', value, '', '', '', value, 'متر', 'rock');
            }
        });

        // 6. جمع بيانات أعمال الأسفلت
        const firstAsphaltInput = document.getElementById('total-first_asphalt');
        const asphaltScrapingInput = document.getElementById('total-asphalt_scraping');
        
        if (firstAsphaltInput && firstAsphaltInput.value && parseFloat(firstAsphaltInput.value) > 0) {
            const value = parseFloat(firstAsphaltInput.value);
            totalAsphaltWork += value;
            finalTotalArea += value;
            
            addExcavationRow(tbody, rowIndex++, 'أعمال السفلتة', 'أسفلت طبقة أولى', '-', '', '', '', '', value, 'م²', 'asphalt');
        }
        
        if (asphaltScrapingInput && asphaltScrapingInput.value && parseFloat(asphaltScrapingInput.value) > 0) {
            const value = parseFloat(asphaltScrapingInput.value);
            totalAsphaltWork += value;
            finalTotalArea += value;
            
            addExcavationRow(tbody, rowIndex++, 'أعمال السفلتة', 'كشط وإعادة السفلتة', '-', '', '', '', '', value, 'م²', 'asphalt');
        }

        // 7. جمع بيانات الحفريات الدقيقة
        document.querySelectorAll('input[name*="excavation_precise"]').forEach(input => {
            if (input.value && parseFloat(input.value) > 0) {
                const value = parseFloat(input.value);
                totalPreciseExcavation += value;
                finalTotalLength += value;
                
                const rowName = input.closest('tr')?.querySelector('td')?.textContent || 'حفر دقيق';
                addExcavationRow(tbody, rowIndex++, 'حفريات دقيقة', rowName, '-', '', '', '', '', value, 'متر', 'precise');
            }
        });

        // 8. جمع بيانات تمديدات الكابلات
        document.querySelectorAll('input[name*="electrical_items"]').forEach(input => {
            if (input.value && parseFloat(input.value) > 0) {
                const value = parseFloat(input.value);
                finalTotalLength += value;
                
                const rowName = input.closest('tr')?.querySelector('td')?.textContent || 'تمديد كابل';
                addExcavationRow(tbody, rowIndex++, 'تمديدات الكابلات', rowName, '-', '', '', '', '', value, 'متر', 'precise');
            }
        });

        // تحديث الإحصائيات التفصيلية
        updateExcavationStats(totalSoilExcavation, totalRockExcavation, totalAsphaltWork, totalPreciseExcavation);
        updateFinalTotals(finalTotalLength, finalTotalVolume, finalTotalArea);

        // إضافة رسالة إذا لم يتم إدخال بيانات
        if (rowIndex === 1) {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td colspan="10" class="text-center text-muted py-5">
                    <div class="d-flex flex-column align-items-center">
                        <i class="fas fa-excavator fa-4x mb-3 opacity-50 text-primary"></i>
                        <h4 class="text-muted">لا توجد بيانات حفريات مدخلة</h4>
                        <p class="text-muted">ابدأ بملء حقول الحفريات في النموذج أعلاه لرؤية التفاصيل هنا</p>
                        <div class="mt-3">
                            <span class="badge bg-info me-2">💡 نصيحة:</span>
                            <small class="text-muted">سيظهر هنا فقط العناصر التي تحتوي على قيم أكبر من صفر</small>
                        </div>
                    </div>
                </td>
            `;
        } else {
            // إضافة رسالة توضيحية في أعلى الجدول
            const infoRow = tbody.insertRow(0);
            infoRow.className = 'table-info';
            infoRow.innerHTML = `
                <td colspan="10" class="text-center py-2">
                    <small><i class="fas fa-info-circle me-1"></i>
                    يتم عرض العناصر التي تحتوي على قيم فقط - إجمالي العناصر المعروضة: <strong>${rowIndex - 1}</strong></small>
                </td>
            `;
        }
    }

    // دالة لإضافة صف للجدول التفصيلي مع تأثيرات بصرية
    function addExcavationRow(tbody, index, type, cable, surface, length, width, depth, volume, quantity, unit, category) {
        const row = tbody.insertRow();
        row.setAttribute('data-category', category);
        row.setAttribute('data-newly-added', 'true');
        
        // تحديد لون الصف حسب النوع
        let rowClass = '';
        let iconClass = '';
        switch(category) {
            case 'soil': 
                rowClass = 'table-success'; 
                iconClass = 'fas fa-mountain text-success';
                break;
            case 'rock': 
                rowClass = 'table-danger'; 
                iconClass = 'fas fa-hammer text-danger';
                break;
            case 'asphalt': 
                rowClass = 'table-warning'; 
                iconClass = 'fas fa-road text-warning';
                break;
            case 'precise': 
                rowClass = 'table-info'; 
                iconClass = 'fas fa-crosshairs text-info';
                break;
        }
        
        row.className = rowClass + ' excavation-row-animated';
        row.innerHTML = `
            <td class="text-center fw-bold align-middle">
                <span class="badge bg-primary rounded-pill">${index}</span>
            </td>
            <td class="align-middle">
                <i class="${iconClass} me-2"></i>
                <strong>${type}</strong>
            </td>
            <td class="align-middle">${cable}</td>
            <td class="text-center align-middle">
                ${surface !== '-' ? `<span class="badge bg-secondary">${surface}</span>` : '-'}
            </td>
            <td class="text-center align-middle fw-bold text-primary">${length ? length.toFixed(2) : '-'}</td>
            <td class="text-center align-middle fw-bold text-primary">${width ? width.toFixed(2) : '-'}</td>
            <td class="text-center align-middle fw-bold text-primary">${depth ? depth.toFixed(2) : '-'}</td>
            <td class="text-center align-middle">
                ${volume ? `<span class="badge bg-dark fs-6">${volume.toFixed(2)}</span>` : '-'}
            </td>
            <td class="text-center align-middle">
                <span class="fw-bold text-success fs-6">${quantity.toFixed(2)}</span>
            </td>
            <td class="text-center align-middle">
                <span class="badge bg-outline-primary">${unit}</span>
            </td>
        `;

        // إضافة تأثير الظهور التدريجي
        setTimeout(() => {
            row.classList.add('fade-in-row');
        }, 50);
    }

    // دالة لتحديث الإحصائيات التفصيلية
    function updateExcavationStats(soil, rock, asphalt, precise) {
        document.getElementById('total-soil-excavation').textContent = soil.toFixed(2);
        document.getElementById('total-rock-excavation').textContent = rock.toFixed(2);
        document.getElementById('total-asphalt-work').textContent = asphalt.toFixed(2);
        document.getElementById('total-precise-excavation').textContent = precise.toFixed(2);
    }

    // دالة لتحديث الإجماليات النهائية
    function updateFinalTotals(totalLength, totalVolume, totalArea) {
        document.getElementById('final-total-length').textContent = totalLength.toFixed(2) + ' متر';
        document.getElementById('final-total-volume').textContent = totalVolume.toFixed(2) + ' متر مكعب';
        document.getElementById('final-total-area').textContent = totalArea.toFixed(2) + ' متر مربع';
    }

    // دالة لإعداد فلاتر الجدول
    function setupExcavationFilters() {
        // أزرار التصفية
        document.querySelectorAll('[data-filter]').forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // تحديث حالة الأزرار
                document.querySelectorAll('[data-filter]').forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.add('btn-outline-primary', 'btn-outline-success', 'btn-outline-danger', 'btn-outline-warning', 'btn-outline-info');
                    btn.classList.remove('btn-primary', 'btn-success', 'btn-danger', 'btn-warning', 'btn-info');
                });
                
                this.classList.add('active');
                if (filter === 'all') this.classList.add('btn-primary');
                else if (filter === 'soil') this.classList.add('btn-success');
                else if (filter === 'rock') this.classList.add('btn-danger');
                else if (filter === 'asphalt') this.classList.add('btn-warning');
                else if (filter === 'precise') this.classList.add('btn-info');

                // تطبيق الفلتر
                filterExcavationTable(filter);
            });
        });

        // البحث في الجدول
        document.getElementById('excavation-search').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            searchExcavationTable(searchTerm);
        });
    }

    // دالة للتصفية
    function filterExcavationTable(filter) {
        const rows = document.querySelectorAll('#excavation-details-tbody tr');
        
        rows.forEach(row => {
            if (filter === 'all') {
                row.style.display = '';
            } else {
                const category = row.getAttribute('data-category');
                row.style.display = category === filter ? '' : 'none';
            }
        });
    }

    // دالة للبحث
    function searchExcavationTable(searchTerm) {
        const rows = document.querySelectorAll('#excavation-details-tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    }

    // دالة لتصدير البيانات
    function exportExcavationData() {
        const table = document.getElementById('excavation-details-table');
        if (!table) return;

        // إنشاء محتوى CSV
        let csvContent = '\uFEFF'; // BOM for UTF-8
        const headers = [];
        const headerCells = table.querySelectorAll('thead th');
        headerCells.forEach(cell => headers.push(cell.textContent.trim()));
        csvContent += headers.join(',') + '\n';

        // إضافة الصفوف المرئية فقط
        const visibleRows = Array.from(table.querySelectorAll('tbody tr')).filter(row => 
            row.style.display !== 'none' && row.cells.length > 1
        );

        visibleRows.forEach(row => {
            const rowData = [];
            for (let i = 0; i < row.cells.length; i++) {
                rowData.push('"' + row.cells[i].textContent.trim().replace(/"/g, '""') + '"');
            }
            csvContent += rowData.join(',') + '\n';
        });

        // تحميل الملف
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', 'بيانات_الحفريات_' + new Date().toISOString().split('T')[0] + '.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // دالة للانتقال إلى الجدول التفصيلي
    function scrollToExcavationDetails() {
        const detailsSection = document.querySelector('.card:has(#excavation-details-table)');
        if (detailsSection) {
            detailsSection.scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
            
            // تحديث الجدول عند الوصول إليه
            setTimeout(() => {
                updateExcavationDetailsTable();
            }, 500);
        }
    }

    // جعل الدالة متاحة على مستوى global
    window.scrollToExcavationDetails = scrollToExcavationDetails;

    // حساب الأحجام الأولية للحفر المفتوح (محدث)
    const excavationTargetsUpdated = ['unsurfaced_soil_open', 'surfaced_soil_open', 'unsurfaced_rock_open', 'surfaced_rock_open', 'surfaced_rock_open_2', 'surfaced_rock_second_open'];
    excavationTargetsUpdated.forEach(target => calculateExcavationVolume(target));
    </script>

    <!-- إضافة JavaScript للتعامل مع رفع الصور -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('civil_works_images');
            const imagePreview = document.getElementById('image-preview');
            const uploadProgress = document.getElementById('upload-progress');
            const progressBar = uploadProgress.querySelector('.progress-bar');
            const uploadStatus = document.getElementById('upload-status');
            
            if (imageInput) {
            const maxFiles = parseInt(imageInput.dataset.maxFiles);
            const maxSize = parseInt(imageInput.dataset.maxSize);
            let totalSize = 0;

            // التحقق من حجم الملفات عند الاختيار
            imageInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                totalSize = files.reduce((sum, file) => sum + file.size, 0);

                // التحقق من عدد الملفات
                if (files.length > maxFiles) {
                        showImageError(`يمكنك رفع ${maxFiles} صور كحد أقصى`);
                    imageInput.value = '';
                    return;
                }

                // التحقق من الحجم الإجمالي
                if (totalSize > maxSize) {
                        showImageError(`الحجم الإجمالي للصور يجب أن لا يتجاوز 30 ميجابايت`);
                    imageInput.value = '';
                    return;
                }

                // عرض معاينة الصور
                imagePreview.innerHTML = '';
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 col-sm-4 col-6';
                        col.innerHTML = `
                            <div class="card h-100">
                                    <img src="${e.target.result}" class="card-img-top" alt="معاينة الصورة ${index + 1}" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <p class="card-text small text-muted mb-0">${formatFileSize(file.size)}</p>
                                </div>
                            </div>
                        `;
                        imagePreview.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                });

                // إخفاء رسائل الخطأ السابقة
                uploadStatus.classList.add('d-none');
            });
            }

            // دالة لعرض رسائل خطأ الصور
            function showImageError(message) {
                uploadStatus.className = 'alert alert-danger mt-3';
                uploadStatus.textContent = message;
                uploadStatus.classList.remove('d-none');
            }
        });
    </script>

    <!-- إضافة JavaScript للتعامل مع رفع المرفقات -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // دالة مشتركة لتنسيق حجم الملف
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // دالة لتحديد أيقونة الملف
            function getFileIcon(filename) {
                const ext = filename.split('.').pop().toLowerCase();
                switch(ext) {
                    case 'pdf': return 'pdf';
                    case 'doc':
                    case 'docx': return 'word';
                    case 'xls':
                    case 'xlsx': return 'excel';
                    case 'ppt':
                    case 'pptx': return 'powerpoint';
                    case 'txt': return 'text';
                    case 'zip':
                    case 'rar': return 'archive';
                    default: return 'file';
                }
            }

            // التعامل مع المرفقات
            const attachmentInput = document.getElementById('civil_works_attachments');
            const attachmentPreview = document.getElementById('attachment-preview');
            const attachmentUploadProgress = document.getElementById('attachment-upload-progress');
            const attachmentUploadStatus = document.getElementById('attachment-upload-status');
            
            if (attachmentInput) {
                const maxFiles = parseInt(attachmentInput.dataset.maxFiles);
                const maxFileSize = parseInt(attachmentInput.dataset.maxFileSize);

                attachmentInput.addEventListener('change', function(e) {
                    const files = Array.from(e.target.files);
                    
                    // التحقق من عدد الملفات
                    if (files.length > maxFiles) {
                        showAttachmentError(`يمكنك رفع ${maxFiles} مرفق كحد أقصى`);
                        attachmentInput.value = '';
                        return;
                    }

                    // التحقق من حجم كل ملف
                    for (let file of files) {
                        if (file.size > maxFileSize) {
                            showAttachmentError(`حجم الملف "${file.name}" يتجاوز 20 ميجابايت`);
                            attachmentInput.value = '';
                            return;
                        }
                    }

                    // عرض معاينة المرفقات
                    attachmentPreview.innerHTML = '';
                    files.forEach((file, index) => {
                        const col = document.createElement('div');
                        col.className = 'col-md-4 col-sm-6';
                        col.innerHTML = `
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-${getFileIcon(file.name)} fa-2x text-warning me-3"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-1 text-truncate" title="${file.name}">
                                                ${file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name}
                                            </h6>
                                            <p class="card-text small text-muted mb-0">${formatFileSize(file.size)}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        attachmentPreview.appendChild(col);
                    });

                    // إخفاء رسائل الخطأ السابقة
                    attachmentUploadStatus.classList.add('d-none');
                });
            }

            // دالة لعرض رسائل خطأ المرفقات
            function showAttachmentError(message) {
                attachmentUploadStatus.className = 'alert alert-danger mt-3';
                attachmentUploadStatus.textContent = message;
                attachmentUploadStatus.classList.remove('d-none');
            }
        });
    </script>
</body>
</html> 