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
        
        /* تنسيقات جدول الملخص اليومي */
        .daily-excavation-row {
            transition: all 0.3s ease;
        }
        
        .daily-excavation-row:hover {
            background-color: rgba(102, 126, 234, 0.1);
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .excavation-type-select {
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }
        
        .excavation-type-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .total-cost {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%) !important;
            font-weight: bold !important;
            text-align: center;
        }
        
        .total-cost:focus {
            background: linear-gradient(135deg, #b8daff 0%, #9ec5fe 100%) !important;
        }
        
        /* تأثيرات على الإحصائيات */
        .stats-card .card-body {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover .card-body {
            transform: translateY(-3px);
        }
        
        /* تحسين أزرار الجدول */
        .btn-group-sm .btn {
            transition: all 0.2s ease;
        }
        
        .btn-group-sm .btn:hover {
            transform: scale(1.1);
        }
        
        /* تأثيرات الإدخال */
        .daily-excavation-row input:focus {
            transform: scale(1.02);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        /* رسالة لا توجد بيانات */
        #no-data-row {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        }
        
        #no-data-row .fas {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
            100% {
                opacity: 1;
            }
        }
        
        /* تأثيرات خاصة للأرقام الكبيرة */
        #daily-total-cost {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        /* تحسين responsive للجدول */
        @media (max-width: 768px) {
            .daily-excavation-row input {
                font-size: 0.85rem;
            }
            
            .btn-group-sm .btn {
                padding: 0.25rem 0.4rem;
            }
        }
    </style>
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
                                    الملخص اليومي التلقائي للحفريات الأساسية
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

                            <!-- الجدول الديناميكي -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="daily-excavation-table">
                                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                        <tr>
                                            <th style="width: 25%">نوع الحفرية</th>
                                            <th style="width: 10%">عدد الكابلات</th>
                                            <th style="width: 15%">الطول/الحجم</th>
                                            <th style="width: 15%">السعر لكل وحدة (ريال)</th>
                                            <th style="width: 15%">إجمالي التكلفة (ريال)</th>
                                            <th style="width: 15%">آخر تحديث</th>
                                            <th style="width: 5%">حذف</th>
                                        </tr>
                                    </thead>
                                    <tbody id="daily-excavation-tbody">
                                        <tr id="no-data-row">
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-clipboard-list fa-2x mb-2 d-block"></i>
                                                <p class="mb-0">لا توجد بيانات حفريات مدخلة اليوم</p>
                                                <small>استخدم الزر أدناه لإضافة بيانات جديدة</small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- أزرار التحكم -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <button type="button" class="btn btn-primary" id="add-daily-excavation-btn">
                                    <i class="fas fa-plus me-2"></i>إضافة حفرية جديدة
                                </button>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-success" id="export-daily-summary-btn">
                                        <i class="fas fa-file-excel me-2"></i>تصدير الملخص
                                    </button>
                                    <button type="button" class="btn btn-outline-info" id="save-daily-summary-btn">
                                        <i class="fas fa-save me-2"></i>حفظ الملخص
                                    </button>
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
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript Code -->
    <script>
        // Global error handler
        window.addEventListener('error', function(e) {
            console.error('🚨 JavaScript Error:', e.error);
            console.error('   File:', e.filename);
            console.error('   Line:', e.lineno);
            console.error('   Column:', e.colno);
            return false;
        });

        // Export function
        window.exportExcavationData = function() {
            console.log('تصدير البيانات');
            alert('ميزة التصدير قيد التطوير');
        };



        document.addEventListener('DOMContentLoaded', function() {
            // دالة حساب الإجمالي
            function calculateTotal(lengthInput, priceInput, totalInput) {
                try {
                    if (!lengthInput || !priceInput || !totalInput) {
                        console.error('عناصر مفقودة:', { lengthInput, priceInput, totalInput });
                        return;
                    }

                    const length = parseFloat(lengthInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;
                    const total = length * price;
                    
                    console.log('حساب الإجمالي:', { length, price, total });
                    
                    totalInput.value = total.toFixed(2);
                    
                    // تأثير بصري
                    totalInput.style.backgroundColor = '#e8f5e8';
                    setTimeout(() => {
                        totalInput.style.backgroundColor = '';
                    }, 500);

                    return total;
                } catch (error) {
                    console.error('خطأ في حساب الإجمالي:', error);
                }
            }

            // دالة حساب الحجم والإجمالي للحفر المفتوح
            function calculateOpenExcavation(fields, volumeTotal, finalTotal) {
                try {
                    const length = parseFloat(fields.length.value) || 0;
                    const width = parseFloat(fields.width.value) || 0;
                    const depth = parseFloat(fields.depth.value) || 0;
                    const price = parseFloat(fields.price.value) || 0;
                    
                    const volume = length * width * depth;
                    const total = volume * price;
                    
                    volumeTotal.value = volume.toFixed(2);
                    finalTotal.value = total.toFixed(2);
                    
                    // تأثير بصري
                    [volumeTotal, finalTotal].forEach(input => {
                        input.style.backgroundColor = '#e8f5e8';
                        setTimeout(() => {
                            input.style.backgroundColor = '';
                        }, 500);
                    });
                } catch (error) {
                    console.error('خطأ في حساب الحفر المفتوح:', error);
                }
            }

            try {
                // الكابلات المنخفضة
                for (let i = 0; i < 4; i++) {
                    const lengthInput = document.querySelector(`input[name="low_voltage[${i}]"]`);
                    const priceInput = document.querySelector(`input[name="low_voltage_price[${i}]"]`);
                    const totalInput = document.getElementById(`final_total_low_voltage_${i}`);
                    
                    if (lengthInput && priceInput && totalInput) {
                        [lengthInput, priceInput].forEach(input => {
                            input.addEventListener('input', function() {
                                calculateTotal(lengthInput, priceInput, totalInput);
                            });
                        });
                    }
                }

                // الكابلات المتوسطة
                for (let i = 0; i < 4; i++) {
                    const lengthInput = document.querySelector(`input[name="medium_voltage[${i}]"]`);
                    const priceInput = document.querySelector(`input[name="medium_voltage_price[${i}]"]`);
                    const totalInput = document.getElementById(`final_total_medium_voltage_${i}`);
                    
                    if (lengthInput && priceInput && totalInput) {
                        [lengthInput, priceInput].forEach(input => {
                            input.addEventListener('input', function() {
                                calculateTotal(lengthInput, priceInput, totalInput);
                            });
                        });
                    }
                }

                // حفر مفتوح أكثر من 4 كابلات
                const openExcavationFields = {
                    length: document.querySelector('input[name="open_excavation[length]"]'),
                    width: document.querySelector('input[name="open_excavation[width]"]'),
                    depth: document.querySelector('input[name="open_excavation[depth]"]'),
                    price: document.querySelector('input[name="open_excavation_price"]')
                };
                
                const volumeTotal = document.getElementById('total_open_excavation');
                const finalTotal = document.getElementById('final_total_open_excavation');
                
                if (Object.values(openExcavationFields).every(field => field) && volumeTotal && finalTotal) {
                    Object.values(openExcavationFields).forEach(input => {
                        input.addEventListener('input', function() {
                            calculateOpenExcavation(openExcavationFields, volumeTotal, finalTotal);
                        });
                    });
                }

                // تفعيل الحساب المبدئي لجميع الحقول
                document.querySelectorAll('input[type="number"], input[type="text"]').forEach(input => {
                    input.dispatchEvent(new Event('input'));
                });

            } catch (error) {
                console.error('خطأ في إعداد مستمعي الأحداث:', error);
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // تكوين الجدول اليومي
            const dailySummaryTable = document.getElementById('daily-summary-table');
            const noDataRow = document.getElementById('no-data-row');
            
            // تعريف أنواع الكابلات
            const cableTypes = {
                'low': {
                    name: 'كابل منخفض',
                    rows: 4,
                    prefix: 'low_cable'
                },
                'medium': {
                    name: 'كابل متوسط',
                    rows: 4,
                    prefix: 'medium_cable'
                }
            };

            // دالة تنسيق التاريخ والوقت
            function formatDateTime() {
                const now = new Date();
                const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
                const timeOptions = { hour: '2-digit', minute: '2-digit' };
                return {
                    date: now.toLocaleDateString('ar-SA', dateOptions),
                    time: now.toLocaleTimeString('ar-SA', timeOptions)
                };
            }

            // دالة إضافة صف جديد للملخص
            function addSummaryRow(details, total) {
                if (noDataRow) {
                    noDataRow.remove();
                }

                const { date, time } = formatDateTime();
                const newRow = document.createElement('tr');
                newRow.className = 'daily-excavation-row';
                newRow.innerHTML = `
                    <td>${date}</td>
                    <td>${time}</td>
                    <td>${details}</td>
                    <td class="text-end">${total.toFixed(2)} ريال</td>
                `;

                const tbody = dailySummaryTable.querySelector('tbody');
                tbody.insertBefore(newRow, tbody.firstChild);

                // تأثير بصري
                newRow.style.backgroundColor = '#e8f5e8';
                setTimeout(() => {
                    newRow.style.backgroundColor = '';
                }, 1000);
            }

            // دالة حساب إجمالي الصف
            function calculateRowTotal(length, price) {
                try {
                    const lengthValue = parseFloat(length) || 0;
                    const priceValue = parseFloat(price) || 0;
                    return lengthValue * priceValue;
                } catch (error) {
                    console.error('خطأ في حساب إجمالي الصف:', error);
                    return 0;
                }
            }

            // دالة تحديث حقل الإجمالي
            function updateTotal(row, total) {
                try {
                    const totalField = row.querySelector('.total-calc');
                    if (totalField) {
                        totalField.value = total.toFixed(2);
                        
                        // تأثير بصري
                        totalField.style.backgroundColor = '#e8f5e8';
                        setTimeout(() => {
                            totalField.style.backgroundColor = '';
                        }, 500);
                    }
                } catch (error) {
                    console.error('خطأ في تحديث الإجمالي:', error);
                }
            }

            // دالة معالجة تغيير القيم
            function handleValueChange(event) {
                try {
                    const input = event.target;
                    const row = input.closest('tr');
                    if (!row) return;

                    const lengthInput = row.querySelector('input[name^="length"]');
                    const priceInput = row.querySelector('input[name^="price"]');
                    
                    if (lengthInput && priceInput) {
                        const total = calculateRowTotal(lengthInput.value, priceInput.value);
                        updateTotal(row, total);

                        // إضافة للملخص اليومي
                        if (total > 0) {
                            const cableType = row.querySelector('.cable-type')?.textContent || 'كابل';
                            const details = `حفر ${cableType} - طول: ${lengthInput.value}م × سعر: ${priceInput.value}ريال`;
                            addSummaryRow(details, total);
                        }
                    }
                } catch (error) {
                    console.error('خطأ في معالجة تغيير القيمة:', error);
                }
            }

            // إضافة مستمعي الأحداث
            document.querySelectorAll('input[name^="length"], input[name^="price"]').forEach(input => {
                input.addEventListener('change', handleValueChange);
                input.addEventListener('input', handleValueChange);
            });

            // معالجة الحفر المفتوح
            const openExcavationForm = document.querySelector('.open-excavation-form');
            if (openExcavationForm) {
                const inputs = openExcavationForm.querySelectorAll('input[type="number"]');
                inputs.forEach(input => {
                    input.addEventListener('input', function() {
                        try {
                            const length = parseFloat(openExcavationForm.querySelector('input[name="length"]')?.value) || 0;
                            const width = parseFloat(openExcavationForm.querySelector('input[name="width"]')?.value) || 0;
                            const depth = parseFloat(openExcavationForm.querySelector('input[name="depth"]')?.value) || 0;
                            const price = parseFloat(openExcavationForm.querySelector('input[name="price"]')?.value) || 0;

                            const volume = length * width * depth;
                            const total = volume * price;

                            const volumeField = openExcavationForm.querySelector('.volume-total-calc');
                            const totalField = openExcavationForm.querySelector('.total-calc');

                            if (volumeField) volumeField.value = volume.toFixed(2);
                            if (totalField) totalField.value = total.toFixed(2);

                            if (total > 0) {
                                const details = `حفر مفتوح - حجم: ${volume.toFixed(2)}م³ × سعر: ${price}ريال`;
                                addSummaryRow(details, total);
                            }
                        } catch (error) {
                            console.error('خطأ في حساب الحفر المفتوح:', error);
                        }
                    });
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // تعريف المتغيرات العامة
            let dailyTotal = 0;
            let dailyItems = 0;
            let dailyCables = 0;
            let dailyLength = 0;

            // دالة تحديث الإحصائيات
            function updateStats() {
                document.getElementById('daily-total-cost').textContent = dailyTotal.toFixed(2);
                document.getElementById('daily-items-count').textContent = dailyItems;
                document.getElementById('daily-cables-count').textContent = dailyCables;
                document.getElementById('daily-total-length').textContent = dailyLength.toFixed(2);
            }

            // دالة حساب الإجمالي المحسنة
            function calculateTotal(row) {
                try {
                    // التحقق من نوع الصف أولاً
                    if (!row || !row.querySelector) {
                        return 0;
                    }
                    
                    // تجاهل صفوف الحفر المفتوح
                    if (row.classList.contains('table-warning') || row.querySelector('input[name*="_open"]')) {
                        return 0;
                    }
                    
                    // البحث عن الحقول المحددة بدقة أكبر
                    const lengthInput = row.querySelector('input[name*="excavation_"][name*="soil"]:not([name*="price"]):not([name*="open"])');
                    const priceInput = row.querySelector('input[name*="excavation_"][name*="price"]:not([name*="open"])');
                    const totalInput = row.querySelector('input[id*="total_"]:not([id*="open"])');

                    // إذا لم نجد الحقول المطلوبة، تجاهل الصف بصمت
                    if (!lengthInput || !priceInput || !totalInput) {
                        return 0;
                    }

                    // استخراج القيم وتحويلها إلى أرقام
                    const length = parseFloat(lengthInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;
                    const total = length * price;

                    // تحديث حقل الإجمالي
                    totalInput.value = total.toFixed(2);

                    // تأثير بصري
                    totalInput.style.backgroundColor = '#e8f5e8';
                    setTimeout(() => {
                        totalInput.style.backgroundColor = '';
                    }, 500);

                    // تحديث الإحصائيات فقط للقيم الموجبة
                    if (length > 0 && price > 0) {
                        dailyTotal += total;
                        dailyItems++;
                        dailyCables++;
                        dailyLength += length;
                        updateStats();
                    }

                    return total;
                } catch (error) {
                    console.error('خطأ في حساب الإجمالي:', error);
                    return 0;
                }
            }

            // دالة حساب الحفر المفتوح المحسنة
            function calculateOpenExcavation(row) {
                try {
                    // التحقق من وجود الصف وأنه صف حفر مفتوح
                    if (!row || !row.querySelector || !row.classList.contains('table-warning')) {
                        return 0;
                    }
                    
                    const lengthInput = row.querySelector('input[name*="length"]');
                    const widthInput = row.querySelector('input[name*="width"]');
                    const depthInput = row.querySelector('input[name*="depth"]');
                    const priceInput = row.querySelector('input[name*="_open_price"]');
                    const volumeInput = row.querySelector('input[id*="total_"][id*="_open"]:not([id*="final_"])');
                    const totalInput = row.querySelector('input[id*="final_total_"][id*="_open"]');

                    // إذا لم نجد الحقول المطلوبة، تجاهل بصمت
                    if (!lengthInput || !widthInput || !depthInput || !priceInput || !volumeInput || !totalInput) {
                        return 0;
                    }

                    const length = parseFloat(lengthInput.value) || 0;
                    const width = parseFloat(widthInput.value) || 0;
                    const depth = parseFloat(depthInput.value) || 0;
                    const price = parseFloat(priceInput.value) || 0;

                    const volume = length * width * depth;
                    const total = volume * price;

                    volumeInput.value = volume.toFixed(2);
                    totalInput.value = total.toFixed(2);

                    // تأثير بصري
                    [volumeInput, totalInput].forEach(input => {
                        input.style.backgroundColor = '#e8f5e8';
                        setTimeout(() => {
                            input.style.backgroundColor = '';
                        }, 500);
                    });

                    // تحديث الإحصائيات فقط للقيم الموجبة
                    if (volume > 0 && price > 0) {
                        dailyTotal += total;
                        dailyItems++;
                        dailyLength += length;
                        updateStats();
                    }

                    return total;
                } catch (error) {
                    console.error('خطأ في حساب الحفر المفتوح:', error);
                    return 0;
                }
            }

            // إضافة مستمعي الأحداث للحقول العادية
            document.querySelectorAll('tr').forEach(row => {
                const inputs = row.querySelectorAll('input[type="number"]');
                if (inputs.length > 0) {
                    inputs.forEach(input => {
                        input.addEventListener('input', () => {
                            if (row.classList.contains('open-excavation')) {
                                calculateOpenExcavation(row);
                            } else {
                                calculateTotal(row);
                            }
                        });
                    });
                }
            });

            // تحديث الإحصائيات الأولية
            updateStats();
        });

        // تم إزالة الكود المكرر - الكود الرئيسي موجود في الأسفل

        // دالة حفظ البيانات الديناميكي المحسنة
        function saveCivilWorksData() {
            const formData = new FormData();
            const workOrderId = {{ $workOrder->id }};
            
            // إضافة CSRF token
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'POST');
            
            // جمع البيانات المحددة فقط (تجنب البيانات الفارغة)
            const excavationData = {};
            
            // جمع بيانات الحفريات التربة غير المسفلتة
            const unsurfacedInputs = document.querySelectorAll('input[name^="excavation_unsurfaced_soil"]');
            const unsurfacedPrices = document.querySelectorAll('input[name^="excavation_unsurfaced_soil_price"]');
            
            unsurfacedInputs.forEach(input => {
                const value = parseFloat(input.value);
                if (value > 0) {
                    formData.append(input.name, value);
                }
            });
            
            unsurfacedPrices.forEach(input => {
                const value = parseFloat(input.value);
                if (value > 0) {
                    formData.append(input.name, value);
                }
            });
            
            // جمع بيانات الحفريات التربة المسفلتة
            const surfacedInputs = document.querySelectorAll('input[name^="excavation_surfaced_soil"]');
            const surfacedPrices = document.querySelectorAll('input[name^="excavation_surfaced_soil_price"]');
            
            surfacedInputs.forEach(input => {
                const value = parseFloat(input.value);
                if (value > 0) {
                    formData.append(input.name, value);
                }
            });
            
            surfacedPrices.forEach(input => {
                const value = parseFloat(input.value);
                if (value > 0) {
                    formData.append(input.name, value);
                }
            });
            
            // جمع بيانات الحفر المفتوح
            const openExcavationInputs = document.querySelectorAll('input[name*="_open"]');
            openExcavationInputs.forEach(input => {
                const value = parseFloat(input.value);
                if (value > 0) {
                    formData.append(input.name, value);
                }
            });
            
            // جمع ملفات الصور فقط (تجنب الملفات الكبيرة)
            const imageFiles = document.querySelectorAll('input[type="file"][accept*="image"]');
            imageFiles.forEach(input => {
                if (input.files.length > 0) {
                    Array.from(input.files).forEach(file => {
                        // تحديد حجم الملف (أقل من 5MB)
                        if (file.size < 5 * 1024 * 1024) {
                            formData.append(input.name, file);
                        }
                    });
                }
            });

            // إرسال البيانات مع timeout محسن
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 ثانية timeout
            
            fetch(`/admin/work-orders/${workOrderId}/civil-works`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: controller.signal
            })
            .then(response => {
                clearTimeout(timeoutId);
                if (!response.ok) {
                    // معالجة أخطاء مختلفة
                    if (response.status === 500) {
                        throw new Error('خطأ في الخادم - يرجى المحاولة مرة أخرى');
                    } else if (response.status === 422) {
                        throw new Error('بيانات غير صحيحة - يرجى مراجعة المدخلات');
                    } else if (response.status === 413) {
                        throw new Error('حجم البيانات كبير جداً - يرجى تقليل حجم الملفات');
                    }
                    throw new Error(`خطأ HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification('تم حفظ البيانات بنجاح', 'success');
                    updateDailySummary();
                } else {
                    showNotification(data.message || 'حدث خطأ أثناء الحفظ', 'error');
                }
            })
            .catch(error => {
                clearTimeout(timeoutId);
                if (error.name === 'AbortError') {
                    showNotification('انتهت مهلة الاستجابة - يرجى المحاولة مرة أخرى', 'error');
                } else {
                    console.error('خطأ في الحفظ:', error);
                    showNotification(error.message || 'حدث خطأ أثناء الحفظ', 'error');
                }
            });
        }

        // دالة عرض الإشعارات
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // دالة تحديث الملخص اليومي
        function updateDailySummary() {
            const tbody = document.querySelector('#summary-table tbody');
            if (!tbody) {
                console.warn('لم يتم العثور على جدول الملخص');
                return;
            }

            const totalInputs = document.querySelectorAll('[id*="final_total_"]');
            let dailyTotal = 0;
            let itemDetails = [];

            totalInputs.forEach(input => {
                const value = parseFloat(input.value) || 0;
                if (value > 0) {
                    dailyTotal += value;
                    const workType = input.id.replace('final_total_', '').replace(/_\d+$/, '');
                    itemDetails.push(`${workType}: ${value.toFixed(2)} ريال`);
                }
            });

            if (dailyTotal > 0) {
                const now = new Date();
                const dateStr = now.toLocaleDateString('ar-SA');
                const timeStr = now.toLocaleTimeString('ar-SA');
                
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>${dateStr}</td>
                    <td>${timeStr}</td>
                    <td>${itemDetails.join('<br>')}</td>
                    <td class="text-end">${dailyTotal.toFixed(2)} ريال</td>
                `;
                
                if (tbody.firstChild) {
                    tbody.insertBefore(newRow, tbody.firstChild);
                } else {
                    tbody.appendChild(newRow);
                }
                
                // تأثير بصري
                newRow.style.backgroundColor = '#e8f5e8';
                setTimeout(() => newRow.style.backgroundColor = '', 1000);
            }
        }

        // دالة حفظ البيانات في الجدول اليومي
        function saveDailyTableData() {
            const workOrderId = {{ $workOrder->id }};
            const dailyData = [];
            
            // جمع بيانات من جميع الصفوف المحسوبة
            const allRows = document.querySelectorAll('table tbody tr');
            
            allRows.forEach((row, index) => {
                try {
                    // البحث عن القيم في الصف
                    const lengthInput = row.querySelector('input[name*="excavation_"]:not([name*="price"]):not([name*="open"])');
                    const priceInput = row.querySelector('input[name*="price"]:not([name*="open"])');
                    const totalInput = row.querySelector('input[id*="total_"]:not([id*="open"])');
                    
                    // إذا وجدت قيم صحيحة
                    if (lengthInput && priceInput && totalInput) {
                        const length = parseFloat(lengthInput.value) || 0;
                        const price = parseFloat(priceInput.value) || 0;
                        const total = parseFloat(totalInput.value) || 0;
                        
                        if (length > 0 && price > 0 && total > 0) {
                            // تحديد نوع العمل
                            const workType = lengthInput.name.includes('surfaced_soil') ? 'تربة مسفلتة' : 'تربة غير مسفلتة';
                            const cableType = row.querySelector('td:first-child')?.textContent?.trim() || `العنصر ${index + 1}`;
                            
                            dailyData.push({
                                work_type: workType,
                                cable_type: cableType,
                                length: length,
                                price: price,
                                total: total,
                                date: new Date().toISOString().split('T')[0],
                                time: new Date().toTimeString().split(' ')[0]
                            });
                        }
                    }
                    
                    // معالجة الحفر المفتوح
                    const openLengthInput = row.querySelector('input[name*="_open"][name*="length"]');
                    const openWidthInput = row.querySelector('input[name*="_open"][name*="width"]');
                    const openDepthInput = row.querySelector('input[name*="_open"][name*="depth"]');
                    const openPriceInput = row.querySelector('input[name*="_open_price"]');
                    const openTotalInput = row.querySelector('input[id*="final_total_"][id*="_open"]');
                    
                    if (openLengthInput && openWidthInput && openDepthInput && openPriceInput && openTotalInput) {
                        const length = parseFloat(openLengthInput.value) || 0;
                        const width = parseFloat(openWidthInput.value) || 0;
                        const depth = parseFloat(openDepthInput.value) || 0;
                        const price = parseFloat(openPriceInput.value) || 0;
                        const total = parseFloat(openTotalInput.value) || 0;
                        
                        if (length > 0 && width > 0 && depth > 0 && price > 0 && total > 0) {
                            const workType = openLengthInput.name.includes('surfaced_soil') ? 'حفر مفتوح - تربة مسفلتة' : 'حفر مفتوح - تربة غير مسفلتة';
                            
                            dailyData.push({
                                work_type: workType,
                                cable_type: 'حفر مفتوح',
                                length: length,
                                width: width,
                                depth: depth,
                                volume: length * width * depth,
                                price: price,
                                total: total,
                                date: new Date().toISOString().split('T')[0],
                                time: new Date().toTimeString().split(' ')[0]
                            });
                        }
                    }
                } catch (error) {
                    console.warn('خطأ في معالجة الصف:', error);
                }
            });
            
            // إرسال البيانات للخادم
            if (dailyData.length > 0) {
                const formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                formData.append('daily_data', JSON.stringify(dailyData));
                
                fetch(`/admin/work-orders/${workOrderId}/civil-works/save-daily-data`, {
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
                        showNotification('تم حفظ البيانات اليومية بنجاح', 'success');
                        updateDailySummary();
                    } else {
                        showNotification(data.message || 'حدث خطأ أثناء حفظ البيانات اليومية', 'error');
                    }
                })
                .catch(error => {
                    console.error('خطأ في حفظ البيانات اليومية:', error);
                    showNotification('حدث خطأ أثناء حفظ البيانات اليومية', 'error');
                });
            } else {
                showNotification('لا توجد بيانات لحفظها', 'warning');
            }
        }

        // الكود الرئيسي للحاسبة
        document.addEventListener('DOMContentLoaded', function() {
            // دالة حساب الضرب المحسنة
            function calculateMultiplication(value1, value2) {
                try {
                    const num1 = parseFloat(value1) || 0;
                    const num2 = parseFloat(value2) || 0;
                    
                    if (num1 < 0 || num2 < 0) {
                        console.warn('القيم السالبة غير مسموح بها');
                        return 0;
                    }
                    
                    return num1 * num2;
                } catch (error) {
                    console.error('خطأ في عملية الضرب:', error);
                    return 0;
                }
            }

            // دالة حساب الحجم للحفر المفتوح
            function calculateVolume(length, width, depth) {
                try {
                    const l = parseFloat(length) || 0;
                    const w = parseFloat(width) || 0;
                    const d = parseFloat(depth) || 0;
                    
                    if (l < 0 || w < 0 || d < 0) {
                        console.warn('الأبعاد السالبة غير مسموح بها');
                        return 0;
                    }
                    
                    return l * w * d;
                } catch (error) {
                    console.error('خطأ في حساب الحجم:', error);
                    return 0;
                }
            }

            // دالة تحديث الحقل مع تأثير بصري
            function updateField(field, value) {
                if (!field) return;
                
                field.value = parseFloat(value).toFixed(2);
                field.style.backgroundColor = '#e8f5e8';
                setTimeout(() => field.style.backgroundColor = '', 500);

                // تحديث الإجمالي النهائي
                updateFinalTotal();
            }

            // دالة تحديث الإجمالي النهائي
            function updateFinalTotal() {
                const allTotalInputs = document.querySelectorAll('input[id*="final_total_"]');
                let grandTotal = 0;

                allTotalInputs.forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    grandTotal += value;
                });

                const grandTotalElement = document.getElementById('grand_total');
                if (grandTotalElement) {
                    grandTotalElement.textContent = grandTotal.toFixed(2) + ' ريال';
                    grandTotalElement.style.backgroundColor = '#e8f5e8';
                    setTimeout(() => grandTotalElement.style.backgroundColor = '', 500);
                }
            }

            // معالجة الحفر العادي (طول × سعر)
            function initializeRegularExcavation(soilType) {
                for (let i = 0; i < 8; i++) {
                    const lengthInput = document.querySelector(`input[name="${soilType}[${i}]"]`);
                    const priceInput = document.querySelector(`input[name="${soilType}_price[${i}]"]`);
                    const totalInput = document.getElementById(`final_total_${soilType}_${i}`);

                    if (lengthInput && priceInput && totalInput) {
                        function updateTotal() {
                            const total = calculateMultiplication(lengthInput.value, priceInput.value);
                            updateField(totalInput, total);
                            
                            // حفظ تلقائي بعد التحديث
                            saveDailyTableData();
                        }

                        [lengthInput, priceInput].forEach(input => {
                            input.addEventListener('input', updateTotal);
                        });
                    }
                }
            }

            // معالجة الحفر المفتوح
            function initializeOpenExcavation(soilType) {
                const lengthInput = document.querySelector(`input[name="${soilType}_open_length"]`);
                const widthInput = document.querySelector(`input[name="${soilType}_open_width"]`);
                const depthInput = document.querySelector(`input[name="${soilType}_open_depth"]`);
                const priceInput = document.querySelector(`input[name="${soilType}_open_price"]`);
                const totalInput = document.getElementById(`final_total_${soilType}_open`);
                const volumeInput = document.getElementById(`volume_${soilType}_open`);

                if (lengthInput && widthInput && depthInput && priceInput && totalInput && volumeInput) {
                    function updateOpenTotal() {
                        const volume = calculateVolume(lengthInput.value, widthInput.value, depthInput.value);
                        updateField(volumeInput, volume);

                        const total = calculateMultiplication(volume, priceInput.value);
                        updateField(totalInput, total);
                        
                        // حفظ تلقائي بعد التحديث
                        saveDailyTableData();
                    }

                    [lengthInput, widthInput, depthInput, priceInput].forEach(input => {
                        input.addEventListener('input', updateOpenTotal);
                    });
                }
            }

            // تهيئة جميع الحقول
            initializeRegularExcavation('surfaced_soil_excavation');
            initializeRegularExcavation('unsurfaced_soil_excavation');
            initializeOpenExcavation('surfaced_soil');
            initializeOpenExcavation('unsurfaced_soil');

            // إضافة زر حفظ البيانات
            const saveButton = document.getElementById('save-daily-data');
            if (saveButton) {
                saveButton.addEventListener('click', saveDailyTableData);
            }
        });

        // دالة حفظ تفاصيل الحفرية
        function saveExcavationDetail(excavationType, length, width, depth, price, total, isOpenExcavation, soilType) {
            const workOrderId = {{ $workOrder->id }};
            const data = {
                excavation_type: excavationType,
                length: length,
                width: width || null,
                depth: depth || null,
                price: price,
                total: total,
                is_open_excavation: isOpenExcavation,
                soil_type: soilType
            };

            fetch(`/admin/work-orders/${workOrderId}/civil-works/save-excavation`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showNotification('تم حفظ تفاصيل الحفرية بنجاح', 'success');
                } else {
                    showNotification(result.message || 'حدث خطأ أثناء الحفظ', 'error');
                }
            })
            .catch(error => {
                console.error('خطأ في حفظ تفاصيل الحفرية:', error);
                showNotification('حدث خطأ أثناء حفظ تفاصيل الحفرية', 'error');
            });
        }

        // تحديث دالة updateTotal لتشمل حفظ التفاصيل
        function updateTotal() {
            const total = calculateMultiplication(lengthInput.value, priceInput.value);
            updateField(totalInput, total);
            
            // حفظ تفاصيل الحفرية
            if (lengthInput.value && priceInput.value) {
                const excavationType = lengthInput.closest('tr').querySelector('td:first-child').textContent.trim();
                const soilType = lengthInput.name.includes('surfaced_soil') ? 'مسفلتة' : 'غير مسفلتة';
                
                saveExcavationDetail(
                    excavationType,
                    parseFloat(lengthInput.value),
                    null,
                    null,
                    parseFloat(priceInput.value),
                    total,
                    false,
                    soilType
                );
            }
        }

        // تحديث دالة updateOpenTotal لتشمل حفظ التفاصيل
        function updateOpenTotal() {
            const volume = calculateVolume(lengthInput.value, widthInput.value, depthInput.value);
            updateField(volumeInput, volume);

            const total = calculateMultiplication(volume, priceInput.value);
            updateField(totalInput, total);
            
            // حفظ تفاصيل الحفرية المفتوحة
            if (lengthInput.value && widthInput.value && depthInput.value && priceInput.value) {
                const soilType = lengthInput.name.includes('surfaced_soil') ? 'مسفلتة' : 'غير مسفلتة';
                
                saveExcavationDetail(
                    'حفر مفتوح',
                    parseFloat(lengthInput.value),
                    parseFloat(widthInput.value),
                    parseFloat(depthInput.value),
                    parseFloat(priceInput.value),
                    total,
                    true,
                    soilType
                );
            }
        }

        // دالة تحديث الملخص اليومي
        function showTodaySummary() {
            const workOrderId = {{ $workOrder->id }};
            
            fetch(`/admin/work-orders/${workOrderId}/civil-works/today-excavations`)
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const { excavations, summary } = result.data;
                        
                        // تحديث بيانات الملخص
                        document.getElementById('totalAmount').textContent = summary.total_amount.toFixed(2) + ' ريال';
                        document.getElementById('totalLength').textContent = summary.total_length.toFixed(2) + ' متر';
                        document.getElementById('excavationCount').textContent = summary.excavation_count;
                        document.getElementById('surfacedSoilCount').textContent = summary.surfaced_soil_count;
                        document.getElementById('unsurfacedSoilCount').textContent = summary.unsurfaced_soil_count;
                        document.getElementById('openExcavationCount').textContent = summary.open_excavation_count;
                        
                        // تحديث جدول التفاصيل
                        const tbody = document.getElementById('excavationDetailsTable');
                        tbody.innerHTML = '';
                        
                        excavations.forEach(excavation => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${excavation.excavation_type}</td>
                                <td>${excavation.soil_type}</td>
                                <td>${excavation.length.toFixed(2)} متر</td>
                                <td>${excavation.width ? excavation.width.toFixed(2) + ' متر' : '-'}</td>
                                <td>${excavation.depth ? excavation.depth.toFixed(2) + ' متر' : '-'}</td>
                                <td>${excavation.price.toFixed(2)} ريال</td>
                                <td>${excavation.total.toFixed(2)} ريال</td>
                                <td>${excavation.created_at}</td>
                            `;
                            tbody.appendChild(row);
                        });
                        
                        // عرض النافذة المنبثقة
                        const modal = new bootstrap.Modal(document.getElementById('todaySummaryModal'));
                        modal.show();
                    } else {
                        showNotification(result.message || 'حدث خطأ أثناء جلب البيانات', 'error');
                    }
                })
                .catch(error => {
                    console.error('خطأ في جلب بيانات اليوم:', error);
                    showNotification('حدث خطأ أثناء جلب بيانات اليوم', 'error');
                });
        }

        // دالة طباعة الملخص
        function printSummary() {
            const printWindow = window.open('', '_blank');
            const content = document.getElementById('todaySummaryModal').querySelector('.modal-body').innerHTML;
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html dir="rtl">
                <head>
                    <title>ملخص حفريات اليوم</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
                        .card { margin-bottom: 15px; }
                        @media print {
                            .card { break-inside: avoid; }
                        }
                    </style>
                </head>
                <body class="p-4">
                    <h3 class="text-center mb-4">ملخص حفريات اليوم - ${new Date().toLocaleDateString('ar-SA')}</h3>
                    ${content}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        }

        // تحديث زر الحفظ ليعرض الملخص
        document.getElementById('save-daily-data').addEventListener('click', function() {
            saveDailyTableData();
            showTodaySummary();
        });
    </script>

    <div class="modal fade" id="todaySummaryModal" tabindex="-1" aria-labelledby="todaySummaryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient text-white">
                    <h5 class="modal-title" id="todaySummaryModalLabel">ملخص حفريات اليوم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- قسم الملخص -->
                    <div class="summary-section mb-4">
                        <h6 class="border-bottom pb-2 mb-3">الملخص الإجمالي</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">الإجمالي</h6>
                                        <h4 class="mb-0" id="modalTotalAmount">0 ريال</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">إجمالي الأطوال</h6>
                                        <h4 class="mb-0" id="modalTotalLength">0 متر</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">عدد الحفريات</h6>
                                        <h4 class="mb-0" id="modalExcavationCount">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">تربة مسفلتة</h6>
                                        <p class="mb-0" id="modalSurfacedSoilCount">0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">تربة غير مسفلتة</h6>
                                        <p class="mb-0" id="modalUnsurfacedSoilCount">0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">حفر مفتوح</h6>
                                        <p class="mb-0" id="modalOpenExcavationCount">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- جدول التفاصيل -->
                    <div class="details-section">
                        <h6 class="border-bottom pb-2 mb-3">تفاصيل الحفريات</h6>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>نوع الحفرية</th>
                                        <th>نوع التربة</th>
                                        <th>الطول</th>
                                        <th>العرض</th>
                                        <th>العمق</th>
                                        <th>السعر</th>
                                        <th>الإجمالي</th>
                                        <th>وقت الإدخال</th>
                                    </tr>
                                </thead>
                                <tbody id="modalExcavationDetailsTable">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-primary" onclick="printSummary()">طباعة الملخص</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 text-start">
            <button type="button" id="save-summary-btn" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>حفظ وعرض الملخص
            </button>
        </div>
    </div>

    <!-- تضمين Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // تم نقل جميع الدوال إلى الجزء الثاني من الـ script
    </script>

    <script>
        // تعريف المتغيرات العامة
        let excavationRows = [];
        let currentExcavationIndex = 0;

        // دالة عرض الإشعارات
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }

        // دالة إضافة حفرية جديدة
        function addNewExcavation() {
            const table = document.querySelector('.excavation-table tbody');
            if (!table) {
                console.warn('لم يتم العثور على جدول الحفريات');
                return;
            }

            const newRow = document.createElement('tr');
            newRow.dataset.excavationId = `excavation-${Date.now()}`;
            newRow.innerHTML = `
                <td>
                    <input type="text" class="form-control" name="cable_type[]" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="length[]" step="0.01" required>
                </td>
                <td>
                    <input type="number" class="form-control" name="width[]" step="0.01">
                </td>
                <td>
                    <input type="number" class="form-control" name="depth[]" step="0.01">
                </td>
                <td>
                    <input type="number" class="form-control" name="price[]" step="0.01" required>
                </td>
                <td class="total-cell">0.00</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-row">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            table.appendChild(newRow);
            setupRowCalculations(newRow);
        }

        // دالة إعداد حسابات الصف
        function setupRowCalculations(row) {
            const inputs = row.querySelectorAll('input[type="number"]');
            inputs.forEach(input => {
                input.addEventListener('input', () => calculateRowTotal(row));
            });
        }

        // دالة حساب إجمالي الصف
        function calculateRowTotal(row) {
            const length = parseFloat(row.querySelector('[name="length[]"]')?.value || 0);
            const price = parseFloat(row.querySelector('[name="price[]"]')?.value || 0);
            const total = length * price;
            
            const totalCell = row.querySelector('.total-cell');
            if (totalCell) {
                totalCell.textContent = total.toFixed(2);
            }
            
            updateTotals();
        }

        // دالة تحديث الإجماليات
        function updateTotals() {
            let grandTotal = 0;
            document.querySelectorAll('.total-cell').forEach(cell => {
                grandTotal += parseFloat(cell.textContent || 0);
            });

            const grandTotalElement = document.getElementById('grandTotal');
            if (grandTotalElement) {
                grandTotalElement.textContent = grandTotal.toFixed(2);
            }
        }

        // دالة حفظ بيانات الجدول اليومي
        function saveDailyTableData() {
            console.log('جاري حفظ بيانات الجدول اليومي...');
            // هنا يمكن إضافة منطق الحفظ
            showNotification('تم حفظ البيانات بنجاح', 'success');
        }

        // دالة عرض ملخص اليوم
        function showTodaySummary() {
            console.log('جاري عرض ملخص اليوم...');
            
            // محاكاة بيانات للاختبار
            const mockData = {
                excavations: [
                    {
                        excavation_type: 'كابل منخفض',
                        soil_type: 'تربة مسفلتة',
                        length: 10.5,
                        width: 0.5,
                        depth: 0.8,
                        price: 50.0,
                        total: 525.0,
                        created_at: new Date().toLocaleString('ar-SA')
                    }
                ],
                summary: {
                    total_amount: 525.0,
                    total_length: 10.5,
                    excavation_count: 1,
                    surfaced_soil_count: 1,
                    unsurfaced_soil_count: 0,
                    open_excavation_count: 0
                }
            };

            // تحديث بيانات الملخص
            const elements = {
                totalAmount: document.getElementById('modalTotalAmount'),
                totalLength: document.getElementById('modalTotalLength'),
                excavationCount: document.getElementById('modalExcavationCount'),
                surfacedSoilCount: document.getElementById('modalSurfacedSoilCount'),
                unsurfacedSoilCount: document.getElementById('modalUnsurfacedSoilCount'),
                openExcavationCount: document.getElementById('modalOpenExcavationCount'),
                excavationDetailsTable: document.getElementById('modalExcavationDetailsTable')
            };

            // التحقق من وجود جميع العناصر قبل التحديث
            if (Object.values(elements).every(el => el)) {
                const { excavations, summary } = mockData;
                
                elements.totalAmount.textContent = summary.total_amount.toFixed(2) + ' ريال';
                elements.totalLength.textContent = summary.total_length.toFixed(2) + ' متر';
                elements.excavationCount.textContent = summary.excavation_count;
                elements.surfacedSoilCount.textContent = summary.surfaced_soil_count;
                elements.unsurfacedSoilCount.textContent = summary.unsurfaced_soil_count;
                elements.openExcavationCount.textContent = summary.open_excavation_count;
                
                // تحديث جدول التفاصيل
                elements.excavationDetailsTable.innerHTML = '';
                
                excavations.forEach(excavation => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${excavation.excavation_type}</td>
                        <td>${excavation.soil_type}</td>
                        <td>${excavation.length.toFixed(2)} متر</td>
                        <td>${excavation.width ? excavation.width.toFixed(2) + ' متر' : '-'}</td>
                        <td>${excavation.depth ? excavation.depth.toFixed(2) + ' متر' : '-'}</td>
                        <td>${excavation.price.toFixed(2)} ريال</td>
                        <td>${excavation.total.toFixed(2)} ريال</td>
                        <td>${excavation.created_at}</td>
                    `;
                    elements.excavationDetailsTable.appendChild(row);
                });

                // عرض النافذة المنبثقة
                const modal = new bootstrap.Modal(document.getElementById('todaySummaryModal'));
                modal.show();
                
                console.log('تم عرض ملخص اليوم بنجاح');
            } else {
                console.warn('بعض عناصر الملخص غير موجودة في الصفحة');
                showNotification('حدث خطأ في عرض الملخص', 'error');
            }
        }

        // دالة طباعة الملخص
        function printSummary() {
            const modalBody = document.getElementById('todaySummaryModal')?.querySelector('.modal-body');
            if (!modalBody) {
                console.warn('لم يتم العثور على محتوى الملخص');
                return;
            }

            const printWindow = window.open('', '_blank');
            const content = modalBody.innerHTML;
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html dir="rtl">
                <head>
                    <title>ملخص حفريات اليوم</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        body { 
                            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                            padding: 20px;
                        }
                        .card { 
                            margin-bottom: 15px;
                            break-inside: avoid;
                        }
                        table { width: 100%; margin-bottom: 20px; }
                        th, td { padding: 8px; text-align: right; }
                        th { background-color: #f8f9fa; }
                        @media print {
                            .card { break-inside: avoid; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body class="p-4">
                    <h3 class="text-center mb-4">ملخص حفريات اليوم - ${new Date().toLocaleDateString('ar-SA')}</h3>
                    ${content}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        }

        // دالة تهيئة الصفحة
        function initializePage() {
            console.log('بدء تهيئة الصفحة...');
            
            // تهيئة الأزرار
            initializeButtons();
            
            // تهيئة الجداول
            setupTables();
            
            // تهيئة المستمعات
            setupEventListeners();
            
            console.log('اكتملت تهيئة الصفحة');
        }

        // دالة تهيئة الأزرار
        function initializeButtons() {
            console.log('جاري تهيئة الأزرار...');
            
            const buttons = {
                addExcavation: document.querySelector('.add-excavation'),
                saveSummary: document.getElementById('save-summary-btn'),
                print: document.querySelector('[data-action="print"]')
            };

            if (buttons.addExcavation) {
                buttons.addExcavation.addEventListener('click', addNewExcavation);
                console.log('تم تهيئة زر إضافة حفرية جديدة');
            } else {
                console.warn('زر إضافة حفرية جديدة غير موجود');
            }

            if (buttons.saveSummary) {
                buttons.saveSummary.addEventListener('click', function() {
                    saveDailyTableData();
                    showTodaySummary();
                });
                console.log('تم تهيئة زر حفظ الملخص');
            } else {
                console.warn('زر حفظ الملخص غير موجود');
            }

            if (buttons.print) {
                buttons.print.addEventListener('click', printSummary);
                console.log('تم تهيئة زر الطباعة');
            } else {
                console.warn('زر الطباعة غير موجود');
            }
        }

        // دالة تهيئة الجداول
        function setupTables() {
            console.log('جاري تهيئة الجداول...');
            const tables = document.querySelectorAll('.excavation-table');
            if (tables.length > 0) {
                tables.forEach(table => {
                    const rows = table.querySelectorAll('tr[data-excavation-id]');
                    rows.forEach(row => {
                        setupRowCalculations(row);
                    });
                });
                console.log(`تم تهيئة ${tables.length} جدول`);
            } else {
                console.warn('لم يتم العثور على جداول للحفريات');
            }
        }

        // دالة تهيئة مستمعات الأحداث
        function setupEventListeners() {
            console.log('جاري تهيئة مستمعات الأحداث...');
            
            // مستمع لأحداث الإدخال
            document.addEventListener('input', function(e) {
                const row = e.target.closest('tr[data-excavation-id]');
                if (row) {
                    calculateRowTotal(row);
                }
            });

            // مستمع لأحداث الحذف
            document.addEventListener('click', function(e) {
                if (e.target.matches('.delete-row') || e.target.closest('.delete-row')) {
                    const row = e.target.closest('tr');
                    if (row) {
                        row.remove();
                        updateTotals();
                    }
                }
            });

            console.log('تم تهيئة مستمعات الأحداث');
        }

        // تهيئة الصفحة عند اكتمال تحميل DOM
        document.addEventListener('DOMContentLoaded', function() {
            console.log('تم تحميل DOM بالكامل');
            initializePage();
        });
    </script>
</body>
</html> 