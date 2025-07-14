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

        /* تنسيق الجدول الرئيسي */
        #daily-excavation-table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            border: none;
            margin-bottom: 2rem;
        }

        /* تنسيق رأس الجدول */
        #daily-excavation-table thead {
            background: linear-gradient(45deg, #1a237e, #283593);
        }

        #daily-excavation-table thead th {
            color: white !important;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 1rem;
            text-align: center;
            border: none;
            white-space: nowrap;
            vertical-align: middle;
        }

        #daily-excavation-table thead th i {
            margin-left: 0.5rem;
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* تنسيق صفوف الجدول */
        #daily-excavation-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        #daily-excavation-table tbody tr:hover {
            background-color: #f8f9ff !important;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        #daily-excavation-table tbody td {
            padding: 0.8rem;
            vertical-align: middle;
            text-align: center;
            font-size: 0.9rem;
            color: #333;
        }

        /* تنسيق خلايا البيانات */
        .section-type-cell {
            background: linear-gradient(45deg, #e3f2fd, #bbdefb);
            color: #1565c0;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-block;
            min-width: 120px;
            border: 1px solid rgba(21, 101, 192, 0.1);
            box-shadow: 0 2px 4px rgba(21, 101, 192, 0.1);
        }

        .excavation-type-cell {
            background: linear-gradient(45deg, #f3e5f5, #e1bee7);
            color: #6a1b9a;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-block;
            min-width: 120px;
            border: 1px solid rgba(106, 27, 154, 0.1);
            box-shadow: 0 2px 4px rgba(106, 27, 154, 0.1);
        }

        .cable-type-badge {
            background: linear-gradient(45deg, #fff3e0, #ffe0b2);
            color: #e65100;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-block;
            min-width: 100px;
            border: 1px solid rgba(230, 81, 0, 0.1);
            box-shadow: 0 2px 4px rgba(230, 81, 0, 0.1);
        }

        .dimensions-cell {
            background: linear-gradient(45deg, #e3f2fd, #bbdefb);
            color: #1565c0;
            padding: 0.5rem;
            border-radius: 8px;
            font-weight: 600;
            min-width: 80px;
            border: 1px solid rgba(21, 101, 192, 0.1);
            box-shadow: 0 2px 4px rgba(21, 101, 192, 0.1);
        }

        .price-cell {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            background: linear-gradient(45deg, #f3f4f6, #ffffff);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .price-value {
            font-weight: 600;
            color: #1a56db;
            font-size: 1rem;
        }

        .total-cell {
            background: linear-gradient(45deg, #e8f5e9, #c8e6c9);
            color: #2e7d32;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            min-width: 100px;
            border: 1px solid rgba(46, 125, 50, 0.1);
            box-shadow: 0 2px 4px rgba(46, 125, 50, 0.1);
        }

        /* تحسين مظهر الخلايا في الشاشات الصغيرة */
        @media (max-width: 768px) {
            .price-cell {
                padding: 0.3rem;
            }

            .price-value {
                font-size: 0.9rem;
            }

            .dimensions-cell,
            .total-cell {
                padding: 0.3rem 0.5rem;
                min-width: 60px;
                font-size: 0.85rem;
            }

            small.text-muted {
                font-size: 0.75rem;
            }
        }

        .last-update-cell {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            color: #6c757d;
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            min-width: 120px;
            border: 1px solid rgba(108, 117, 125, 0.1);
            box-shadow: 0 2px 4px rgba(108, 117, 125, 0.1);
        }

        .last-update-cell i {
            color: #6c757d;
            margin-left: 0.5rem;
        }

        @media (max-width: 768px) {
            .last-update-cell {
                font-size: 0.8rem;
                padding: 0.3rem;
                min-width: 100px;
            }
        }

        /* تنسيق حالة عدم وجود بيانات */
        .empty-state-content {
            padding: 3rem;
            text-align: center;
            background: linear-gradient(45deg, #f8f9fa, #ffffff);
            border-radius: 15px;
            border: 2px dashed #e0e0e0;
        }

        .empty-state-content i {
            color: #9e9e9e;
            margin-bottom: 1rem;
        }

        .empty-state-content h5 {
            color: #616161;
            margin-bottom: 0.5rem;
        }

        .empty-state-content p {
            color: #9e9e9e;
            margin-bottom: 0.5rem;
        }

        /* تحسينات للشاشات الصغيرة */
        @media (max-width: 768px) {
            #daily-excavation-table {
                font-size: 0.85rem;
            }

            #daily-excavation-table thead th {
                padding: 0.8rem 0.5rem;
                font-size: 0.8rem;
            }

            #daily-excavation-table tbody td {
                padding: 0.6rem 0.4rem;
            }

            .section-type-cell,
            .excavation-type-cell,
            .cable-type-badge,
            .dimensions-cell,
            .price-cell,
            .total-cell {
                min-width: auto;
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }

            .last-update-cell {
                font-size: 0.7rem;
                padding: 0.3rem 0.5rem;
            }
        }

        /* تأثيرات حركية */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .daily-excavation-row {
            animation: fadeIn 0.3s ease-out;
        }

        /* تنسيق أرقام الصفوف */
        .row-number {
            background: #f5f5f5;
            color: #616161;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* تحسين قابلية القراءة */
        #daily-excavation-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        #daily-excavation-table tbody td {
            border-bottom: 1px solid #f0f0f0;
        }

        #daily-excavation-table tbody tr:last-child td {
            border-bottom: none;
        }

        .details-cell {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            padding: 0.75rem;
            border-radius: 8px;
            color: #495057;
            min-width: 150px;
            border: 1px solid rgba(73, 80, 87, 0.1);
            box-shadow: 0 2px 4px rgba(73, 80, 87, 0.1);
        }

        .width-info {
            font-size: 0.9rem;
            padding: 0.3rem 0;
            border-bottom: 1px dashed #dee2e6;
        }

        .update-info {
            font-size: 0.85rem;
            color: #6c757d;
            padding-top: 0.3rem;
        }

        .details-cell i {
            color: #6c757d;
            width: 16px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .details-cell {
                padding: 0.5rem;
                min-width: 120px;
            }

            .width-info {
                font-size: 0.8rem;
            }

            .update-info {
                font-size: 0.75rem;
            }
        }

        /* تنسيق قسم التفاصيل */
        .details-container {
            position: relative;
        }

        .details-content {
            position: absolute;
            z-index: 1000;
            background: white;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            min-width: 200px;
            left: 50%;
            transform: translateX(-50%);
        }

        .details-container .btn-link {
            text-decoration: none;
            color: #1565c0;
            font-size: 0.9rem;
        }

        .details-container .btn-link:hover {
            color: #0d47a1;
        }

        .details-content .text-muted {
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .details-content i {
            margin-left: 0.5rem;
            color: #757575;
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <!-- Civil Works Professional System -->
    <script src="{{ asset('js/civil-works-professional.js') }}" defer></script>
    
    <!-- تحميل البيانات المحفوظة -->
    <script>
        // تهيئة المتغيرات العامة
        window.workOrderId = {{ $workOrder->id }};
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        window.savedDailyData = @json($savedDailyData ?? []);
        
        // دالة التهيئة الرئيسية
        function initializeDailyExcavation() {
            console.log('Initializing daily excavation system...');
            
            // تحميل البيانات المحفوظة
            loadSavedDailyWork();
            
            // إضافة مستمعي الأحداث
            const saveButton = document.getElementById('save-daily-summary-btn');
            if (saveButton) {
                saveButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    saveData();
                });
            }
            
            // إضافة مستمع لأحداث التغيير في الجدول
            const tbody = document.getElementById('daily-excavation-tbody');
            if (tbody) {
                tbody.addEventListener('change', function() {
                    // تحديث الإحصائيات عند تغيير البيانات
                    updateStatistics();
                });
            }
            
            // تحديث الإحصائيات الأولية
            updateStatistics();
            
            console.log('Daily excavation system initialized successfully');
        }
        
        // تهيئة النظام عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            initializeDailyExcavation();
        });
    </script>
</body>
</html> 