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

        /* أنماط للرقم المضاف للكابلات */
        .badge.bg-info {
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            padding: 0.35rem 0.6rem !important;
            border-radius: 12px !important;
            margin-right: 8px !important;
            box-shadow: 0 2px 4px rgba(13, 202, 240, 0.25) !important;
            background: linear-gradient(45deg, #0dcaf0, #0ba5cc) !important;
            animation: pulse-cable-number 3s infinite alternate !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            letter-spacing: 0.5px !important;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important;
        }

        .badge.bg-info:hover {
            transform: scale(1.1) !important;
            box-shadow: 0 4px 8px rgba(13, 202, 240, 0.4) !important;
            transition: all 0.3s ease !important;
        }

        @keyframes pulse-cable-number {
            0% { 
                transform: scale(1); 
                opacity: 1; 
                box-shadow: 0 2px 4px rgba(13, 202, 240, 0.25);
            }
            100% { 
                transform: scale(1.05); 
                opacity: 0.9; 
                box-shadow: 0 4px 8px rgba(13, 202, 240, 0.4);
            }
        }

        /* تحسين عرض الرقم على الشاشات الصغيرة */
        @media (max-width: 768px) {
            .badge.bg-info {
                font-size: 0.65rem !important;
                padding: 0.25rem 0.4rem !important;
                margin-right: 5px !important;
            }
        }

        /* تنسيق حقول السعر */
        .price-input {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #28a745;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
            color: #28a745;
        }

        .price-input:focus {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-color: #20c997;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            transform: scale(1.02);
        }

        .price-input:hover {
            background: linear-gradient(135deg, #ffffff 0%, #f1f3f4 100%);
            border-color: #20c997;
            transform: translateY(-1px);
        }

        .price-input::placeholder {
            color: #6c757d;
            opacity: 0.7;
        }

        .price-input + .input-group-text {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-color: #28a745;
            color: white;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            border-radius: 0 8px 8px 0;
            transition: all 0.3s ease;
        }

        .price-input:focus + .input-group-text {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            transform: scale(1.02);
        }

        /* تأثير للصفوف التي تحتوي على حقول السعر */
        tr:has(.price-input) {
            background: linear-gradient(90deg, rgba(40, 167, 69, 0.02) 0%, rgba(32, 201, 151, 0.02) 100%);
            transition: all 0.3s ease;
        }

        tr:has(.price-input:focus) {
            background: linear-gradient(90deg, rgba(40, 167, 69, 0.05) 0%, rgba(32, 201, 151, 0.05) 100%);
            transform: scale(1.001);
        }

        /* تأثير بصري عند التركيز على حقول السعر */
        .input-group:has(.price-input:focus) {
            position: relative;
        }

        .input-group:has(.price-input:focus)::after {
            content: '💰';
            position: absolute;
            top: -10px;
            right: -10px;
            font-size: 16px;
            z-index: 10;
            animation: float 2s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }

        /* تنسيق عمود الإجمالي النهائي */
        .total-calc {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: 2px solid #28a745;
            color: white !important;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        .volume-total-calc, .area-total-calc {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: 2px solid #28a745;
            color: white !important;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        .total-calc + .input-group-text, 
        .volume-total-calc + .input-group-text, 
        .area-total-calc + .input-group-text {
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            border-color: #20c997;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
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
                                        @foreach([ ' كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط ', '2 كابل متوسط ', '3 كابل متوسط', '4 كابل متوسط'] as $cable)
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
                                <table class="table table-bordered table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 20%">العنصر</th>
                                            <th style="width: 12%">الطول (متر)</th>
                                            <th style="width: 12%">العرض (متر)</th>
                                            <th style="width: 10%">العمق (متر)</th>
                                            <th style="width: 12%">الإجمالي (م³)</th>
                                            <th style="width: 14%">السعر (ريال)</th>
                                            <th style="width: 20%">الإجمالي النهائي</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        
                                        <tr>
                                            <td class="align-middle">أسفلت طبقة أولى <span class="badge bg-info">12345678900</span></td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.01" class="form-control dimension-input calculate-area calc-area-length" 
                                                           name="open_excavation[first_asphalt][length]" 
                                                           data-row="first_asphalt"
                                                           data-table="first_asphalt"
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
                                                    <input type="number" step="0.01" class="form-control dimension-input calculate-area calc-area-length" 
                                                           name="open_excavation[asphalt_scraping][length]" 
                                                           data-row="asphalt_scraping"
                                                           data-table="asphalt_scraping"
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
                                        <tr>
                                            <td colspan="5"><hr class='my-2'></td>
                                    
                                    
                                        
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
                                <div class="uploaded-attachments mt-3">
                                    <h6 class="mb-2">المرفقات المرفوعة</h6>
                                    @foreach($workOrder->civilWorksAttachments as $file)
                                        <div class="d-flex align-items-center border rounded p-2 mb-2 attachment-item" data-attachment-id="{{ $file->id }}">
                                            <i class="fas fa-file-{{ getFileIcon($file->original_filename) }} text-primary me-2"></i>
                                            <div class="flex-grow-1">
                                                <div class="text-truncate" title="{{ $file->original_filename }}">
                                                    {{ $file->original_filename }}
                                                </div>
                                                <small class="text-muted">{{ formatFileSize($file->file_size) }}</small>
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
                                <button type="button" class="btn btn-warning btn-sm" id="saveAttachmentsBtn">
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
        

        

    });





        // تنظيف الجدول
        tbody.innerHTML = '';

        // جمع البيانات من النموذج
        const excavationData = [];
        
        // 1. بيانات الحفريات الترابية غير المسفلتة
        const soilUnsurfacedInputs = document.querySelectorAll('input[name^="excavation_unsurfaced_soil"]');
        const cableTypes = [
            'كابل منخفض واحد 12345678900', 
            'كابلين منخفضين 12345678900', 
            '3 كابلات منخفضة 12345678900', 
            '4 كابلات منخفضة 12345678900',
            'كابل متوسط واحد 12345678900', 
            'كابلين متوسطين 12345678900', 
            '3 كابلات متوسطة 12345678900', 
            '4 كابلات متوسطة 12345678900'
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

        // 6. بيانات الحفريات الدقيقة
        const preciseExcavationTypes = [
            { 
                name: 'excavation_precise[medium]', 
                label: 'حفر متوسط دقيق 12345678900', 
                description: 'حفر دقيق بأبعاد 20 × 80 سم 12345678900',
                dimensions: '20 × 80 سم',
                badge: 'info'
            },
            { 
                name: 'excavation_precise[low]', 
                label: 'حفر منخفض دقيق 12345678900', 
                description: 'حفر دقيق بأبعاد 20 × 56 سم 12345678900',
                dimensions: '20 × 56 سم',
                badge: 'info'
            }
        ];

        preciseExcavationTypes.forEach(type => {
            const input = document.querySelector(`input[name="${type.name}"]`);
            if (input) {
                const value = parseFloat(input.value) || 0;
                if (value > 0) {
                    excavationData.push({
                        type: 'الحفريات الدقيقة',
                        surface: 'دقيق',
                        description: type.description,
                        dimensions: type.dimensions,
                        value: value,
                        unit: 'متر طولي',
                        volume: value.toFixed(2),
                        badge: type.badge
                    });
                }
            }
        });

        // 7. بيانات أعمال الأسفلت
        const asphaltTypes = [
            { 
                name: 'open_excavation[first_asphalt][length]', 
                widthName: 'open_excavation[first_asphalt][width]',
                label: 'أسفلت طبقة أولى 12345678900', 
                description: 'عمل سطحي - طبقة أساسية 12345678900',
                badge: 'primary'
            },
            { 
                name: 'open_excavation[asphalt_scraping][length]', 
                widthName: 'open_excavation[asphalt_scraping][width]',
                label: 'كشط وإعادة السفلتة 12345678900', 
                description: 'إصلاح وتجديد السطح 12345678900',
                badge: 'warning'
            }
        ];

        asphaltTypes.forEach(type => {
            const lengthInput = document.querySelector(`input[name="${type.name}"]`);
            const widthInput = document.querySelector(`input[name="${type.widthName}"]`);
            
            if (lengthInput && widthInput) {
                const length = parseFloat(lengthInput.value) || 0;
                const width = parseFloat(widthInput.value) || 0;
                const area = length * width;
                
                if (area > 0) {
                    excavationData.push({
                        type: 'أعمال الأسفلت',
                        surface: 'سطحي',
                        description: type.description,
                        dimensions: `${length} × ${width} متر`,
                        value: area,
                        unit: 'متر مربع',
                        volume: area.toFixed(2),
                        badge: type.badge
                    });
                }
            }
        });

        // 8. بيانات الكابلات الكهربائية
        const electricalTypes = [
            { 
                name: 'electrical_items[cable_4x70_low][meters]', 
                label: 'كيبل 4x70 منخفض 12345678900', 
                description: 'جهد منخفض - 4 أسلاك 12345678900',
                badge: 'danger'
            },
            { 
                name: 'electrical_items[cable_4x185_low][meters]', 
                label: 'كيبل 4x185 منخفض 12345678900', 
                description: 'جهد منخفض - 4 أسلاك 12345678900',
                badge: 'danger'
            },
            { 
                name: 'electrical_items[cable_4x300_low][meters]', 
                label: 'كيبل 4x300 منخفض 12345678900', 
                description: 'جهد منخفض - 4 أسلاك 12345678900',
                badge: 'danger'
            },
            { 
                name: 'electrical_items[cable_3x500_med][meters]', 
                label: 'كيبل 3x500 متوسط 12345678900', 
                description: 'جهد متوسط - 3 أسلاك 12345678900',
                badge: 'primary'
            },
            { 
                name: 'electrical_items[cable_3x400_med][meters]', 
                label: 'كيبل 3x400 متوسط 12345678900', 
                description: 'جهد متوسط - 3 أسلاك 12345678900',
                badge: 'primary'
            }
        ];

        electricalTypes.forEach(type => {
            const input = document.querySelector(`input[name="${type.name}"]`);
            if (input) {
                const value = parseFloat(input.value) || 0;
                if (value > 0) {
                    excavationData.push({
                        type: 'الكابلات الكهربائية',
                        surface: 'كهربائي',
                        description: type.description,
                        dimensions: '-',
                        value: value,
                        unit: 'متر طولي',
                        volume: value.toFixed(2),
                        badge: type.badge
                    });
                }
            }
        });

        // 9. عرض البيانات في الجدول
        if (excavationData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-info-circle me-2"></i>لا توجد بيانات حفريات مدخلة للعرض</td></tr>';
        } else {
            excavationData.forEach((item, index) => {
                // تحديد لون وأيقونة القسم
                let sectionBadge = '';
                let sectionIcon = '';
                
                switch(item.type) {
                    case 'حفريات التربة الترابية':
                    case 'حفريات التربة الصخرية':
                        sectionBadge = 'bg-success';
                        sectionIcon = 'fa-mountain';
                        break;
                    case 'الحفريات الدقيقة':
                        sectionBadge = 'bg-info';
                        sectionIcon = 'fa-crosshairs';
                        break;
                    case 'أعمال الأسفلت':
                        sectionBadge = 'bg-warning text-dark';
                        sectionIcon = 'fa-road';
                        break;
                    case 'الكابلات الكهربائية':
                        sectionBadge = 'bg-danger';
                        sectionIcon = 'fa-bolt';
                        break;
                    default:
                        sectionBadge = 'bg-secondary';
                        sectionIcon = 'fa-tools';
                }

                const row = tbody.insertRow();
                row.innerHTML = `
                    <td class="text-center fw-bold">${index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-${item.badge} rounded-circle me-2" style="width: 12px; height: 12px;"></div>
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
                        <span class="badge bg-info text-white px-2 py-1">${item.unit}</span>
                    </td>
                    <td class="text-center">
                        <span class="text-success fw-bold fs-6">${item.volume}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge ${item.surface === 'مسفلت' ? 'bg-danger' : item.surface === 'سطحي' ? 'bg-warning text-dark' : item.surface === 'دقيق' ? 'bg-secondary' : item.surface === 'كهربائي' ? 'bg-primary' : 'bg-success'} text-white px-2 py-1">
                            <i class="fas ${item.surface === 'مسفلت' ? 'fa-road' : item.surface === 'سطحي' ? 'fa-layer-group' : item.surface === 'دقيق' ? 'fa-crosshairs' : item.surface === 'كهربائي' ? 'fa-bolt' : 'fa-mountain'} me-1"></i>
                            ${item.surface}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge ${sectionBadge} px-2 py-1">
                            <i class="fas ${sectionIcon} me-1"></i>
                            ${item.type.split(' ')[0]}
                        </span>
                    </td>
                `;
                
                // إضافة تأثير hover للصف
                row.classList.add('table-hover-row');
            });
        }

        console.log(`تم عرض ${excavationData.length} عنصر في الجدول التفصيلي`);
        
        // إفراغ حقول حفريات التربة الترابية المسفلتة بعد العرض
        clearSurfacedSoilExcavationFields();
        

    };

    // دالة لإفراغ حقول حفريات التربة الترابية المسفلتة
    function clearSurfacedSoilExcavationFields() {
        console.log('إفراغ حقول حفريات التربة الترابية المسفلتة');
        
        // إفراغ حقول الحفريات العادية المسفلتة
        const surfacedSoilInputs = document.querySelectorAll('input[name^="excavation_surfaced_soil"]');
        surfacedSoilInputs.forEach(input => {
            if (input.type === 'number' || input.type === 'text') {
                const oldValue = input.value;
                input.value = '';
                
                // إضافة تأثير بصري للإشارة إلى الإفراغ
                input.style.backgroundColor = '#fff3cd';
                input.style.border = '2px solid #ffc107';
                
                // إزالة التأثير بعد ثانيتين
                setTimeout(() => {
                    input.style.backgroundColor = '';
                    input.style.border = '';
                }, 2000);
                
                console.log(`تم إفراغ الحقل: ${input.name} (القيمة السابقة: ${oldValue})`);
            }
        });
        
        // إفراغ حقول الحفر المفتوح المسفلت
        const openSurfacedInputs = [
            'input[name="excavation_surfaced_soil_open[length]"]',
            'input[name="excavation_surfaced_soil_open[width]"]',
            'input[name="excavation_surfaced_soil_open[depth]"]'
        ];
        
        openSurfacedInputs.forEach(selector => {
            const input = document.querySelector(selector);
            if (input) {
                const oldValue = input.value;
                input.value = '';
                
                // إضافة تأثير بصري
                input.style.backgroundColor = '#fff3cd';
                input.style.border = '2px solid #ffc107';
                
                setTimeout(() => {
                    input.style.backgroundColor = '';
                    input.style.border = '';
                }, 2000);
                
                console.log(`تم إفراغ الحقل: ${selector} (القيمة السابقة: ${oldValue})`);
            }
        });
        
        // إفراغ حقل الإجمالي للحفر المفتوح المسفلت
        const totalSurfacedOpen = document.getElementById('total_excavation_surfaced_soil_open');
        if (totalSurfacedOpen) {
            totalSurfacedOpen.value = '';
            totalSurfacedOpen.style.backgroundColor = '#fff3cd';
            totalSurfacedOpen.style.border = '2px solid #ffc107';
            
            setTimeout(() => {
                totalSurfacedOpen.style.backgroundColor = '';
                totalSurfacedOpen.style.border = '';
            }, 2000);
        }
        
        // عرض رسالة تأكيد
        showNotification('تم إفراغ حقول حفريات التربة الترابية المسفلتة بنجاح', 'success');
    }

    // دالة لعرض الإشعارات
    function showNotification(message, type = 'info') {
        // إنشاء عنصر الإشعار
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // إضافة الإشعار للصفحة
        document.body.appendChild(notification);
        
        // إزالة الإشعار تلقائياً بعد 5 ثوان
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }











    window.exportExcavationData = function() {
        console.log('تصدير البيانات');
        alert('ميزة التصدير قيد التطوير');
    };
















    </script>

    <!-- كود JavaScript لحساب الضرب والإجماليات -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // دوال حساب الضرب للإجمالي النهائي
        function calculateLinearTotal(row, table) {
            const lengthInput = document.querySelector(`input[name="excavation_${table}[${row}]"]`);
            const priceInput = document.querySelector(`input[name="excavation_${table}_price[${row}]"]`);
            const totalInput = document.getElementById(`total_${table}_${row}`);
            
            if (lengthInput && priceInput && totalInput) {
                const length = parseFloat(lengthInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = length * price;
                totalInput.value = total.toFixed(2);
            }
        }

        function calculateVolumeTotal(table) {
            console.log(`🔧 calculateVolumeTotal called for table: ${table}`);
            
            const lengthInput = document.querySelector(`input[name="excavation_${table}_open[length]"]`);
            const widthInput = document.querySelector(`input[name="excavation_${table}_open[width]"]`);
            const depthInput = document.querySelector(`input[name="excavation_${table}_open[depth]"]`);
            const priceInput = document.querySelector(`input[name="excavation_${table}_open_price"]`);
            const volumeInput = document.getElementById(`total_${table}_open`);
            const totalInput = document.getElementById(`final_total_${table}_open`);
            
            console.log(`   Elements found: length=${!!lengthInput}, width=${!!widthInput}, depth=${!!depthInput}, volume=${!!volumeInput}, price=${!!priceInput}, total=${!!totalInput}`);
            
            if (lengthInput && widthInput && depthInput && volumeInput) {
                const length = parseFloat(lengthInput.value) || 0;
                const width = parseFloat(widthInput.value) || 0;
                const depth = parseFloat(depthInput.value) || 0;
                const volume = length * width * depth;
                
                console.log(`   Calculation: ${length} × ${width} × ${depth} = ${volume}`);
                
                volumeInput.value = volume.toFixed(2);
                
                // تغيير لون الخلفية لتأكيد التحديث
                volumeInput.style.backgroundColor = '#e8f5e8';
                setTimeout(() => {
                    volumeInput.style.backgroundColor = '';
                }, 1000);
                
                if (priceInput && totalInput) {
                    const price = parseFloat(priceInput.value) || 0;
                    const total = volume * price;
                    
                    console.log(`   Total calculation: ${volume} × ${price} = ${total}`);
                    
                    totalInput.value = total.toFixed(2);
                    
                    // تغيير لون الخلفية لتأكيد التحديث
                    totalInput.style.backgroundColor = '#e8f5e8';
                    setTimeout(() => {
                        totalInput.style.backgroundColor = '';
                    }, 1000);
                }
                
                console.log(`   ✅ ${table} calculation completed successfully`);
            } else {
                console.error(`   ❌ Missing required elements for ${table}`);
            }
        }

        function calculateAreaTotal(table) {
            const lengthInput = document.querySelector(`input[name="open_excavation[${table}][length]"]`);
            const widthInput = document.querySelector(`input[name="open_excavation[${table}][width]"]`);
            const priceInput = document.querySelector(`input[name="open_excavation[${table}][price]"]`);
            const areaInput = document.getElementById(`total-${table}`);
            const totalInput = document.getElementById(`final_total_${table}`);
            
            if (lengthInput && widthInput && areaInput) {
                const length = parseFloat(lengthInput.value) || 0;
                const width = parseFloat(widthInput.value) || 0;
                const area = length * width;
                areaInput.value = area.toFixed(2);
                
                if (priceInput && totalInput) {
                    const price = parseFloat(priceInput.value) || 0;
                    const total = area * price;
                    totalInput.value = total.toFixed(2);
                }
            }
        }

        function calculatePreciseTotal(type) {
            const lengthInput = document.querySelector(`input[name="excavation_precise[${type}]"]`);
            const priceInput = document.querySelector(`input[name="excavation_precise[${type}_price]"]`);
            const totalId = type === 'medium' ? 'final_total_precise_medium' : 'final_total_precise_low';
            const totalInput = document.getElementById(totalId);
            
            if (lengthInput && priceInput && totalInput) {
                const length = parseFloat(lengthInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = length * price;
                totalInput.value = total.toFixed(2);
            }
        }

        function calculateElectricalTotal(type) {
            const lengthInput = document.querySelector(`input[name="electrical_items[${type}][meters]"]`);
            const priceInput = document.querySelector(`input[name="electrical_items[${type}][price]"]`);
            const totalInput = document.getElementById(`final_total_${type}`);
            
            if (lengthInput && priceInput && totalInput) {
                const length = parseFloat(lengthInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = length * price;
                totalInput.value = total.toFixed(2);
            }
        }

        // إضافة مستمعات الأحداث للحسابات
        document.addEventListener('input', function(e) {
            // للحقول الخطية (الطول * السعر)
            if (e.target.classList.contains('calc-length') || e.target.classList.contains('calc-price')) {
                const row = e.target.dataset.row;
                const table = e.target.dataset.table;
                if (row !== undefined && table) {
                    calculateLinearTotal(row, table);
                }
            }
            
            // للحفر المفتوح (الحجم * السعر)
            if (e.target.classList.contains('calc-volume-length') || 
                e.target.classList.contains('calc-volume-width') || 
                e.target.classList.contains('calc-volume-depth') || 
                e.target.classList.contains('calc-volume-price')) {
                const table = e.target.dataset.table;
                if (table) {
                    calculateVolumeTotal(table);
                }
            }
            
            // للمساحات (الطول * العرض * السعر)
            if (e.target.classList.contains('calc-area-length') || e.target.classList.contains('calc-area-price')) {
                const table = e.target.dataset.table;
                if (table) {
                    calculateAreaTotal(table);
                }
            }

            // للحفريات الدقيقة
            if (e.target.classList.contains('calc-precise-length') || e.target.classList.contains('calc-precise-price')) {
                const type = e.target.dataset.type;
                if (type) {
                    calculatePreciseTotal(type);
                }
            }

            // للكابلات الكهربائية
            if (e.target.classList.contains('calc-electrical-length') || e.target.classList.contains('calc-electrical-price')) {
                const type = e.target.dataset.type;
                if (type) {
                    calculateElectricalTotal(type);
                }
            }
        });

        // حساب الإجماليات عند تحميل الصفحة
        setTimeout(function() {
            // التحقق من وجود العناصر أولاً
            console.log('🚀 Page loaded, verifying elements...');
            
            // انتظار إضافي للتأكد من تحميل DOM بالكامل
            setTimeout(function() {
                verifyElementsExistence();
                
                // حساب الإجماليات الخطية للجداول الأساسية
                for (let table of ['unsurfaced_soil', 'surfaced_soil', 'surfaced_rock', 'unsurfaced_rock']) {
                    for (let row = 0; row < 8; row++) {
                        calculateLinearTotal(row, table);
                    }
                }
                
                // حساب إجماليات الحفر المفتوح بالحجم (الجداول التي لها أبعاد 3D)
                // تأكد من تضمين جميع الجداول الأربعة
                const openExcavationTables = ['unsurfaced_soil_open', 'surfaced_soil_open', 'surfaced_rock_open', 'unsurfaced_rock_open'];
                openExcavationTables.forEach(table => {
                    console.log(`Calculating volume total for: ${table}`);
                    // فحص إضافي قبل الحساب
                    const volumeInput = document.getElementById(`total_${table}_open`);
                    const totalInput = document.getElementById(`final_total_${table}_open`);
                    if (volumeInput && totalInput) {
                        calculateVolumeTotal(table);
                    } else {
                        console.warn(`⚠️ Elements missing for ${table}: volume=${!!volumeInput}, total=${!!totalInput}`);
                    }
                });
            }, 200);
            
            
            // حساب إجماليات الحفر المفتوح بالمساحة (الجداول التي لها أبعاد 2D)
            calculateAreaTotal('first_asphalt');
            calculateAreaTotal('asphalt_scraping');
            
            // حساب إجماليات الحفريات الدقيقة
            calculatePreciseTotal('medium');
            calculatePreciseTotal('low');
            
            // حساب إجماليات الكابلات الكهربائية
            calculateElectricalTotal('cable_4x70_low');
            calculateElectricalTotal('cable_4x185_low');
            calculateElectricalTotal('cable_4x300_low');
            calculateElectricalTotal('cable_3x500_med');
            calculateElectricalTotal('cable_3x400_med');
        }, 500);
    });
    </script>

    <!-- دالة مشتركة لتنسيق حجم الملف -->
    <script>
        // التحقق من وجود الدوال العامة وإنشاؤها إذا لم تكن موجودة
        if (!window.formatFileSize) {
            window.formatFileSize = function(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            };
        }
        
        if (!window.getFileIcon) {
            window.getFileIcon = function(filename) {
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
            };
        }
        
        // دالة للتحقق من وجود العناصر المطلوبة
        window.verifyElementsExistence = function() {
            console.log('🔍 Verifying elements existence...');
            const tables = ['unsurfaced_soil_open', 'surfaced_soil_open', 'surfaced_rock_open', 'unsurfaced_rock_open'];
            let allElementsFound = true;
            
            tables.forEach(table => {
                console.log(`\n=== Checking ${table} ===`);
                
                const lengthInput = document.querySelector(`input[name="excavation_${table}_open[length]"]`);
                const widthInput = document.querySelector(`input[name="excavation_${table}_open[width]"]`);
                const depthInput = document.querySelector(`input[name="excavation_${table}_open[depth]"]`);
                const priceInput = document.querySelector(`input[name="excavation_${table}_open_price"]`);
                const volumeInput = document.getElementById(`total_${table}_open`);
                const totalInput = document.getElementById(`final_total_${table}_open`);
                
                console.log(`   Length input: ${lengthInput ? '✅' : '❌'}`);
                console.log(`   Width input: ${widthInput ? '✅' : '❌'}`);
                console.log(`   Depth input: ${depthInput ? '✅' : '❌'}`);
                console.log(`   Price input: ${priceInput ? '✅' : '❌'}`);
                console.log(`   Volume output: ${volumeInput ? '✅' : '❌'}`);
                console.log(`   Total output: ${totalInput ? '✅' : '❌'}`);
                
                if (!lengthInput || !widthInput || !depthInput || !priceInput || !volumeInput || !totalInput) {
                    allElementsFound = false;
                }
            });
            
            console.log(`\n${allElementsFound ? '🎉 All elements found!' : '⚠️ Some elements missing.'}`);
            return allElementsFound;
        };

        // دالة لإعادة تعيين ألوان الحقول
        window.resetFieldColors = function() {
            const tables = ['unsurfaced_soil_open', 'surfaced_soil_open', 'surfaced_rock_open', 'unsurfaced_rock_open'];
            tables.forEach(table => {
                const volumeInput = document.getElementById(`total_${table}_open`);
                const totalInput = document.getElementById(`final_total_${table}_open`);
                if (volumeInput) {
                    volumeInput.style.backgroundColor = '';
                    volumeInput.style.color = '';
                }
                if (totalInput) {
                    totalInput.style.backgroundColor = '';
                    totalInput.style.color = '';
                }
            });
        };

        // دالة اختبار للتحقق من حسابات الحفر المفتوح
        window.testOpenExcavationCalculation = function() {
            console.log('🧪 Testing open excavation calculations...');
            
            // إعادة تعيين الألوان أولاً
            resetFieldColors();
            
            const tables = ['unsurfaced_soil_open', 'surfaced_soil_open', 'surfaced_rock_open', 'unsurfaced_rock_open'];
            let allTestsPassed = true;
            
            tables.forEach(table => {
                console.log(`\n=== Testing ${table} ===`);
                
                // تعيين قيم اختبارية
                const lengthInput = document.querySelector(`input[name="excavation_${table}_open[length]"]`);
                const widthInput = document.querySelector(`input[name="excavation_${table}_open[width]"]`);
                const depthInput = document.querySelector(`input[name="excavation_${table}_open[depth]"]`);
                const priceInput = document.querySelector(`input[name="excavation_${table}_open_price"]`);
                
                if (lengthInput && widthInput && depthInput && priceInput) {
                    // حفظ القيم الأصلية
                    const originalValues = {
                        length: lengthInput.value,
                        width: widthInput.value,
                        depth: depthInput.value,
                        price: priceInput.value
                    };
                    
                    // تعيين قيم اختبارية
                    lengthInput.value = '10';
                    widthInput.value = '5';
                    depthInput.value = '2';
                    priceInput.value = '100';
                    
                    // تشغيل الحساب
                    calculateVolumeTotal(table);
                    
                    // التحقق من النتائج
                    const volumeInput = document.getElementById(`total_${table}_open`);
                    const totalInput = document.getElementById(`final_total_${table}_open`);
                    
                    const expectedVolume = '100.00';
                    const expectedTotal = '10000.00';
                    const actualVolume = volumeInput ? volumeInput.value : 'NOT FOUND';
                    const actualTotal = totalInput ? totalInput.value : 'NOT FOUND';
                    
                    const volumeTest = actualVolume === expectedVolume;
                    const totalTest = actualTotal === expectedTotal;
                    
                    console.log(`📊 ${table} Results:`);
                    console.log(`   Volume: ${actualVolume} ${volumeTest ? '✅' : '❌'} (expected: ${expectedVolume})`);
                    console.log(`   Total: ${actualTotal} ${totalTest ? '✅' : '❌'} (expected: ${expectedTotal})`);
                    
                    // إضافة تأثير بصري للاختبار
                    if (volumeInput) {
                        volumeInput.style.backgroundColor = volumeTest ? '#d4edda' : '#f8d7da';
                        volumeInput.style.color = volumeTest ? '#155724' : '#721c24';
                    }
                    if (totalInput) {
                        totalInput.style.backgroundColor = totalTest ? '#d4edda' : '#f8d7da';
                        totalInput.style.color = totalTest ? '#155724' : '#721c24';
                    }
                    
                    if (!volumeTest || !totalTest) {
                        allTestsPassed = false;
                    }
                    
                    // استعادة القيم الأصلية
                    lengthInput.value = originalValues.length;
                    widthInput.value = originalValues.width;
                    depthInput.value = originalValues.depth;
                    priceInput.value = originalValues.price;
                    
                    // إعادة حساب القيم الأصلية
                    calculateVolumeTotal(table);
                    
                    // إعادة تعيين الألوان بعد فترة قصيرة
                    setTimeout(() => {
                        if (volumeInput) {
                            volumeInput.style.backgroundColor = '';
                            volumeInput.style.color = '';
                        }
                        if (totalInput) {
                            totalInput.style.backgroundColor = '';
                            totalInput.style.color = '';
                        }
                    }, 3000);
                } else {
                    console.error(`❌ Missing input elements for ${table}`);
                    allTestsPassed = false;
                }
            });
            
            console.log(`\n${allTestsPassed ? '🎉 All tests passed!' : '⚠️ Some tests failed.'}`);
            
            // إشعار بصري
            if (allTestsPassed) {
                showNotification('✅ جميع اختبارات الحفر المفتوح نجحت!', 'success');
            } else {
                showNotification('⚠️ بعض اختبارات الحفر المفتوح فشلت. تحقق من وحدة التحكم.', 'warning');
            }
            
            return allTestsPassed;
        };

        // دالة سريعة لاختبار العناصر فقط
        window.quickElementCheck = function() {
            console.log('⚡ Quick element check...');
            const tables = ['unsurfaced_soil_open', 'surfaced_soil_open', 'surfaced_rock_open', 'unsurfaced_rock_open'];
            
            tables.forEach(table => {
                const lengthInput = document.querySelector(`input[name="excavation_${table}_open[length]"]`);
                const widthInput = document.querySelector(`input[name="excavation_${table}_open[width]"]`);
                const depthInput = document.querySelector(`input[name="excavation_${table}_open[depth]"]`);
                const priceInput = document.querySelector(`input[name="excavation_${table}_open_price"]`);
                const volumeInput = document.getElementById(`total_${table}_open`);
                const totalInput = document.getElementById(`final_total_${table}_open`);
                
                const allFound = lengthInput && widthInput && depthInput && priceInput && volumeInput && totalInput;
                console.log(`${table}: ${allFound ? '✅' : '❌'}`);
                
                if (!allFound) {
                    console.log(`   Missing: ${!lengthInput ? 'length ' : ''}${!widthInput ? 'width ' : ''}${!depthInput ? 'depth ' : ''}${!priceInput ? 'price ' : ''}${!volumeInput ? 'volume ' : ''}${!totalInput ? 'total' : ''}`);
                }
            });
        };
    </script>

    <!-- JavaScript للتعامل مع الصور تم حذفه -->

    <!-- إضافة JavaScript للتعامل مع رفع المرفقات -->
    <script>
        // معالج الأخطاء العام
        window.addEventListener('error', function(e) {
            console.error('🚨 JavaScript Error:', e.error);
            console.error('   File:', e.filename);
            console.error('   Line:', e.lineno);
            console.error('   Column:', e.colno);
            return false; // لا تمنع الأخطاء الأخرى
        });

        document.addEventListener('DOMContentLoaded', function() {
            // استخدام الدالة العامة المعرفة مسبقاً

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
                                        <i class="fas fa-file-${window.getFileIcon(file.name)} fa-2x text-warning me-3"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-1 text-truncate" title="${file.name}">
                                                ${file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name}
                                            </h6>
                                            <p class="card-text small text-muted mb-0">${window.formatFileSize ? window.formatFileSize(file.size) : ((file.size / 1024 / 1024).toFixed(2) + ' MB')}</p>
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

        // استخدام الدوال العامة المعرفة مسبقاً

        // دالة إنشاء حاوية المرفقات المرفوعة
        function createUploadedAttachmentsContainer() {
            const container = document.createElement('div');
            container.className = 'uploaded-attachments mt-3';
            container.innerHTML = '<h6 class="mb-2">المرفقات المرفوعة</h6>';
            const attachmentsContainer = document.getElementById('attachments-preview');
            attachmentsContainer.after(container);
            return container;
        }

        // دالة عرض رسائل الخطأ
        function showError(message) {
            const errorAlert = document.createElement('div');
            errorAlert.className = 'alert alert-danger alert-dismissible fade show mt-3';
            errorAlert.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            const attachmentsContainer = document.getElementById('attachments-preview');
            attachmentsContainer.before(errorAlert);

            // إخفاء رسالة الخطأ بعد 5 ثواني
            setTimeout(() => {
                errorAlert.remove();
            }, 5000);
        }

        // دالة حذف المرفق
        function deleteAttachment(attachmentId) {
            if (!confirm('هل أنت متأكد من حذف هذا المرفق؟')) {
                return;
            }

            fetch(`/admin/work-orders/attachments/${attachmentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // حذف العنصر من العرض
                    const attachmentElement = document.querySelector(`[data-attachment-id="${attachmentId}"]`);
                    if (attachmentElement) {
                        attachmentElement.remove();
                    }
                    showSuccess('تم حذف المرفق بنجاح');
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('حدث خطأ أثناء حذف المرفق');
            });
        }

        // دالة عرض رسائل النجاح
        function showSuccess(message) {
            const successAlert = document.createElement('div');
            successAlert.className = 'alert alert-success alert-dismissible fade show mt-3';
            successAlert.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            const attachmentsContainer = document.getElementById('attachments-preview');
            attachmentsContainer.before(successAlert);

            // إخفاء رسالة النجاح بعد 5 ثواني
            setTimeout(() => {
                successAlert.remove();
            }, 5000);
        }
    </script>

    <script>
        // تهيئة معالجات الأحداث عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            // معالج حدث زر حفظ المرفقات
            const saveAttachmentsBtn = document.getElementById('saveAttachmentsBtn');
            if (saveAttachmentsBtn) {
                saveAttachmentsBtn.addEventListener('click', function() {
                    const attachmentsInput = document.getElementById('civil_works_attachments');
                    if (!attachmentsInput) {
                        console.error('لم يتم العثور على عنصر إدخال المرفقات');
                        return;
                    }

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

                    // إظهار مؤشر التحميل
                    const originalButtonText = saveAttachmentsBtn.innerHTML;
                    saveAttachmentsBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
                    saveAttachmentsBtn.disabled = true;

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
                            // تحديث عرض المرفقات
                            const uploadedAttachmentsContainer = document.querySelector('.uploaded-attachments') || createUploadedAttachmentsContainer();
                            
                            if (data.attachments && data.attachments.length > 0) {
                                data.attachments.forEach(attachment => {
                                    const fileDiv = document.createElement('div');
                                    fileDiv.className = 'd-flex align-items-center border rounded p-2 mb-2 attachment-item';
                                    fileDiv.setAttribute('data-attachment-id', attachment.id);
                                    fileDiv.innerHTML = `
                                        <i class="fas fa-file-${window.getFileIcon ? window.getFileIcon(attachment.original_filename) : 'file'} text-primary me-2"></i>
                                        <div class="flex-grow-1">
                                            <div class="text-truncate" title="${attachment.original_filename}">
                                                ${attachment.original_filename}
                                            </div>
                                            <small class="text-muted">${window.formatFileSize ? window.formatFileSize(attachment.file_size) : (attachment.file_size + ' bytes')}</small>
                                        </div>
                                        <div class="btn-group btn-group-sm ms-2">
                                            <a href="/storage/${attachment.file_path}" class="btn btn-outline-primary" target="_blank" title="عرض الملف">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" onclick="deleteAttachment(${attachment.id})" title="حذف الملف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    `;
                                    uploadedAttachmentsContainer.appendChild(fileDiv);
                                });
                            }

                            // مسح حقل الإدخال والمعاينة
                            attachmentsInput.value = '';
                            const attachmentsPreview = document.getElementById('attachments-preview');
                            if (attachmentsPreview) {
                                attachmentsPreview.innerHTML = '';
                            }

                            // إظهار رسالة نجاح
                            alert('تم حفظ المرفقات بنجاح');
                        } else {
                            alert(data.message || 'حدث خطأ أثناء حفظ المرفقات');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ أثناء حفظ المرفقات');
                    })
                    .finally(() => {
                        saveAttachmentsBtn.innerHTML = originalButtonText;
                        saveAttachmentsBtn.disabled = false;
                    });
                });
            }
        });
    </script>
</body>
</html> 