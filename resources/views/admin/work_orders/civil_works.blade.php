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
            z-index: 100;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* تثبيت الجدول التفصيلي */
        .excavation-details-container {
            position: sticky;
            top: 20px;
            z-index: 50;
            max-height: 80vh;
            overflow-y: auto;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            background: white;
        }

        .excavation-details-container::-webkit-scrollbar {
            width: 8px;
        }

        .excavation-details-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .excavation-details-container::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 4px;
        }

        .excavation-details-container::-webkit-scrollbar-thumb:hover {
            background: #764ba2;
        }

        /* جعل البيانات ثابتة ومرئية */
        .permanent-visible {
            display: block !important;
            opacity: 1 !important;
            transform: none !important;
        }

        /* تثبيت حالة البيانات */
        .data-locked {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
            color: white !important;
        }

        .data-locked i {
            animation: lock-pulse 2s infinite;
        }

        @keyframes lock-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* تثبيت الجدول في الصفحة */
        .fixed-table-container {
            position: fixed;
            top: 50px;
            right: 20px;
            width: 500px;
            max-height: 70vh;
            overflow-y: auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            z-index: 1000;
            transform: translateX(520px);
            transition: transform 0.3s ease;
        }

        .fixed-table-container.visible {
            transform: translateX(0);
        }

        .fixed-table-toggle {
            position: fixed;
            top: 120px;
            right: 20px;
            z-index: 1001;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
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
        #excavation-details-table {
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
        }
        
                 #excavation-details-table thead th {
             background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
             color: white;
             font-weight: 600;
             border: none;
             padding: 18px 12px;
             text-align: center;
             font-size: 0.9rem;
             letter-spacing: 0.5px;
         }
         
         #excavation-details-table thead th.bg-warning {
             background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%) !important;
             color: white !important;
             font-weight: 700;
             text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
         }
        
        #excavation-details-table tbody tr {
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            background: white;
        }
        
        #excavation-details-table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05) !important;
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-left-color: #007bff;
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
        
        #excavation-details-table td {
            padding: 15px 12px;
            vertical-align: middle;
            border-color: #e9ecef;
        }
        
        /* تحسين البادجات */
        .badge.bg-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%) !important;
        }
        
        .badge.bg-light {
            border: 1px solid #dee2e6;
        }
        
        /* تحسين نقاط الألوان */
        .rounded-circle {
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
                 /* تحسين الكود */
         code.bg-light {
             font-family: 'Courier New', monospace;
             font-size: 0.85rem;
             color: #495057;
             font-weight: 600;
         }
         
         /* تحسين بادجات حالة السطح */
         .badge.bg-danger {
             background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
             box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
         }
         
         .badge.bg-success {
             background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
             box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
         }
         
         .badge.bg-info {
             background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
             box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
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
                                                               value="{{ old('excavation_unsurfaced_soil.' . $loop->index, $workOrder->excavation_unsurfaced_soil[$loop->index] ?? '') }}"
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
                                                               value="{{ old('excavation_surfaced_soil.' . $loop->index, $workOrder->excavation_surfaced_soil[$loop->index] ?? '') }}"
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
                                                               value="{{ old('excavation_surfaced_rock_open.length', $workOrder->excavation_surfaced_rock_open['length'] ?? '') }}"
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
                                                               value="{{ old('excavation_surfaced_rock_open.width', $workOrder->excavation_surfaced_rock_open['width'] ?? '') }}"
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
                                                               value="{{ old('excavation_surfaced_rock_open.depth', $workOrder->excavation_surfaced_rock_open['depth'] ?? '') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control bg-light fw-bold text-primary" 
                                                               id="total_surfaced_rock_open" 
                                                               readonly 
                                                               value="{{ 
                                                                   ($workOrder->excavation_surfaced_rock_open['length'] ?? 0) * 
                                                                   ($workOrder->excavation_surfaced_rock_open['width'] ?? 0) * 
                                                                   ($workOrder->excavation_surfaced_rock_open['depth'] ?? 0) 
                                                               }}">
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

                

                <!-- زر تثبيت الجدول -->
                <button type="button" class="btn btn-primary fixed-table-toggle" id="toggle-fixed-table" title="تثبيت/إلغاء تثبيت الجدول">
                    <i class="fas fa-thumbtack"></i>
                </button>

                <!-- الجدول المثبت -->
                <div class="fixed-table-container" id="fixed-table-container">
                    <div class="card border-0 h-100">
                        <div class="card-header bg-gradient text-white p-2" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-lock me-1"></i>الجدول المثبت
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-light" id="close-fixed-table">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped" id="fixed-excavation-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>النوع</th>
                                            <th class="text-center">الكمية</th>
                                            <th class="text-center">الوحدة</th>
                                        </tr>
                                    </thead>
                                    <tbody id="fixed-table-tbody">
                                        <!-- سيتم ملء البيانات تلقائياً -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- جدول شامل لبيانات الحفريات -->
                <div class="col-12">
                    <div class="card shadow-lg mb-4 border-0 excavation-details-container permanent-visible">
                        <div class="card-header bg-gradient text-white data-locked" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                            <i class="fas fa-shovel me-2"></i>
                            <h5 class="mb-0 d-inline">جدول تفصيلي لجميع بيانات الحفريات المدخلة - مثبت دائماً</h5>
                            <div class="float-end">
                                <button type="button" class="btn btn-warning btn-sm me-1" id="lock-data-btn">
                                    <i class="fas fa-lock me-1"></i>تثبيت البيانات
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning border-0 shadow-sm">
                                <i class="fas fa-excavator me-2"></i>
                                هذا الجدول يعرض تفاصيل شاملة لجميع أنواع الحفريات والأعمال المدنية المدخلة في النموذج
                            </div>
                            
                            <!-- أزرار التصفية تم حذفها -->

                            <!-- الجدول التفصيلي -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle" id="excavation-details-table">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 8%" class="text-center">#</th>
                                            <th style="width: 40%">تفاصيل نوع الحفرية</th>
                                            <th style="width: 15%" class="text-center">الكمية</th>
                                            <th style="width: 12%" class="text-center">الوحدة</th>
                                            <th style="width: 15%" class="text-center">الحجم/المساحة</th>
                                            <th style="width: 10%" class="text-center bg-warning">حالة السطح</th>
                                        </tr>
                                    </thead>
                                    <tbody id="excavation-details-tbody">
                                        <!-- سيتم ملء البيانات تلقائياً بواسطة JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- أزرار إدارة الجدول التفصيلي -->
                            <div class="row mt-3 mb-4">
                                <div class="col-12">
                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <button type="button" class="btn btn-primary" onclick="scrollToExcavationDetails()">
                                            <i class="fas fa-eye me-2"></i>
                                            عرض الجدول التفصيلي
                                        </button>
                                    </div>
                                </div>
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
                                <div class="mt-3">
                                    <h6 class="mb-2">الصور المرفوعة</h6>
                                    <div class="row g-2">
                                        @foreach($workOrder->civilWorksFiles as $file)
                                            <div class="col-6">
                                                <div class="card">
                                                    <img src="{{ asset('storage/' . $file->file_path) }}" 
                                                         class="card-img-top" 
                                                         style="height: 100px; object-fit: cover;">
                                                    <div class="card-body p-2">
                                                        <small class="text-muted">{{ round($file->file_size / 1024 / 1024, 2) }} MB</small>
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
                                <div class="mt-3">
                                    <h6 class="mb-2">المرفقات المرفوعة</h6>
                                    @foreach($workOrder->civilWorksAttachments as $file)
                                        <div class="d-flex align-items-center border rounded p-2 mb-2">
                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                            <span class="flex-grow-1">{{ Str::limit($file->original_filename, 30) }}</span>
                                            <small class="text-muted">{{ round($file->file_size / 1024 / 1024, 2) }} MB</small>
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

                <!-- زر للانتقال إلى الجدول التفصيلي -->
                <div class="col-12 mb-4">
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-lg px-5 py-3 shadow-lg" id="scroll-to-details-btn">
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
            <!-- زر التثبيت النهائي -->
            <div class="text-center mt-5 mb-4">
                <button type="button" class="btn btn-success btn-lg px-5 py-3 shadow-lg" onclick="lockCivilWorksData()">
                    <i class="fas fa-lock me-3"></i>
                    تثبيت الأعمال المدنية
                    <i class="fas fa-check ms-3"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // كود JavaScript مبسط للجدول التفصيلي
    document.addEventListener('DOMContentLoaded', function() {
        console.log('تم تحميل الصفحة - بدء إعداد الأحداث');
        
        // دالة لحساب الإجمالي لكل صف (حجم) - محسنة مع فحص الأمان
        function calculateTotal(rowId) {
            try {
                const lengthInput = document.querySelector(`input[data-row="${rowId}"][name$="[length]"]`);
                const widthInput = document.querySelector(`input[data-row="${rowId}"][name$="[width]"]`);
                const depthInput = document.querySelector(`input[data-row="${rowId}"][name$="[depth]"]`);
                
                const length = lengthInput ? parseFloat(lengthInput.value) || 0 : 0;
                const width = widthInput ? parseFloat(widthInput.value) || 0 : 0;
                const depth = depthInput ? parseFloat(depthInput.value) || 0 : 0;
                
                const total = length * width * depth;
                const totalField = document.getElementById(`total-${rowId}`);
                if (totalField) {
                    totalField.value = total.toFixed(2);
                }
            } catch (error) {
                console.warn(`خطأ في حساب الإجمالي لـ ${rowId}:`, error);
            }
        }

        // دالة لحساب المساحة لكل صف (متر مربع) - محسنة مع فحص الأمان
        function calculateArea(rowId) {
            try {
                const lengthInput = document.querySelector(`input[data-row="${rowId}"][name$="[length]"]`);
                const widthInput = document.querySelector(`input[data-row="${rowId}"][name$="[width]"]`);
                
                const length = lengthInput ? parseFloat(lengthInput.value) || 0 : 0;
                const width = widthInput ? parseFloat(widthInput.value) || 0 : 0;
                
                const total = length * width;
                const totalField = document.getElementById(`total-${rowId}`);
                if (totalField) {
                    totalField.value = total.toFixed(2);
                }
            } catch (error) {
                console.warn(`خطأ في حساب المساحة لـ ${rowId}:`, error);
            }
        }

        // إضافة مستمع الحدث لجميع حقول الإدخال (الحجم) - مع فحص الأمان
        document.querySelectorAll('.calculate-total').forEach(input => {
            if (input && input.dataset && input.dataset.row) {
                input.addEventListener('input', function() {
                    calculateTotal(this.dataset.row);
                });
            }
        });

        // إضافة مستمع الحدث لجميع حقول الإدخال (المساحة) - مع فحص الأمان
        document.querySelectorAll('.calculate-area').forEach(input => {
            if (input && input.dataset && input.dataset.row) {
                input.addEventListener('input', function() {
                    calculateArea(this.dataset.row);
                });
            }
        });

        // حساب الإجماليات الأولية عند تحميل الصفحة - مع فحص وجود العناصر
        const volumeRows = ['medium', 'low', 'sand_under', 'sand_over', 'first_sibz', 'second_sibz', 'concrete'];
        volumeRows.forEach(row => {
            // فحص وجود العناصر قبل حساب الإجمالي
            const lengthInput = document.querySelector(`input[data-row="${row}"][name$="[length]"]`);
            if (lengthInput) {
                calculateTotal(row);
            }
        });

        const areaRows = ['first_asphalt', 'asphalt_scraping'];
        areaRows.forEach(row => {
            // فحص وجود العناصر قبل حساب المساحة
            const lengthInput = document.querySelector(`input[data-row="${row}"][name$="[length]"]`);
            if (lengthInput) {
                calculateArea(row);
            }
        });

        // دالة لحساب الحجم للحفر المفتوح - محسنة مع فحص الأمان
        function calculateExcavationVolume(targetId) {
            try {
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
                } else {
                    console.log(`بعض العناصر غير موجودة لـ ${targetId}`);
                }
            } catch (error) {
                console.warn(`خطأ في حساب حجم الحفر لـ ${targetId}:`, error);
            }
        }

        // إضافة مستمع الأحداث لحقول الحفر المفتوح - مع فحص الأمان
        document.querySelectorAll('.excavation-calc').forEach(input => {
            if (input && input.dataset && input.dataset.target) {
                input.addEventListener('input', function() {
                    const targetId = this.dataset.target;
                    if (targetId) {
                        calculateExcavationVolume(targetId);
                    }
                });
            }
        });

        // حساب الأحجام الأولية للحفر المفتوح - مع فحص وجود العناصر
        const excavationTargets = ['unsurfaced_soil_open', 'surfaced_soil_open', 'unsurfaced_rock_open', 'surfaced_rock_open'];
        excavationTargets.forEach(target => {
            // فحص وجود العناصر قبل حساب الحجم
            const lengthInput = document.querySelector(`input[data-target="${target}"][data-type="length"]`);
            if (lengthInput) {
                calculateExcavationVolume(target);
            } else {
                console.log(`عناصر ${target} غير موجودة، تم تجاهلها`);
            }
        });

        console.log('تم إعداد جميع الأحدث بنجاح');
        
        // تحديث الجدول التفصيلي عند تحميل الصفحة
        setTimeout(() => {
            // فحص البيانات المحفوظة أولاً
            const savedData = loadDataFromLocalStorage();
            if (savedData && savedData.length > 0) {
                // استعادة حالة التثبيت
                const mainTable = document.querySelector('.excavation-details-container');
                const lockBtn = document.getElementById('lock-data-btn');
                
                if (mainTable && lockBtn) {
                    mainTable.classList.add('data-locked');
                    lockBtn.innerHTML = '<i class="fas fa-lock me-1"></i>البيانات مثبتة';
                    lockBtn.className = 'btn btn-success btn-sm me-1';
                    
                    // منع تحديث البيانات
                    const updateBtn = document.getElementById('update-excavation-table-btn');
                    if (updateBtn) updateBtn.disabled = true;
                }
                console.log('تم استعادة حالة التثبيت من التخزين المحلي');
            }
            
            updateExcavationDetailsTable();
            loadFixedTableData();
        }, 500);

        // إدارة الجدول المثبت
        setupFixedTableControls();
        
        // إضافة مؤشر بصري للتثبيت في العنوان
        const mainCardHeader = document.querySelector('.excavation-details-container .card-header');
        if (mainCardHeader) {
            const lockIndicator = document.createElement('span');
            lockIndicator.id = 'lock-indicator';
            lockIndicator.innerHTML = ' <i class="fas fa-lock-open text-light ms-2" title="غير مثبت"></i>';
            mainCardHeader.querySelector('h5').appendChild(lockIndicator);
        }
    });

    // دوال الجدول التفصيلي - متاحة عالمياً
    window.scrollToExcavationDetails = function() {
        console.log('الانتقال إلى الجدول التفصيلي');
        const detailsSection = document.querySelector('#excavation-details-table');
        if (detailsSection) {
            detailsSection.scrollIntoView({ behavior: 'smooth' });
            updateExcavationDetailsTable();
        } else {
            console.warn('لم يتم العثور على الجدول التفصيلي');
        }
    };

    window.updateExcavationDetailsTable = function() {
        console.log('تحديث الجدول التفصيلي');
        const tbody = document.getElementById('excavation-details-tbody');
        const mainTable = document.querySelector('.excavation-details-container');
        
        if (!tbody) {
            console.warn('لم يتم العثور على tbody');
            return;
        }

        // فحص إذا كانت البيانات مثبتة
        if (mainTable && mainTable.classList.contains('data-locked')) {
            console.log('البيانات مثبتة - لا يمكن التحديث');
            const savedData = loadDataFromLocalStorage();
            if (savedData && savedData.length > 0) {
                console.log('استعادة البيانات المثبتة من التخزين المحلي');
                displaySavedData(savedData, tbody);
                return;
            }
        }

        // تنظيف الجدول
        tbody.innerHTML = '';

        // جمع البيانات من النموذج
        const excavationData = [];
        
        // 1. بيانات الحفريات الترابية غير المسفلتة
        const soilUnsurfacedInputs = document.querySelectorAll('input[name^="excavation_unsurfaced_soil"]');
        const cableTypes = [
            'كابل منخفض واحد', 
            'كابلين منخفضين', 
            '3 كابلات منخفضة', 
            '4 كابلات منخفضة',
            'كابل متوسط واحد', 
            'كابلين متوسطين', 
            '3 كابلات متوسطة', 
            '4 كابلات متوسطة'
        ];
        
        soilUnsurfacedInputs.forEach((input, index) => {
            const value = parseFloat(input.value);
            if (value > 0) {
                excavationData.push({
                    type: 'حفريات التربة الترابية',
                    surface: 'غير مسفلت',
                    description: cableTypes[index] || `كابل ${index + 1}`,
                    dimensions: '-',
                    value: value,
                    unit: 'متر طولي',
                    volume: '-',
                    badge: 'success'
                });
            }
        });

        // 2. بيانات الحفريات الترابية المسفلتة
        const soilSurfacedInputs = document.querySelectorAll('input[name^="excavation_surfaced_soil"]');
        soilSurfacedInputs.forEach((input, index) => {
            const value = parseFloat(input.value);
            if (value > 0) {
                excavationData.push({
                    type: 'حفريات التربة الترابية',
                    surface: 'مسفلت',
                    description: cableTypes[index] || `كابل ${index + 1}`,
                    dimensions: '-',
                    value: value,
                    unit: 'متر طولي',
                    volume: '-',
                    badge: 'primary'
                });
            }
        });

        // 3. بيانات الحفريات الصخرية غير المسفلتة
        const rockUnsurfacedInputs = document.querySelectorAll('input[name^="excavation_unsurfaced_rock"]');
        rockUnsurfacedInputs.forEach((input, index) => {
            const value = parseFloat(input.value);
            if (value > 0) {
                excavationData.push({
                    type: 'حفريات التربة الصخرية',
                    surface: 'غير مسفلت',
                    description: cableTypes[index] || `كابل ${index + 1}`,
                    dimensions: '-',
                    value: value,
                    unit: 'متر طولي',
                    volume: '-',
                    badge: 'warning'
                });
            }
        });

        // 4. بيانات الحفريات الصخرية المسفلتة
        const rockSurfacedInputs = document.querySelectorAll('input[name^="excavation_surfaced_rock"]');
        rockSurfacedInputs.forEach((input, index) => {
            const value = parseFloat(input.value);
            if (value > 0) {
                excavationData.push({
                    type: 'حفريات التربة الصخرية',
                    surface: 'مسفلت',
                    description: cableTypes[index] || `كابل ${index + 1}`,
                    dimensions: '-',
                    value: value,
                    unit: 'متر طولي',
                    volume: '-',
                    badge: 'danger'
                });
            }
        });

        // 5. بيانات الحفر المفتوح
        const openExcavationTypes = [
            { 
                id: 'unsurfaced_soil_open', 
                name: 'حفر مفتوح في التربة الترابية', 
                surface: 'غير مسفلت',
                badge: 'info'
            },
            { 
                id: 'surfaced_soil_open', 
                name: 'حفر مفتوح في التربة الترابية', 
                surface: 'مسفلت',
                badge: 'info'
            },
            { 
                id: 'unsurfaced_rock_open', 
                name: 'حفر مفتوح في التربة الصخرية', 
                surface: 'غير مسفلت',
                badge: 'dark'
            },
            { 
                id: 'surfaced_rock_open', 
                name: 'حفر مفتوح في التربة الصخرية', 
                surface: 'مسفلت',
                badge: 'dark'
            }
        ];

        openExcavationTypes.forEach(type => {
            const lengthInput = document.querySelector(`input[data-target="${type.id}"][data-type="length"]`);
            const widthInput = document.querySelector(`input[data-target="${type.id}"][data-type="width"]`);
            const depthInput = document.querySelector(`input[data-target="${type.id}"][data-type="depth"]`);
            
            if (lengthInput && widthInput && depthInput) {
                const length = parseFloat(lengthInput.value) || 0;
                const width = parseFloat(widthInput.value) || 0;
                const depth = parseFloat(depthInput.value) || 0;
                const volume = length * width * depth;
                
                if (volume > 0) {
                    excavationData.push({
                        type: type.name,
                        surface: type.surface,
                        description: `حفر مفتوح ${type.surface === 'مسفلت' ? 'في سطح مسفلت' : 'في سطح ترابي'}`,
                        dimensions: `${length} × ${width} × ${depth}`,
                        value: volume,
                        unit: 'متر مكعب',
                        volume: volume.toFixed(2),
                        badge: type.badge
                    });
                }
            }
        });

        // 6. عرض البيانات في الجدول
        if (excavationData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-info-circle me-2"></i>لا توجد بيانات حفريات مدخلة للعرض</td></tr>';
        } else {
            excavationData.forEach((item, index) => {
                const row = tbody.insertRow();
                row.innerHTML = `
                    <td class="text-center fw-bold">${index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-${item.badge} rounded-circle me-2" style="width: 10px; height: 10px;"></div>
                            <div>
                                <strong class="d-block">${item.type}</strong>
                                <small class="text-muted">${item.description}</small>
                                ${item.dimensions !== '-' ? `<br><span class="badge bg-light text-dark mt-1"><i class="fas fa-ruler-combined me-1"></i>${item.dimensions}</span>` : ''}
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <strong class="text-primary fs-5">${item.value.toFixed(2)}</strong>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-info text-white">${item.unit}</span>
                    </td>
                    <td class="text-center">
                        <span class="text-success fw-bold fs-6">${item.volume}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge ${item.surface === 'مسفلت' ? 'bg-danger' : 'bg-success'} text-white px-3 py-2">
                            <i class="fas ${item.surface === 'مسفلت' ? 'fa-road' : 'fa-mountain'} me-1"></i>
                            ${item.surface}
                        </span>
                    </td>
                `;
                
                // إضافة تأثير hover للصف
                row.classList.add('table-hover-row');
            });
        }

        console.log(`تم عرض ${excavationData.length} عنصر في الجدول التفصيلي`);
        
        // تحديث الإحصائيات
        updateExcavationStats(excavationData);
        
        // تحديث الجدول المثبت
        setTimeout(() => {
            loadFixedTableData();
        }, 100);
    };

    // عرض البيانات المحفوظة
    function displaySavedData(savedData, tbody) {
        tbody.innerHTML = '';
        
        savedData.forEach((item, index) => {
            const row = tbody.insertRow();
            row.innerHTML = `
                <td class="text-center fw-bold">${item.index}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="bg-success rounded-circle me-2" style="width: 10px; height: 10px;"></div>
                        <div>
                            <strong class="d-block text-success">${item.type} (محفوظ)</strong>
                            <small class="text-muted">بيانات مثبتة</small>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <strong class="text-primary fs-5">${item.quantity}</strong>
                </td>
                <td class="text-center">
                    <span class="badge bg-info text-white">${item.unit}</span>
                </td>
                <td class="text-center">
                    <span class="text-success fw-bold fs-6">${item.volume}</span>
                </td>
                <td class="text-center">
                    <span class="badge bg-success text-white px-3 py-2">
                        <i class="fas fa-lock me-1"></i>
                        ${item.surface}
                    </span>
                </td>
            `;
            
            // إضافة فئة للصف المحفوظ
            row.classList.add('table-success', 'table-hover-row');
        });
    }

    // دالة لتحديث إحصائيات الحفريات
    function updateExcavationStats(excavationData) {
        let totalSoilLength = 0;
        let totalRockLength = 0;
        let totalVolume = 0;
        let totalCount = excavationData.length;

        excavationData.forEach(item => {
            if (item.type.includes('التربة الترابية')) {
                totalSoilLength += item.value;
            } else if (item.type.includes('التربة الصخرية')) {
                totalRockLength += item.value;
            }
            
            if (item.unit === 'متر مكعب') {
                totalVolume += item.value;
            }
        });

        // تحديث الإحصائيات في الكروت
        const soilStat = document.getElementById('total-soil-excavation');
        const rockStat = document.getElementById('total-rock-excavation');
        const volumeStat = document.getElementById('final-total-volume');
        const lengthStat = document.getElementById('final-total-length');

        if (soilStat) soilStat.textContent = totalSoilLength.toFixed(2);
        if (rockStat) rockStat.textContent = totalRockLength.toFixed(2);
        if (volumeStat) volumeStat.textContent = totalVolume.toFixed(2) + ' متر مكعب';
        if (lengthStat) lengthStat.textContent = (totalSoilLength + totalRockLength).toFixed(2) + ' متر';
        
        // إضافة إشارة للبيانات المثبتة
        const mainTable = document.querySelector('.excavation-details-container');
        if (mainTable && mainTable.classList.contains('data-locked')) {
            if (soilStat) soilStat.innerHTML += ' <i class="fas fa-lock text-success ms-1" title="مثبت"></i>';
            if (rockStat) rockStat.innerHTML += ' <i class="fas fa-lock text-success ms-1" title="مثبت"></i>';
            if (volumeStat) volumeStat.innerHTML += ' <i class="fas fa-lock text-success ms-1" title="مثبت"></i>';
            if (lengthStat) lengthStat.innerHTML += ' <i class="fas fa-lock text-success ms-1" title="مثبت"></i>';
        }
    }

    window.exportExcavationData = function() {
        console.log('تصدير البيانات');
        alert('ميزة التصدير قيد التطوير');
    };

    // دالة جديدة لحفظ بيانات الجدول التفصيلي في قاعدة البيانات
    window.saveExcavationDetailsTable = function() {
        console.log('حفظ بيانات الجدول التفصيلي');
        
        // جمع البيانات من الجدول التفصيلي
        const tableRows = document.querySelectorAll('#excavation-details-tbody tr');
        const excavationDetails = [];
        
        tableRows.forEach((row, index) => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 4 && !row.textContent.includes('لا توجد بيانات')) {
                excavationDetails.push({
                    index: index + 1,
                    type: cells[1].textContent.trim(),
                    description: cells[2].textContent.trim(),
                    quantity: cells[3].textContent.trim()
                });
            }
        });

        if (excavationDetails.length === 0) {
            alert('لا توجد بيانات للحفظ');
            return;
        }

        // إرسال البيانات عبر AJAX
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('_method', 'PUT');
        formData.append('excavation_details_table', JSON.stringify(excavationDetails));

        // عرض رسالة التحميل
        const saveButton = document.querySelector('button[onclick="saveExcavationDetailsTable()"]');
        const originalText = saveButton ? saveButton.innerHTML : '';
        if (saveButton) {
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
            saveButton.disabled = true;
        }

        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم حفظ بيانات الجدول التفصيلي بنجاح');
                console.log('تم حفظ ' + excavationDetails.length + ' عنصر في قاعدة البيانات');
            } else {
                alert('حدث خطأ أثناء الحفظ: ' + (data.message || 'خطأ غير معروف'));
            }
        })
        .catch(error => {
            console.error('خطأ في حفظ البيانات:', error);
            alert('حدث خطأ أثناء حفظ البيانات');
        })
        .finally(() => {
            // استعادة النص الأصلي للزر
            if (saveButton) {
                saveButton.innerHTML = originalText;
                saveButton.disabled = false;
            }
        });
    };

    // دالة لتحميل بيانات الجدول التفصيلي المحفوظة
    window.loadSavedExcavationDetails = function() {
        console.log('تحميل البيانات المحفوظة للجدول التفصيلي');
        
        fetch('{{ route("admin.work-orders.civil-works.excavation-details", $workOrder) }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.excavationDetails) {
                const tbody = document.getElementById('excavation-details-tbody');
                if (!tbody) return;

                tbody.innerHTML = '';
                
                if (data.excavationDetails.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">لا توجد بيانات محفوظة</td></tr>';
                } else {
                    data.excavationDetails.forEach((item, index) => {
                        const row = tbody.insertRow();
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${item.type}</td>
                            <td>${item.description}</td>
                            <td>${item.quantity}</td>
                        `;
                    });
                    console.log(`تم تحميل ${data.excavationDetails.length} عنصر من البيانات المحفوظة`);
                }
            }
        })
        .catch(error => {
            console.error('خطأ في تحميل البيانات المحفوظة:', error);
        });
    };

    // دوال إدارة الجدول المثبت
    function setupFixedTableControls() {
        const toggleBtn = document.getElementById('toggle-fixed-table');
        const fixedContainer = document.getElementById('fixed-table-container');
        const closeBtn = document.getElementById('close-fixed-table');
        const lockDataBtn = document.getElementById('lock-data-btn');
        const toggleFixedViewBtn = document.getElementById('toggle-fixed-view-btn');

        // تبديل عرض الجدول المثبت
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                fixedContainer.classList.toggle('visible');
                loadFixedTableData();
                
                if (fixedContainer.classList.contains('visible')) {
                    toggleBtn.innerHTML = '<i class="fas fa-eye-slash"></i>';
                    toggleBtn.title = 'إخفاء الجدول المثبت';
                } else {
                    toggleBtn.innerHTML = '<i class="fas fa-thumbtack"></i>';
                    toggleBtn.title = 'عرض الجدول المثبت';
                }
            });
        }

        // إغلاق الجدول المثبت
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                fixedContainer.classList.remove('visible');
                toggleBtn.innerHTML = '<i class="fas fa-thumbtack"></i>';
                toggleBtn.title = 'عرض الجدول المثبت';
            });
        }

        // تثبيت البيانات
        if (lockDataBtn) {
            lockDataBtn.addEventListener('click', function() {
                lockExcavationData();
            });
        }

        // تبديل العرض المثبت
        if (toggleFixedViewBtn) {
            toggleFixedViewBtn.addEventListener('click', function() {
                const mainTable = document.querySelector('.excavation-details-container');
                if (mainTable) {
                    mainTable.classList.toggle('permanent-visible');
                    const isVisible = mainTable.classList.contains('permanent-visible');
                    
                    this.innerHTML = isVisible ? 
                        '<i class="fas fa-eye-slash me-1"></i>إخفاء العرض' : 
                        '<i class="fas fa-eye me-1"></i>عرض مثبت';
                }
            });
        }
    }

    // تحميل بيانات الجدول المثبت
    function loadFixedTableData() {
        const mainTableBody = document.getElementById('excavation-details-tbody');
        const fixedTableBody = document.getElementById('fixed-table-tbody');
        
        if (!mainTableBody || !fixedTableBody) return;

        // نسخ البيانات من الجدول الرئيسي للجدول المثبت
        const mainRows = mainTableBody.querySelectorAll('tr');
        fixedTableBody.innerHTML = '';

        if (mainRows.length === 0 || mainRows[0].textContent.includes('لا توجد بيانات')) {
            fixedTableBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">لا توجد بيانات</td></tr>';
            return;
        }

        mainRows.forEach((row, index) => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 4) {
                const newRow = fixedTableBody.insertRow();
                
                // رقم السطر
                newRow.insertCell(0).innerHTML = `<span class="badge bg-primary">${index + 1}</span>`;
                
                // نوع الحفرية (مبسط)
                const typeCell = newRow.insertCell(1);
                const typeText = cells[1].textContent.trim();
                const shortType = typeText.length > 30 ? typeText.substring(0, 30) + '...' : typeText;
                typeCell.innerHTML = `<small class="fw-bold text-primary">${shortType}</small>`;
                
                // الكمية
                const quantityCell = newRow.insertCell(2);
                quantityCell.innerHTML = `<span class="fw-bold text-success">${cells[2].textContent.trim()}</span>`;
                quantityCell.className = 'text-center';
                
                // الوحدة
                const unitCell = newRow.insertCell(3);
                unitCell.innerHTML = `<span class="badge bg-info">${cells[3].textContent.trim()}</span>`;
                unitCell.className = 'text-center';
                
                // إضافة hover effect
                newRow.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = 'rgba(102, 126, 234, 0.1)';
                });
                
                newRow.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            }
        });

        console.log(`تم تحميل ${mainRows.length} عنصر في الجدول المثبت`);
    }

    // تثبيت البيانات
    function lockExcavationData() {
        const mainTable = document.querySelector('.excavation-details-container');
        const lockBtn = document.getElementById('lock-data-btn');
        
        if (!mainTable || !lockBtn) return;

        const isLocked = mainTable.classList.contains('data-locked');
        
        if (isLocked) {
            // إلغاء التثبيت
            mainTable.classList.remove('data-locked');
            lockBtn.innerHTML = '<i class="fas fa-unlock me-1"></i>إلغاء التثبيت';
            lockBtn.className = 'btn btn-outline-warning btn-sm me-1';
            
            // تمكين تحديث البيانات
            const updateBtn = document.getElementById('update-excavation-table-btn');
            if (updateBtn) updateBtn.disabled = false;
            
            // تحديث مؤشر التثبيت
            const lockIndicator = document.getElementById('lock-indicator');
            if (lockIndicator) {
                lockIndicator.innerHTML = ' <i class="fas fa-lock-open text-light ms-2" title="غير مثبت"></i>';
            }
            
            // حذف البيانات من التخزين المحلي
            localStorage.removeItem('excavation_data_locked');
            
            alert('تم إلغاء تثبيت البيانات - يمكن تحديثها الآن');
        } else {
            // تثبيت البيانات
            mainTable.classList.add('data-locked');
            lockBtn.innerHTML = '<i class="fas fa-lock me-1"></i>البيانات مثبتة';
            lockBtn.className = 'btn btn-success btn-sm me-1';
            
            // منع تحديث البيانات
            const updateBtn = document.getElementById('update-excavation-table-btn');
            if (updateBtn) updateBtn.disabled = true;
            
            // تحديث مؤشر التثبيت
            const lockIndicator = document.getElementById('lock-indicator');
            if (lockIndicator) {
                lockIndicator.innerHTML = ' <i class="fas fa-lock text-warning ms-2" title="مثبت"></i>';
            }
            
            // حفظ البيانات في التخزين المحلي
            saveDataToLocalStorage();
            
            alert('تم تثبيت البيانات بنجاح - البيانات محمية من التغيير');
        }
    }

    // حفظ البيانات في التخزين المحلي
    function saveDataToLocalStorage() {
        const tableData = [];
        const rows = document.querySelectorAll('#excavation-details-tbody tr');
        
        rows.forEach((row, index) => {
            const cells = row.querySelectorAll('td');
            if (cells.length >= 4 && !row.textContent.includes('لا توجد بيانات')) {
                tableData.push({
                    index: index + 1,
                    type: cells[1].textContent.trim(),
                    quantity: cells[2].textContent.trim(),
                    unit: cells[3].textContent.trim(),
                    volume: cells[4] ? cells[4].textContent.trim() : '',
                    surface: cells[5] ? cells[5].textContent.trim() : ''
                });
            }
        });

        localStorage.setItem('excavation_data_locked', JSON.stringify({
            data: tableData,
            timestamp: new Date().toISOString(),
            workOrderId: '{{ $workOrder->id }}'
        }));

        console.log(`تم حفظ ${tableData.length} عنصر في التخزين المحلي`);
    }

    // استعادة البيانات من التخزين المحلي
    function loadDataFromLocalStorage() {
        const savedData = localStorage.getItem('excavation_data_locked');
        if (!savedData) return false;

        try {
            const parsedData = JSON.parse(savedData);
            if (parsedData.workOrderId === '{{ $workOrder->id }}') {
                return parsedData.data;
            }
        } catch (error) {
            console.error('خطأ في استعادة البيانات:', error);
        }
        
        return false;
    }

    // جعل الدوال متاحة عالمياً
    window.loadFixedTableData = loadFixedTableData;
    window.lockExcavationData = lockExcavationData;
    window.setupFixedTableControls = setupFixedTableControls;
    </script>

    <!-- دالة مشتركة لتنسيق حجم الملف -->
    <script>
        // دالة عامة لتنسيق حجم الملف
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        // جعل الدالة متاحة عالمياً
        window.formatFileSize = formatFileSize;
    </script>

    <!-- JavaScript للتعامل مع الصور تم حذفه -->

    <!-- إضافة JavaScript للتعامل مع رفع المرفقات -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

    <!-- JavaScript للصور والمرفقات -->
    <script>
        // دالة معاينة الصور
        document.addEventListener('DOMContentLoaded', function() {
            const imagesInput = document.getElementById('civil_works_images');
            const imagesPreview = document.getElementById('images-preview');
            
            if (imagesInput) {
                imagesInput.addEventListener('change', function(e) {
                    const files = Array.from(e.target.files);
                    imagesPreview.innerHTML = '';
                    
                    files.forEach((file, index) => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const col = document.createElement('div');
                                col.className = 'col-6';
                                col.innerHTML = `
                                    <div class="card">
                                        <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                                        </div>
                                    </div>
                                `;
                                imagesPreview.appendChild(col);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                });
            }

            // معاينة المرفقات
            const attachmentsInput = document.getElementById('civil_works_attachments');
            const attachmentsPreview = document.getElementById('attachments-preview');
            
            if (attachmentsInput) {
                attachmentsInput.addEventListener('change', function(e) {
                    const files = Array.from(e.target.files);
                    attachmentsPreview.innerHTML = '';
                    
                    files.forEach(file => {
                        const fileDiv = document.createElement('div');
                        fileDiv.className = 'd-flex align-items-center border rounded p-2 mb-2';
                        fileDiv.innerHTML = `
                            <i class="fas fa-file text-primary me-2"></i>
                            <span class="flex-grow-1">${file.name}</span>
                            <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                        `;
                        attachmentsPreview.appendChild(fileDiv);
                    });
                });
            }
        });

        // دالة حفظ الصور
        function saveImages() {
            const imagesInput = document.getElementById('civil_works_images');
            const files = imagesInput.files;
            
            if (files.length === 0) {
                alert('يرجى اختيار صور أولاً');
                return;
            }

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            for (let i = 0; i < files.length; i++) {
                formData.append('civil_works_images[]', files[i]);
            }

            fetch(`/admin/work-orders/{{ $workOrder->id }}/save-images`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم حفظ الصور بنجاح!');
                    location.reload();
                } else {
                    alert('خطأ: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء حفظ الصور');
            });
        }

        // دالة حفظ المرفقات
        function saveAttachments() {
            const attachmentsInput = document.getElementById('civil_works_attachments');
            const files = attachmentsInput.files;
            
            if (files.length === 0) {
                alert('يرجى اختيار مرفقات أولاً');
                return;
            }

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            for (let i = 0; i < files.length; i++) {
                formData.append('civil_works_attachments[]', files[i]);
            }

            fetch(`/admin/work-orders/{{ $workOrder->id }}/save-attachments`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم حفظ المرفقات بنجاح!');
                    location.reload();
                } else {
                    alert('خطأ: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء حفظ المرفقات');
            });
        }

        // دالة تثبيت الأعمال المدنية
        function lockCivilWorksData() {
            if (confirm('هل أنت متأكد من تثبيت بيانات الأعمال المدنية؟\nلن تتمكن من تعديلها بعد التثبيت.')) {
                
                // إرسال طلب POST لتثبيت البيانات
                const url = `/admin/work-orders/{{ $workOrder->id }}/lock-images`;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const formData = new FormData();
                formData.append('_token', csrfToken);
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم تثبيت الأعمال المدنية بنجاح!');
                        // تعطيل جميع inputs في الصفحة
                        document.querySelectorAll('input, textarea, select').forEach(element => {
                            element.disabled = true;
                        });
                        // تغيير النص وحالة الزر
                        const lockBtn = document.querySelector('[onclick="lockCivilWorksData()"]');
                        lockBtn.innerHTML = '<i class="fas fa-check me-3"></i>تم التثبيت بنجاح<i class="fas fa-lock ms-3"></i>';
                        lockBtn.className = 'btn btn-secondary btn-lg px-5 py-3 shadow-lg';
                        lockBtn.disabled = true;
                    } else {
                        alert('خطأ: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء التثبيت');
                });
            }
        }
    </script>
</body>
</html> 