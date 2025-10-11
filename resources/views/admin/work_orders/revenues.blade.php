@extends('layouts.app')

@section('title', 'إدارة الإيرادات')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    /* Custom column width for statistics cards */
    .col-md-1-5 {
        width: 12.5%;
    }

    /* Custom styles for revenues page */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-radius: 15px 15px 0 0 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .table-responsive {
        border-radius: 10px;
        box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
        max-height: 50vh;
        overflow: auto;
    }

    .table {
        margin-bottom: 0;
        font-size: 0.85rem;
        min-width: 1800px;
    }

    .table thead th {
        background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
        color: white;
        font-weight: 600;
        text-align: center;
        padding: 8px 4px;
        border: none;
        white-space: nowrap;
        font-size: 0.75rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        position: sticky;
        top: 0;
        z-index: 10;
        vertical-align: middle;
        line-height: 1.3;
    }

    .table tbody td {
        padding: 4px;
        border: 1px solid #e9ecef;
        vertical-align: middle;
        text-align: center;
        transition: all 0.2s ease;
        position: relative;
        min-width: 80px;
        max-width: 150px;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:hover {
        background-color: #e3f2fd;
        transform: scale(1.005);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .editable-field {
        min-height: 28px;
        min-width: 70px;
        padding: 6px 8px;
        border: 2px solid transparent;
        border-radius: 4px;
        background: transparent;
        transition: all 0.2s ease;
        cursor: text;
        display: block;
        width: 100%;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .editable-field:hover {
        background-color: rgba(13, 110, 253, 0.08);
        border-color: rgba(13, 110, 253, 0.3);
    }

    .editable-field:focus {
        outline: none;
        background-color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    }
    
    .editable-field[contenteditable="true"]:empty:before {
        content: attr(placeholder);
        color: #adb5bd;
        font-style: italic;
    }

    .editable-field:empty::before {
        content: attr(placeholder);
        color: #6c757d;
        opacity: 0.7;
        font-style: italic;
        font-size: 0.7rem;
    }

    .save-indicator {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        z-index: 5;
    }

    .saving {
        background: #ffc107;
        animation: pulse 1s infinite;
    }

    .saved {
        background: #28a745;
    }

    .error {
        background: #dc3545;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.8); }
    }

    .new-row {
        animation: newRowSlide 0.5s ease-out;
        background: linear-gradient(90deg, rgba(40, 167, 69, 0.1), rgba(255, 255, 255, 0.1));
    }

    @keyframes newRowSlide {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .action-buttons {
        display: flex;
        gap: 3px;
        justify-content: center;
        align-items: center;
    }

    .btn-sm {
        padding: 2px 4px;
        font-size: 0.65rem;
    }

    /* Excel buttons styling */
    .btn-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-info:hover {
        background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .btn-warning {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        border: none;
        color: #212529;
        transition: all 0.3s ease;
    }

    .btn-warning:hover {
        background: linear-gradient(135deg, #e0a800 0%, #d39e00 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        color: #212529;
    }

    /* Auto save status */
    .auto-save-status {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        border: 1px solid rgba(40, 167, 69, 0.3);
        display: inline-block;
    }

    /* تنسيق زر العودة */
    .btn-outline-light {
        border: 1px solid rgba(255, 255, 255, 0.5);
        transition: all 0.3s ease;
    }

    .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.8);
        transform: translateX(-2px);
    }

    .btn-outline-light:active {
        transform: translateX(0);
    }

    /* Serial number column */
    .serial-col {
        min-width: 40px !important;
        max-width: 40px !important;
        width: 40px !important;
    }

    /* Date columns - smaller width */
    .date-col {
        min-width: 90px !important;
        max-width: 110px !important;
        width: 100px !important;
    }

    .date-col .date-input {
        width: 100% !important;
        min-width: 85px !important;
        font-size: 0.7rem !important;
    }

    .badge {
        font-size: 0.75rem;
        padding: 4px 8px;
    }

    /* Date input styling */
    .date-input {
        border: 2px solid transparent;
        border-radius: 4px;
        padding: 6px 8px;
        font-size: 0.85rem;
        width: 100%;
        background: transparent;
        cursor: pointer;
        min-height: 32px;
        transition: all 0.2s ease;
    }

    .date-input:focus {
        outline: none;
        background: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    }

    .date-input:hover {
        background: rgba(13, 110, 253, 0.08);
        border-color: rgba(13, 110, 253, 0.3);
    }

    /* Number input styling - إخفاء الأسهم وتحسين المظهر */
    input[type="number"] {
        -moz-appearance: textfield;
        text-align: center;
    }

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"].form-control {
        border: 2px solid transparent;
        border-radius: 4px;
        background: transparent;
        transition: all 0.2s ease;
        padding: 6px 8px;
        font-size: 0.85rem;
        min-height: 32px;
    }

    input[type="number"].form-control:hover {
        background-color: rgba(13, 110, 253, 0.08);
        border-color: rgba(13, 110, 253, 0.3);
    }

    input[type="number"].form-control:focus {
        outline: none;
        background-color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    }

    /* Filter Section Styles - استايل حقول الفلتر */
    .card.mb-3 {
        border-radius: 12px;
        border: 1px solid #e3e6ea;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .card.mb-3:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .card.mb-3 .card-body {
        padding: 1.25rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }

    /* Filter Input & Select Styles */
    .form-control-sm, .form-select-sm {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        transition: all 0.3s ease;
        background-color: #ffffff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .form-control-sm:hover, .form-select-sm:hover {
        border-color: #0d6efd;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.15);
    }

    .form-control-sm:focus, .form-select-sm:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        background-color: #f8f9ff;
    }

    /* Filter Labels */
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
    }

    .form-label i {
        color: #0d6efd;
        margin-left: 0.3rem;
        transition: transform 0.3s ease;
    }

    .form-label:hover i {
        transform: scale(1.15);
    }

    /* Filter Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        border: none;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.4);
    }

    .btn-outline-secondary {
        border: 1px solid #6c757d;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
        background-color: #ffffff;
    }

    .btn-outline-secondary:hover {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(108, 117, 125, 0.3);
    }

    /* Quick Date Filters */
    .btn-outline-primary {
        border: 1px solid #0d6efd;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-size: 0.75rem;
        padding: 0.35rem 0.75rem;
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.25);
    }

    /* Filter Row Spacing */
    .row.g-2 {
        margin-bottom: 0.75rem;
    }

    /* Auto-save feedback */
    .saving {
        background-color: #fff3cd !important;
        border-color: #ffc107 !important;
    }
    
    .saved {
        background-color: #d1e7dd !important;
        border-color: #28a745 !important;
        animation: savedPulse 0.5s ease-in-out;
    }
    
    @keyframes savedPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
    
    .error-field {
        background-color: #f8d7da !important;
        border-color: #dc3545 !important;
    }
    
    /* Tab navigation enhancement */
    .editable-field:focus-within,
    .form-control:focus,
    .field-focused {
        z-index: 5;
    }
    
    .field-focused {
        transform: scale(1.02);
    }
    
    /* Select dropdown styling */
    select.form-control {
        cursor: pointer;
        border: 2px solid transparent;
        border-radius: 4px;
        padding: 6px 8px;
        font-size: 0.85rem;
        min-height: 32px;
        background-color: transparent;
        transition: all 0.2s ease;
    }
    
    select.form-control:hover {
        background-color: rgba(13, 110, 253, 0.08);
        border-color: rgba(13, 110, 253, 0.3);
    }
    
    select.form-control:focus {
        outline: none;
        background-color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table {
            font-size: 0.75rem;
            min-width: 1400px;
        }
        
        .editable-field {
            font-size: 0.75rem;
            min-height: 26px;
            padding: 4px 6px;
        }

        .date-input {
            font-size: 0.75rem;
            min-height: 26px;
            padding: 4px 6px;
        }

        .table thead th {
            padding: 6px 2px;
            font-size: 0.65rem;
        }

        .table tbody td {
            padding: 2px;
        }

        .date-col {
            min-width: 80px !important;
            max-width: 95px !important;
            width: 85px !important;
        }

        .date-col .date-input {
            min-width: 75px !important;
            font-size: 0.6rem !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white py-2">
                    <div class="d-flex align-items-center justify-content-between w-100">
                        <div class="d-flex align-items-center">
                            <span style="font-size: 0.95rem;">
                                <i class="fas fa-chart-line me-2"></i>
                                إدارة الإيرادات
                            </span>
                            
                            @if(isset($projectName))
                            <span class="badge bg-info text-white ms-2" style="font-size: 0.75rem;">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $projectName }}
                            </span>
                            @endif
                        </div>
                        
                        <!-- زر العودة إلى أوامر العمل -->
                        @if(isset($project))
                        <a href="{{ route('admin.work-orders.index', ['project' => $project]) }}" 
                           class="btn btn-outline-light btn-sm" 
                           style="font-size: 0.8rem; padding: 0.25rem 0.75rem;"
                           title="العودة إلى أوامر العمل">
                            <i class="fas fa-arrow-right me-1"></i>
                            عودة لأوامر العمل
                        </a>
                        @endif
                    </div>
                    
                </div>

                <div class="card-body p-0">
                    <!-- Statistics Cards Section -->
                    <div class="py-1 px-2 bg-light">
                        <div class="row g-1">
                            <!-- إجمالي عدد المستخلصات -->
                            <div class="col-md-1-5">
                                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                    <div class="card-body p-1 text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 opacity-75" style="font-size: 0.65rem;">عدد المستخلصات</h6>
                                                <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">{{ $statistics['totalRevenues'] }}</h6>
                                            </div>
                                            <div class="bg-white bg-opacity-25 p-1 rounded-circle" style="width: 25px; height: 25px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-file-invoice" style="font-size: 0.7rem;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- إجمالي قيمة المستخلصات غير شامله الضريبه -->
                            <div class="col-md-1-5">
                                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);">
                                    <div class="card-body p-1 text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 opacity-75" style="font-size: 0.6rem;">إجمالي قيمة المستخلصات غير شامله الضريبه</h6>
                                                <h6 class="mb-0 fw-bold" style="font-size: 0.75rem;">{{ number_format($statistics['totalExtractValue'], 2) }}</h6>
                                                <small class="opacity-75" style="font-size: 0.6rem;">ريال سعودي</small>
                                            </div>
                                            <div class="bg-white bg-opacity-25 p-1 rounded-circle">
                                                <i class="fas fa-money-bill-wave fa-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- إجمالي الضريبة -->
                            <div class="col-md-1-5">
                                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                    <div class="card-body p-1 text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 opacity-75" style="font-size: 0.65rem;">إجمالي الضريبة</h6>
                                                <h6 class="mb-0 fw-bold" style="font-size: 0.75rem;">{{ number_format($statistics['totalTaxValue'], 2) }}</h6>
                                                <small class="opacity-75" style="font-size: 0.6rem;">ريال سعودي</small>
                                            </div>
                                            <div class="bg-white bg-opacity-25 p-1 rounded-circle">
                                                <i class="fas fa-percentage fa-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- إجمالي الغرامات -->
                            <div class="col-md-1-5">
                                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);">
                                    <div class="card-body p-1 text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 opacity-75" style="font-size: 0.65rem;">إجمالي الغرامات</h6>
                                                <h6 class="mb-0 fw-bold" style="font-size: 0.75rem;">{{ number_format($statistics['totalPenalties'], 2) }}</h6>
                                                <small class="opacity-75" style="font-size: 0.6rem;">ريال سعودي</small>
                                            </div>
                                            <div class="bg-white bg-opacity-25 p-1 rounded-circle">
                                                <i class="fas fa-exclamation-triangle fa-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- صافي قيمة المستخلصات -->
                            <div class="col-md-1-5">
                                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                    <div class="card-body p-1 text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 opacity-75" style="font-size: 0.65rem;">صافي قيمة المستخلصات</h6>
                                                <h6 class="mb-0 fw-bold" style="font-size: 0.75rem;">{{ number_format($statistics['totalNetExtractValue'], 2) }}</h6>
                                                <small class="opacity-75" style="font-size: 0.6rem;">ريال سعودي</small>
                                            </div>
                                            <div class="bg-white bg-opacity-25 p-1 rounded-circle">
                                                <i class="fas fa-calculator fa-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- إجمالي المدفوعات -->
                            <div class="col-md-1-5">
                                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);">
                                    <div class="card-body p-1 text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 opacity-75" style="font-size: 0.65rem;">إجمالي المدفوعات</h6>
                                                <h6 class="mb-0 fw-bold" style="font-size: 0.75rem;">{{ number_format($statistics['totalPaymentValue'], 2) }}</h6>
                                                <small class="opacity-75" style="font-size: 0.6rem;">ريال سعودي</small>
                                            </div>
                                            <div class="bg-white bg-opacity-25 p-1 rounded-circle">
                                                <i class="fas fa-hand-holding-usd fa-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- المبلغ المتبقي عند العميل شامل الضريبة -->
                            <div class="col-md-1-5">
                                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                                    <div class="card-body p-1 text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 opacity-75" style="font-size: 0.6rem;">المبلغ المتبقي عند العميل شامل الضريبة</h6>
                                                <h6 class="mb-0 fw-bold" style="font-size: 0.75rem;">{{ number_format($statistics['remainingAmount'], 2) }}</h6>
                                                <small class="opacity-75" style="font-size: 0.6rem;">ريال سعودي</small>
                                            </div>
                                            <div class="bg-white bg-opacity-25 p-1 rounded-circle">
                                                <i class="fas fa-hourglass-half fa-sm"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- منطقة الفلاتر -->
                    <div class="card mb-2 shadow-sm">
                        <div class="card-body p-2">
                            <!-- فترات زمنية سريعة -->
                            <div class="row g-1 mb-2">
                                <div class="col-12">
                                    <label class="form-label mb-1" style="font-size: 0.75rem; font-weight: 600;">
                                        <i class="fas fa-clock me-1 text-primary" style="font-size: 0.7rem;"></i>
                                        فترات زمنية سريعة
                                    </label>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateRange('today')" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                                            <i class="fas fa-calendar-day me-1" style="font-size: 0.65rem;"></i>
                                            اليوم
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateRange('week')" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                                            <i class="fas fa-calendar-week me-1" style="font-size: 0.65rem;"></i>
                                            أسبوع
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateRange('month')" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                                            <i class="fas fa-calendar-alt me-1" style="font-size: 0.65rem;"></i>
                                            شهر
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateRange('quarter')" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                                            <i class="fas fa-calendar me-1" style="font-size: 0.65rem;"></i>
                                            ربع سنة
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateRange('half')" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                                            <i class="fas fa-calendar-check me-1" style="font-size: 0.65rem;"></i>
                                            نصف سنة
                                        </button>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="setQuickDateRange('year')" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                                            <i class="fas fa-calendar-plus me-1" style="font-size: 0.65rem;"></i>
                                            سنة
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row g-2 align-items-end">
                                <div class="col-md-1">
                                    <label for="filter_start_date" class="form-label mb-0" style="font-size: 0.75rem;">
                                        <i class="fas fa-calendar-alt me-1 text-primary" style="font-size: 0.7rem;"></i>
                                        تاريخ البداية
                                    </label>
                                    <input type="date" class="form-control form-control-sm" id="filter_start_date" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                </div>
                                
                                <div class="col-md-1">
                                    <label for="filter_end_date" class="form-label mb-0" style="font-size: 0.75rem;">
                                        <i class="fas fa-calendar-check me-1 text-primary" style="font-size: 0.7rem;"></i>
                                        تاريخ النهاية
                                    </label>
                                    <input type="date" class="form-control form-control-sm" id="filter_end_date" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                </div>
                                
                                <div class="col-md-1">
                                    <label for="filter_office" class="form-label mb-0" style="font-size: 0.75rem;">
                                        <i class="fas fa-building me-1 text-primary" style="font-size: 0.7rem;"></i>
                                        المكتب
                                    </label>
                                    <select class="form-select form-select-sm" id="filter_office" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                        <option value="">الكل</option>
                                        @if(isset($project) && $project == 'madinah')
                                            <option value="مكتب المدينة المنورة">مكتب المدينة المنورة</option>
                                            <option value="ينبع">ينبع</option>
                                            <option value="خيبر">خيبر</option>
                                            <option value="مهد الذهب">مهد الذهب</option>
                                            <option value="بدر">بدر</option>
                                            <option value="الحناكية">الحناكية</option>
                                            <option value="العلا">العلا</option>
                                        @else
                                            <option value="خريص">خريص</option>
                                            <option value="الشرق">الشرق</option>
                                            <option value="الشمال">الشمال</option>
                                            <option value="الدرعية">الدرعية</option>
                                            <option value="الجنوب">الجنوب</option>
                                        @endif
                                    </select>
                                </div>
                                
                                <div class="col-md-1">
                                    <label for="filter_payment_status" class="form-label mb-0" style="font-size: 0.75rem;">
                                        <i class="fas fa-money-check-alt me-1 text-primary" style="font-size: 0.7rem;"></i>
                                        حالة الصرف
                                    </label>
                                    <select class="form-select form-select-sm" id="filter_payment_status" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                        
                                        <option value="مدفوع">مدفوع</option>
                                        <option value="غير مدفوع">غير مدفوع</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-1">
                                    <label for="filter_date_order" class="form-label mb-0" style="font-size: 0.75rem;">
                                        <i class="fas fa-sort me-1 text-primary" style="font-size: 0.7rem;"></i>
                                        ترتيب التاريخ
                                    </label>
                                    <select class="form-select form-select-sm" id="filter_date_order" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                        <option value="">الافتراضي</option>
                                        <option value="asc">من الأقدم للأحدث</option>
                                        <option value="desc">من الأحدث للأقدم</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <label for="filter_extract_number" class="form-label mb-0" style="font-size: 0.75rem;">
                                        <i class="fas fa-hashtag me-1 text-primary" style="font-size: 0.7rem;"></i>
                                        رقم المستخلص
                                    </label>
                                    <input type="number" class="form-control form-control-sm" id="filter_extract_number" placeholder="رقم المستخلص" style="font-size: 0.7rem; padding: 0.25rem 0.5rem;">
                                </div>
                                
                                <div class="col-md-1">
                                    <label for="filter_payment_type" class="form-label mb-0" style="font-size: 0.75rem;">
                                        <i class="fas fa-map-marker-alt me-1 text-primary" style="font-size: 0.7rem;"></i>
                                        موقف المستخلص
                                    </label>
                                    <select class="form-select form-select-sm" id="filter_payment_type" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                        <option value="">الكل</option>
                                        <option value="المقاول">المقاول</option>
                                        <option value="ادارة الكهرباء">ادارة الكهرباء</option>
                                        <option value="المالية">المالية</option>
                                        <option value="الخزينة">الخزينة</option>
                                        <option value="تم الصرف">تم الصرف</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-1">
                                    <label for="filter_tax_percentage" class="form-label mb-0" style="font-size: 0.75rem;">
                                        <i class="fas fa-percent me-1 text-primary" style="font-size: 0.7rem;"></i>
                                        جهة المستخلص
                                    </label>
                                    <select class="form-select form-select-sm" id="filter_tax_percentage" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                        <option value="">الكل</option>
                                        <option value="UDS">UDS</option>
                                        <option value="SAP">SAP</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary btn-sm w-100" onclick="applyFilters()" style="font-size: 0.75rem; padding: 0.35rem 0.5rem; margin-bottom: 2px;">
                                        <i class="fas fa-filter me-1" style="font-size: 0.7rem;"></i>
                                        تطبيق الفلاتر
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="clearFilters()" style="font-size: 0.75rem; padding: 0.35rem 0.5rem;">
                                        <i class="fas fa-times me-1" style="font-size: 0.7rem;"></i>
                                        مسح الفلاتر
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- عدد النتائج وعناصر التحكم -->
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light border-bottom">
                        <div class="text-muted">
                            <i class="fas fa-list me-1"></i>
                            عدد السجلات: <span id="recordCount">{{ $revenues->count() }}</span>
                        </div>
                        
                        <div class="d-flex gap-2">
                            @if(auth()->user()->hasPermission($project . '_manage_revenues') || auth()->user()->isAdmin())
                            <!-- زر استيراد Excel -->
                            <div class="position-relative">
                                <input type="file" id="excelImport" accept=".xlsx,.xls" style="display: none;" onchange="importExcel(this)">
                                <button type="button" class="btn btn-info btn-sm" onclick="document.getElementById('excelImport').click()">
                                    <i class="fas fa-file-import me-1"></i>
                                    استيراد Excel
                                </button>
                            </div>
                            
                            <!-- زر تصدير Excel -->
                            <button type="button" class="btn btn-warning btn-sm" onclick="exportToExcel()">
                                <i class="fas fa-file-export me-1"></i>
                                تصدير Excel
                            </button>
                            
                            <!-- زر إضافة صف جديد -->
                            <button type="button" class="btn btn-success btn-sm" onclick="addNewRow()">
                                <i class="fas fa-plus me-1"></i>
                                إضافة صف جديد
                            </button>
                            @endif
                            
                            @if(auth()->user()->isAdmin())
                            <!-- زر مسح جميع الصفوف -->
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteAllRows()">
                                <i class="fas fa-trash-alt me-1"></i>
                                مسح جميع الصفوف
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- الجدول -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="serial-col">#</th>
                                    <th>اسم العميل/<br>الجهة المالكة</th>
                                    <th>المشروع/<br>المنطقة</th>
                                    <th>رقم العقد</th>
                                    <th>رقم المستخلص</th>
                                    <th>المكتب</th>
                                    <th>نوع المستخلص</th>
                                    <th>رقم PO</th>
                                    <th>رقم الفاتورة</th>
                                    <th>إجمالي قيمة المستخلصات<br>غير شامله الضريبه</th>
                                    <th>جهة المستخلص</th>
                                    <th>قيمة الضريبة</th>
                                    <th>الغرامات</th>
                                    <th>ضريبة الدفعة الأولى</th>
                                    <th>صافي قيمة المستخلص</th>
                                    <th class="date-col">تاريخ إعداد المستخلص</th>
                                    <th>العام</th>
                                    <th>موقف المستخلص<br>عند ...</th>
                                    <th>الرقم المرجعي</th>
                                    <th class="date-col">تاريخ الصرف</th>
                                    <th>قيمة الصرف</th>
                                    <th>حالة الصرف</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="revenuesTableBody">
                                @if($revenues && $revenues->count() > 0)
                                    @foreach($revenues as $index => $revenue)
                                    <tr data-row-id="{{ $loop->iteration }}" data-revenue-id="{{ $revenue->id }}">
                                        <td class="serial-col">
                                            <span class="badge bg-primary">{{ $loop->iteration }}</span>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="client_name" placeholder="اسم العميل">{{ $revenue->client_name }}</div>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="project_area" placeholder="المشروع">{{ $revenue->project_area }}</div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" data-field="contract_number" placeholder="رقم العقد" value="{{ $revenue->contract_number }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" data-field="extract_number" placeholder="رقم المستخلص" value="{{ $revenue->extract_number }}">
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="office" placeholder="المكتب">{{ $revenue->office }}</div>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="extract_type" placeholder="نوع المستخلص">{{ $revenue->extract_type }}</div>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="po_number" placeholder="رقم PO">{{ $revenue->po_number }}</div>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="invoice_number" placeholder="رقم الفاتورة">{{ $revenue->invoice_number }}</div>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control" data-field="extract_value" placeholder="إجمالي قيمة المستخلصات" value="{{ $revenue->extract_value }}">
                                        </td>
                                        <td>
                                            <select class="form-control" data-field="tax_percentage">
                                                <option value="UDS" {{ $revenue->tax_percentage === 'UDS' ? 'selected' : '' }}>UDS</option>
                                                <option value="SAP" {{ $revenue->tax_percentage === 'SAP' ? 'selected' : '' }}>SAP</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="false" data-field="tax_value" placeholder="قيمة الضريبة" style="background-color: #fef3c7; font-weight: bold; cursor: not-allowed;" title="محسوب تلقائياً: قيمة المستخلص × 15%">{{ $revenue->tax_value }}</div>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control" data-field="penalties" placeholder="الغرامات" value="{{ $revenue->penalties }}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control" data-field="first_payment_tax" placeholder="ضريبة الدفعة الأولى" value="{{ $revenue->first_payment_tax }}">
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="false" data-field="net_extract_value" placeholder="صافي قيمة المستخلص" style="background-color: #fef3c7; font-weight: bold; cursor: not-allowed;" title="محسوب تلقائياً: قيمة المستخلص + الضريبة - الغرامات">{{ $revenue->net_extract_value }}</div>
                                        </td>
                                        <td class="date-col">
                                            <input type="date" class="date-input" data-field="extract_date" value="{{ $revenue->extract_date ? $revenue->extract_date->format('Y-m-d') : '' }}" placeholder="تاريخ الإعداد">
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="year" placeholder="العام">{{ $revenue->year }}</div>
                                        </td>
                                        <td>
                                            <select class="form-control payment-type-select" data-field="payment_type">
                                                <option value="المقاول" {{ $revenue->payment_type == 'المقاول' ? 'selected' : '' }}>المقاول</option>
                                                <option value="ادارة الكهرباء" {{ $revenue->payment_type == 'ادارة الكهرباء' ? 'selected' : '' }}>ادارة الكهرباء</option>
                                                <option value="المالية" {{ $revenue->payment_type == 'المالية' ? 'selected' : '' }}>المالية</option>
                                                <option value="الخزينة" {{ $revenue->payment_type == 'الخزينة' ? 'selected' : '' }}>الخزينة</option>
                                                <option value="تم الصرف" {{ $revenue->payment_type == 'تم الصرف' ? 'selected' : '' }}>تم الصرف</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" data-field="reference_number" placeholder="الرقم المرجعي" value="{{ $revenue->reference_number }}">
                                        </td>
                                        <td class="date-col">
                                            <input type="date" class="date-input" data-field="payment_date" value="{{ $revenue->payment_date ? $revenue->payment_date->format('Y-m-d') : '' }}" placeholder="تاريخ الصرف">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control" data-field="payment_value" placeholder="قيمة الصرف" value="{{ $revenue->payment_value }}">
                                        </td>
                                        <td>
                                            <select class="form-control" data-field="extract_status">
                                                <option value="مدفوع" {{ $revenue->extract_status == 'مدفوع' ? 'selected' : '' }}>مدفوع</option>
                                                <option value="غير مدفوع" {{ $revenue->extract_status == 'غير مدفوع' ? 'selected' : '' }}>غير مدفوع</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="action-buttons d-flex gap-1">
                                                @if($revenue->attachment_path)
                                                <a href="{{ asset('storage/' . $revenue->attachment_path) }}" target="_blank" class="btn btn-info btn-sm" title="عرض المرفق" style="padding: 0.25rem 0.4rem;">
                                                    <i class="fas fa-eye" style="font-size: 0.8rem;"></i>
                                                </a>
                                                @endif
                                                <button type="button" class="btn btn-success btn-sm" onclick="triggerFileUpload({{ $loop->iteration }})" title="رفع مرفق" style="padding: 0.25rem 0.4rem;">
                                                    <i class="fas fa-paperclip" style="font-size: 0.8rem;"></i>
                                                </button>
                                                <input type="file" id="fileInput{{ $loop->iteration }}" style="display: none;" onchange="uploadAttachment({{ $loop->iteration }}, {{ $revenue->id }})" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                                                @if(auth()->user()->isAdmin())
                                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow({{ $loop->iteration }})" title="حذف" style="padding: 0.25rem 0.4rem;">
                                                    <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr id="emptyRow">
                                        <td colspan="23" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-plus fa-3x mb-3"></i>
                                                <h5>لا توجد بيانات</h5>
                                                <p>اضغط على "إضافة صف جديد" لبدء إدخال البيانات</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Revenues page loaded');
    
    // إضافة event listeners للصفوف الموجودة
    const existingRows = document.querySelectorAll('tr[data-row-id]');
    existingRows.forEach(row => {
        addEditableFieldListeners(row);
        // حساب صافي قيمة المستخلص للصفوف الموجودة
        calculateNetExtractValue(row);
    });
    
    // تحديث عداد الصفوف
    updateRowCounter();
    
    console.log('Event listeners attached to existing rows:', existingRows.length);
});

let rowCounter = 0;
const isAdmin = {{ auth()->user()->isAdmin() ? 'true' : 'false' }};

// تحديث عداد الصفوف
function updateRowCounter() {
    const tbody = document.getElementById('revenuesTableBody');
    const dataRows = tbody.querySelectorAll('tr[data-row-id]');
    
    if (dataRows.length > 0) {
        rowCounter = dataRows.length;
    }
    
    // تحديث عداد السجلات في الواجهة
    const recordCountElement = document.getElementById('recordCount');
    if (recordCountElement) {
        recordCountElement.textContent = dataRows.length;
    }
}

// إضافة صف جديد
function addNewRow() {
    const tbody = document.getElementById('revenuesTableBody');
    
    // إخفاء رسالة "لا توجد بيانات" إذا كانت موجودة
    const emptyRow = document.getElementById('emptyRow');
    if (emptyRow) {
        emptyRow.remove();
    }
    
    rowCounter++;
    
    const newRow = document.createElement('tr');
    newRow.className = 'new-row';
    newRow.dataset.rowId = rowCounter;
    newRow.dataset.revenueId = 'null';
    
    newRow.innerHTML = `
        <td class="serial-col">
            <span class="badge bg-primary">${rowCounter}</span>
        </td>
        <td><div class="editable-field" contenteditable="true" data-field="client_name" placeholder="اسم العميل"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="project_area" placeholder="المشروع"></div></td>
        <td><input type="number" class="form-control" data-field="contract_number" placeholder="رقم العقد"></td>
        <td><input type="number" class="form-control" data-field="extract_number" placeholder="رقم المستخلص"></td>
        <td><div class="editable-field" contenteditable="true" data-field="office" placeholder="المكتب"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="extract_type" placeholder="نوع المستخلص"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="po_number" placeholder="رقم PO"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="invoice_number" placeholder="رقم الفاتورة"></div></td>
        <td><input type="number" step="0.01" class="form-control" data-field="extract_value" placeholder="إجمالي قيمة المستخلصات غير شامله الضريبه"></td>
        <td>
            <select class="form-control" data-field="tax_percentage">
                <option value="UDS">UDS</option>
                <option value="SAP">SAP</option>
            </select>
        </td>
        <td><div class="editable-field" contenteditable="false" data-field="tax_value" placeholder="قيمة الضريبة" style="background-color: #fef3c7; font-weight: bold; cursor: not-allowed;" title="محسوب تلقائياً: قيمة المستخلص × 15%"></div></td>
        <td><input type="number" step="0.01" class="form-control" data-field="penalties" placeholder="الغرامات"></td>
        <td><input type="number" step="0.01" class="form-control" data-field="first_payment_tax" placeholder="ضريبة الدفعة الأولى"></td>
        <td><div class="editable-field" contenteditable="false" data-field="net_extract_value" placeholder="صافي قيمة المستخلص" style="background-color: #fef3c7; font-weight: bold; cursor: not-allowed;" title="محسوب تلقائياً: قيمة المستخلص + الضريبة - الغرامات"></div></td>
        <td class="date-col"><input type="date" class="date-input" data-field="extract_date" placeholder="تاريخ الإعداد"></td>
        <td><div class="editable-field" contenteditable="true" data-field="year" placeholder="العام"></div></td>
        <td>
            <select class="form-control payment-type-select" data-field="payment_type">
                <option value="">اختر...</option>
                <option value="المقاول">المقاول</option>
                <option value="ادارة الكهرباء">ادارة الكهرباء</option>
                <option value="المالية">المالية</option>
                <option value="الخزينة">الخزينة</option>
                <option value="تم الصرف">تم الصرف</option>
            </select>
        </td>
        <td><input type="number" class="form-control" data-field="reference_number" placeholder="الرقم المرجعي"></td>
        <td class="date-col"><input type="date" class="date-input" data-field="payment_date" placeholder="تاريخ الصرف"></td>
        <td><input type="number" step="0.01" class="form-control" data-field="payment_value" placeholder="قيمة الصرف"></td>
        <td>
            <select class="form-control" data-field="extract_status">
                <option value="">اختر...</option>
                <option value="مدفوع">مدفوع</option>
                <option value="غير مدفوع">غير مدفوع</option>
            </select>
        </td>
        <td>
            <div class="action-buttons d-flex gap-1">
                <button type="button" class="btn btn-success btn-sm" onclick="triggerFileUpload(${rowCounter})" title="رفع مرفق" style="padding: 0.25rem 0.4rem;">
                    <i class="fas fa-paperclip" style="font-size: 0.8rem;"></i>
                </button>
                <input type="file" id="fileInput${rowCounter}" style="display: none;" onchange="uploadAttachment(${rowCounter}, null)" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                ${isAdmin ? `<button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(${rowCounter})" title="حذف" style="padding: 0.25rem 0.4rem;">
                    <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                </button>` : ''}
            </div>
        </td>
    `;
    
    tbody.appendChild(newRow);
    
    // إضافة event listeners للصف الجديد
    addEditableFieldListeners(newRow);
    
    // تحديث العداد
    updateRowCounter();
    
    console.log('Added new row:', rowCounter);
}

// إضافة event listeners للحقول القابلة للتحرير
function addEditableFieldListeners(row) {
    const editableFields = row.querySelectorAll('.editable-field');
    const dateInputs = row.querySelectorAll('.date-input');
    const selectFields = row.querySelectorAll('select[data-field]');
    const numberInputs = row.querySelectorAll('input[type="number"][data-field]');
    
    // إضافة listeners لحساب صافي قيمة المستخلص
    addCalculationListeners(row);
    
    // للحقول النصية القابلة للتحرير
    editableFields.forEach(field => {
        // حفظ تلقائي عند تغيير المحتوى
        field.addEventListener('input', function() {
            debounce(() => autoSaveRow(row), 1000)();
        });
        
        // حفظ عند فقدان التركيز
        field.addEventListener('blur', function() {
            autoSaveRow(row);
        });
    });

    // لحقول التاريخ
    dateInputs.forEach(dateInput => {
        // حفظ تلقائي عند تغيير التاريخ
        dateInput.addEventListener('change', function() {
            autoSaveRow(row);
        });
        
        // حفظ عند فقدان التركيز
        dateInput.addEventListener('blur', function() {
            autoSaveRow(row);
        });
    });
    
    // لحقول Select (مثل حالة الصرف وجهة المستخلص)
    selectFields.forEach(selectField => {
        // حفظ تلقائي عند تغيير القيمة
        selectField.addEventListener('change', function() {
            console.log('Select field changed:', selectField.dataset.field, 'Value:', selectField.value);
            
            // إذا تم اختيار "تم الصرف" في موقف المستخلص، غيّر حالة الصرف إلى "مدفوع" تلقائياً
            if (selectField.dataset.field === 'payment_type' && selectField.value === 'تم الصرف') {
                const extractStatusField = row.querySelector('select[data-field="extract_status"]');
                if (extractStatusField) {
                    extractStatusField.value = 'مدفوع';
                    console.log('Auto-changed extract_status to "مدفوع"');
                }
            }
            
            autoSaveRow(row);
        });
    });
    
    // لحقول Number (مثل قيمة المستخلص)
    numberInputs.forEach(numberInput => {
        // حفظ تلقائي عند تغيير القيمة
        numberInput.addEventListener('input', function() {
            debounce(() => autoSaveRow(row), 1000)();
        });
        
        // حفظ عند فقدان التركيز
        numberInput.addEventListener('blur', function() {
            autoSaveRow(row);
        });
    });
}

// Debounce function للحد من استدعاءات الحفظ
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// حفظ تلقائي للصف
function autoSaveRow(row) {
    if (!row || !row.dataset.rowId) return;
    
    // جمع البيانات من الصف
    const data = {
        @if(isset($project))
        project: '{{ $project }}',
        @endif
        client_name: row.querySelector('[data-field="client_name"]').textContent.trim(),
        project_area: row.querySelector('[data-field="project_area"]').textContent.trim(),
        contract_number: row.querySelector('[data-field="contract_number"]').value || row.querySelector('[data-field="contract_number"]').textContent.trim(),
        extract_number: row.querySelector('[data-field="extract_number"]').value || row.querySelector('[data-field="extract_number"]').textContent.trim(),
        office: row.querySelector('[data-field="office"]').textContent.trim(),
        extract_type: row.querySelector('[data-field="extract_type"]').textContent.trim(),
        po_number: row.querySelector('[data-field="po_number"]').textContent.trim(),
        invoice_number: row.querySelector('[data-field="invoice_number"]').textContent.trim(),
        extract_value: row.querySelector('[data-field="extract_value"]').value || '',
        tax_percentage: row.querySelector('[data-field="tax_percentage"]').value || '',
        tax_value: row.querySelector('[data-field="tax_value"]').textContent.trim(),
        penalties: row.querySelector('[data-field="penalties"]').value || '',
        first_payment_tax: row.querySelector('[data-field="first_payment_tax"]').value || row.querySelector('[data-field="first_payment_tax"]').textContent.trim(),
        net_extract_value: row.querySelector('[data-field="net_extract_value"]').textContent.trim(),
        extract_date: row.querySelector('[data-field="extract_date"]').value || '',
        year: row.querySelector('[data-field="year"]').textContent.trim(),
        payment_type: row.querySelector('[data-field="payment_type"]').value || '',
        reference_number: row.querySelector('[data-field="reference_number"]').value || row.querySelector('[data-field="reference_number"]').textContent.trim(),
        payment_date: row.querySelector('[data-field="payment_date"]').value || '',
        payment_value: row.querySelector('[data-field="payment_value"]').value || row.querySelector('[data-field="payment_value"]').textContent.trim(),
        extract_status: row.querySelector('[data-field="extract_status"]').value || row.querySelector('[data-field="extract_status"]').textContent.trim()
    };

    // التحقق من وجود بيانات قبل الحفظ
    const hasData = Object.values(data).some(value => value.length > 0);
    if (!hasData) return;

    // إضافة row_id للتحديث
    data.row_id = row.dataset.revenueId || null;

    console.log('Saving revenue data:', data);
    console.log('Tax Percentage (جهة المستخلص):', data.tax_percentage);

    // إظهار مؤشر الحفظ
    showSavingIndicator(row);
    
    // حفظ البيانات في قاعدة البيانات عبر AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        showErrorIndicator(row, 'خطأ في إعدادات الأمان');
        return;
    }

    fetch('{{ route("admin.work-orders.revenues.save") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        console.log('Response result:', result);
        if (result.success) {
            console.log('تم الحفظ التلقائي:', result.data);
            
            // تحديث row_id مع الـ ID الجديد من قاعدة البيانات
            row.dataset.revenueId = result.revenue_id;
            
            // إزالة كلاس الصف الجديد
            row.classList.remove('new-row');
            
            // إظهار مؤشر تم الحفظ
            showSavedIndicator(row, result.message || 'تم الحفظ');
        } else {
            console.error('خطأ في الحفظ:', result.message);
            showErrorIndicator(row, result.message || 'خطأ في الحفظ');
        }
    })
    .catch(error => {
        console.error('خطأ في الشبكة:', error);
        showErrorIndicator(row, 'خطأ في الاتصال بالخادم: ' + error.message);
    });
}

// إظهار مؤشر الحفظ
function showSavingIndicator(row) {
    let indicator = row.querySelector('.save-indicator');
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'save-indicator';
        row.style.position = 'relative';
        row.appendChild(indicator);
    }
    indicator.className = 'save-indicator saving';
}

// إظهار مؤشر تم الحفظ
function showSavedIndicator(row, message = 'تم الحفظ') {
    let indicator = row.querySelector('.save-indicator');
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'save-indicator';
        row.style.position = 'relative';
        row.appendChild(indicator);
    }
    indicator.className = 'save-indicator saved';
    
    // إخفاء المؤشر بعد ثانيتين
    setTimeout(() => {
        if (indicator) {
            indicator.remove();
        }
    }, 2000);
}

// إظهار مؤشر خطأ
function showErrorIndicator(row, message = 'خطأ في الحفظ') {
    let indicator = row.querySelector('.save-indicator');
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.className = 'save-indicator';
        row.style.position = 'relative';
        row.appendChild(indicator);
    }
    indicator.className = 'save-indicator error';
    
    console.error('Error saving row:', message);
    
    // إخفاء المؤشر بعد 3 ثوانٍ
    setTimeout(() => {
        if (indicator) {
            indicator.remove();
        }
    }, 3000);
}

// حذف جميع الصفوف
function deleteAllRows() {
    const tbody = document.getElementById('revenuesTableBody');
    const dataRows = tbody.querySelectorAll('tr:not(#emptyRow)');
    
    if (dataRows.length === 0) {
        alert('لا توجد صفوف لحذفها');
        return;
    }
    
    if (!confirm(`هل أنت متأكد من حذف جميع الصفوف (${dataRows.length} صف)؟\n\nهذا الإجراء لا يمكن التراجع عنه!`)) {
        return;
    }
    
    // تأكيد إضافي للأمان
    if (!confirm('تأكيد نهائي: هل تريد فعلاً حذف جميع البيانات؟')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('خطأ في إعدادات الأمان');
        return;
    }
    
    // إظهار loading
    const deleteBtn = event.target.closest('button');
    const originalText = deleteBtn.innerHTML;
    deleteBtn.disabled = true;
    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري الحذف...';
    
    // جمع IDs للصفوف المحفوظة في قاعدة البيانات
    const rowsToDelete = [];
    dataRows.forEach(row => {
        const revenueId = row.dataset.revenueId;
        if (revenueId && revenueId !== 'null') {
            rowsToDelete.push(revenueId);
        }
    });
    
    // إذا كان هناك صفوف محفوظة، احذفها من قاعدة البيانات
    if (rowsToDelete.length > 0) {
        // حذف كل صف على حدة
        let deletedCount = 0;
        let failedCount = 0;
        
        const deletePromises = rowsToDelete.map(revenueId => {
            return fetch(`{{ route("admin.work-orders.revenues.delete", ":id") }}`.replace(':id', revenueId), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    deletedCount++;
                } else {
                    failedCount++;
                }
            })
            .catch(error => {
                console.error('Error deleting row:', error);
                failedCount++;
            });
        });
        
        Promise.all(deletePromises).then(() => {
            // حذف جميع الصفوف من الجدول
            dataRows.forEach(row => row.remove());
            
            // إظهار رسالة "لا توجد بيانات"
            checkEmptyTable();
            
            // إعادة تعيين الزر
            deleteBtn.disabled = false;
            deleteBtn.innerHTML = originalText;
            
            // إظهار رسالة النجاح
            if (failedCount === 0) {
                alert(`تم حذف جميع الصفوف بنجاح (${deletedCount} صف)`);
            } else {
                alert(`تم حذف ${deletedCount} صف بنجاح\nفشل حذف ${failedCount} صف`);
            }
        });
    } else {
        // لا توجد صفوف محفوظة، فقط احذفها من الجدول
        dataRows.forEach(row => row.remove());
        checkEmptyTable();
        
        deleteBtn.disabled = false;
        deleteBtn.innerHTML = originalText;
        
        alert(`تم حذف جميع الصفوف (${dataRows.length} صف)`);
    }
}

// حذف صف
// دالة لفتح نافذة اختيار الملف
function triggerFileUpload(rowId) {
    const fileInput = document.getElementById(`fileInput${rowId}`);
    if (fileInput) {
        fileInput.click();
    }
}

// دالة لرفع المرفق
function uploadAttachment(rowId, revenueId) {
    const fileInput = document.getElementById(`fileInput${rowId}`);
    const file = fileInput.files[0];
    
    if (!file) return;
    
    // التحقق من حجم الملف (أقل من 10MB)
    if (file.size > 10 * 1024 * 1024) {
        alert('حجم الملف كبير جداً. الرجاء اختيار ملف أصغر من 10 ميجابايت.');
        fileInput.value = '';
        return;
    }
    
    const formData = new FormData();
    formData.append('attachment', file);
    if (revenueId) {
        formData.append('revenue_id', revenueId);
    }
    formData.append('row_id', rowId);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        alert('خطأ في إعدادات الأمان');
        return;
    }
    
    // عرض مؤشر التحميل
    const row = document.querySelector(`tr[data-row-id="${rowId}"]`);
    if (row) {
        const button = row.querySelector('.btn-success');
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 0.8rem;"></i>';
        }
    }
    
    // رفع الملف
    fetch('/admin/work-orders/revenues/upload-attachment', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم رفع المرفق بنجاح');
            
            // إضافة زر عرض المرفق
            if (row && data.file_path) {
                const actionButtons = row.querySelector('.action-buttons');
                if (actionButtons) {
                    // التحقق إذا كان زر العرض موجود بالفعل
                    let viewButton = actionButtons.querySelector('.btn-info');
                    if (!viewButton) {
                        // إنشاء زر جديد لعرض المرفق
                        viewButton = document.createElement('a');
                        viewButton.href = '/storage/' + data.file_path;
                        viewButton.target = '_blank';
                        viewButton.className = 'btn btn-info btn-sm';
                        viewButton.title = 'عرض المرفق';
                        viewButton.style.cssText = 'padding: 0.25rem 0.4rem;';
                        viewButton.innerHTML = '<i class="fas fa-eye" style="font-size: 0.8rem;"></i>';
                        
                        // إضافة الزر في البداية
                        actionButtons.insertBefore(viewButton, actionButtons.firstChild);
                    } else {
                        // تحديث رابط الزر الموجود
                        viewButton.href = '/storage/' + data.file_path;
                    }
                }
                
                // إعادة الزر لحالته الطبيعية
                const button = row.querySelector('.btn-success');
                if (button) {
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-paperclip" style="font-size: 0.8rem;"></i>';
                    button.title = 'تحديث المرفق';
                }
            }
        } else {
            alert('حدث خطأ أثناء رفع المرفق: ' + (data.message || 'خطأ غير معروف'));
            // إعادة الزر لحالته الطبيعية
            if (row) {
                const button = row.querySelector('.btn-success');
                if (button) {
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-paperclip" style="font-size: 0.8rem;"></i>';
                }
            }
        }
        // مسح input file
        fileInput.value = '';
    })
    .catch(error => {
        console.error('Error uploading attachment:', error);
        alert('حدث خطأ أثناء رفع المرفق');
        // إعادة الزر لحالته الطبيعية
        if (row) {
            const button = row.querySelector('.btn-success');
            if (button) {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-paperclip" style="font-size: 0.8rem;"></i>';
            }
        }
        fileInput.value = '';
    });
}

function deleteRow(rowId) {
    if (confirm('هل أنت متأكد من حذف هذا الصف؟')) {
        const row = document.querySelector(`tr[data-row-id="${rowId}"]`);
        if (!row) return;

        const revenueId = row.dataset.revenueId;
        
        // إذا كان السجل محفوظ في قاعدة البيانات، احذفه من هناك
        if (revenueId && revenueId !== 'null') {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                alert('خطأ في إعدادات الأمان');
                return;
            }

            fetch(`{{ route("admin.work-orders.revenues.delete", ":id") }}`.replace(':id', revenueId), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    row.remove();
                    reorderRows();
                    checkEmptyTable();
                    console.log('تم حذف السجل بنجاح');
                } else {
                    alert('خطأ في حذف السجل: ' + result.message);
                }
            })
            .catch(error => {
                console.error('خطأ في الشبكة:', error);
                alert('خطأ في الاتصال بالخادم');
            });
        } else {
            // حذف الصف مباشرة إذا لم يكن محفوظ
            row.remove();
            reorderRows();
            checkEmptyTable();
            console.log('تم حذف الصف بنجاح');
        }
    }
}

// إعادة ترقيم الصفوف
function reorderRows() {
    const tbody = document.getElementById('revenuesTableBody');
    const rows = tbody.querySelectorAll('tr[data-row-id]');
    
    rows.forEach((row, index) => {
        const serialNumber = index + 1;
        row.dataset.rowId = serialNumber;
        
        const badge = row.querySelector('.badge');
        if (badge) {
            badge.textContent = serialNumber;
        }
        
        // تحديث onclick للزر حذف
        const deleteBtn = row.querySelector('.btn-danger');
        if (deleteBtn) {
            deleteBtn.setAttribute('onclick', `deleteRow(${serialNumber})`);
        }
    });
    
    // تحديث العداد
    updateRowCounter();
}

// التحقق من الجدول الفارغ
function checkEmptyTable() {
    const tbody = document.getElementById('revenuesTableBody');
    const dataRows = tbody.querySelectorAll('tr[data-row-id]');
    
    if (dataRows.length === 0) {
        const emptyRow = document.createElement('tr');
        emptyRow.id = 'emptyRow';
        emptyRow.innerHTML = `
            <td colspan="23" class="text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-plus fa-3x mb-3"></i>
                    <h5>لا توجد بيانات</h5>
                    <p>اضغط على "إضافة صف جديد" لبدء إدخال البيانات</p>
                </div>
            </td>
        `;
        tbody.appendChild(emptyRow);
    }
}

// تصدير البيانات إلى Excel
function exportToExcel() {
    // إظهار مؤشر التحميل
    const loadingToast = document.createElement('div');
    loadingToast.innerHTML = `
        <div style="position: fixed; top: 20px; right: 20px; background: #0d6efd; color: white; padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10000; display: flex; align-items: center; gap: 10px;">
            <div class="spinner-border spinner-border-sm" role="status"></div>
            <span>جاري تصدير البيانات إلى Excel...</span>
        </div>
    `;
    document.body.appendChild(loadingToast);
    
    // تأخير بسيط للسماح بعرض المؤشر
    setTimeout(() => {
        const rows = document.querySelectorAll('#revenuesTableBody tr[data-revenue-id]');
        
        // Headers
        const headers = [
            '#',
            'اسم العميل/الجهة المالكة',
            'المشروع/المنطقة',
            'رقم العقد',
            'رقم المستخلص',
            'المكتب',
            'نوع المستخلص',
            'رقم PO',
            'رقم الفاتورة',
            'إجمالي قيمة المستخلصات غير شامله الضريبه',
            'جهة المستخلص',
            'قيمة الضريبة',
            'الغرامات',
            'ضريبة الدفعة الأولى',
            'صافي قيمة المستخلص',
            'تاريخ إعداد المستخلص',
            'العام',
            'موقف المستخلص',
            'الرقم المرجعي',
            'تاريخ الصرف',
            'قيمة الصرف',
            'حالة الصرف'
        ];
        
        // Data rows
        const data = [];
        rows.forEach((row, index) => {
            const rowData = [
                index + 1,
                getFieldValue(row, 'client_name'),
                getFieldValue(row, 'project_area'),
                getFieldValue(row, 'contract_number'),
                getFieldValue(row, 'extract_number'),
                getFieldValue(row, 'office'),
                getFieldValue(row, 'extract_type'),
                getFieldValue(row, 'po_number'),
                getFieldValue(row, 'invoice_number'),
                getFieldValue(row, 'extract_value'),
                getFieldValue(row, 'tax_percentage'),
                getFieldValue(row, 'tax_value'),
                getFieldValue(row, 'penalties'),
                getFieldValue(row, 'first_payment_tax'),
                getFieldValue(row, 'net_extract_value'),
                getFieldValue(row, 'extract_date'),
                getFieldValue(row, 'year'),
                getFieldValue(row, 'payment_type'),
                getFieldValue(row, 'reference_number'),
                getFieldValue(row, 'payment_date'),
                getFieldValue(row, 'payment_value'),
                getFieldValue(row, 'extract_status')
            ];
            data.push(rowData);
        });
        
        // Create workbook
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet([headers, ...data]);
        
        // تنسيق العرض التلقائي للأعمدة
        const colWidths = [
            { wch: 5 },   // #
            { wch: 25 },  // اسم العميل
            { wch: 25 },  // المشروع
            { wch: 15 },  // رقم العقد
            { wch: 15 },  // رقم المستخلص
            { wch: 15 },  // المكتب
            { wch: 20 },  // نوع المستخلص
            { wch: 15 },  // رقم PO
            { wch: 15 },  // رقم الفاتورة
            { wch: 20 },  // إجمالي القيمة
            { wch: 15 },  // جهة المستخلص
            { wch: 15 },  // قيمة الضريبة
            { wch: 15 },  // الغرامات
            { wch: 20 },  // ضريبة الدفعة الأولى
            { wch: 20 },  // صافي القيمة
            { wch: 18 },  // تاريخ الإعداد
            { wch: 10 },  // العام
            { wch: 20 },  // موقف المستخلص
            { wch: 15 },  // الرقم المرجعي
            { wch: 18 },  // تاريخ الصرف
            { wch: 15 },  // قيمة الصرف
            { wch: 15 }   // حالة الصرف
        ];
        ws['!cols'] = colWidths;
        
        // تنسيق الهيدر (الصف الأول)
        const range = XLSX.utils.decode_range(ws['!ref']);
        for (let C = range.s.c; C <= range.e.c; ++C) {
            const address = XLSX.utils.encode_col(C) + "1";
            if (!ws[address]) continue;
            ws[address].s = {
                font: { bold: true, color: { rgb: "FFFFFF" } },
                fill: { fgColor: { rgb: "4472C4" } },
                alignment: { horizontal: "center", vertical: "center", wrapText: true }
            };
        }
        
        XLSX.utils.book_append_sheet(wb, ws, "الإيرادات");
        
        // تنسيق اسم الملف بالتاريخ الحالي
        const today = new Date();
        const dateStr = today.getFullYear() + '-' + 
                       String(today.getMonth() + 1).padStart(2, '0') + '-' + 
                       String(today.getDate()).padStart(2, '0');
    
        XLSX.writeFile(wb, `revenues_${dateStr}.xlsx`);
        
        // إزالة مؤشر التحميل
        document.body.removeChild(loadingToast);
        
        // إظهار رسالة نجاح
        const successToast = document.createElement('div');
        successToast.innerHTML = `
            <div style="position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 15px 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10000; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-check-circle"></i>
                <span>تم تصدير ${rows.length} سجل بنجاح!</span>
            </div>
        `;
        document.body.appendChild(successToast);
        
        setTimeout(() => {
            document.body.removeChild(successToast);
        }, 3000);
        
        console.log('تم تصدير البيانات إلى Excel');
    }, 100);
}

// دالة مساعدة لقراءة قيمة الحقل
function getFieldValue(row, fieldName) {
    const field = row.querySelector(`[data-field="${fieldName}"]`);
    if (!field) return '';
    
    // إذا كان input أو select
    if (field.tagName === 'INPUT' || field.tagName === 'SELECT') {
        return field.value || '';
    }
    
    // إذا كان contenteditable div
    return field.textContent.trim() || '';
}

// استيراد البيانات من Excel
function importExcel(input) {
    const file = input.files[0];
    if (!file) return;
    
    // التحقق من نوع الملف
    const fileType = file.name.split('.').pop().toLowerCase();
    if (!['xlsx', 'xls', 'csv'].includes(fileType)) {
        alert('يرجى اختيار ملف Excel صحيح (.xlsx، .xls، أو .csv)');
        return;
    }
    
    // إظهار مؤشر التحميل
    const loadingIndicator = document.createElement('div');
    loadingIndicator.id = 'importLoadingIndicator';
    loadingIndicator.innerHTML = `
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; justify-content: center; align-items: center;">
            <div style="background: white; padding: 20px; border-radius: 10px; text-align: center;">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3 mb-0">جاري استيراد البيانات وحفظها في قاعدة البيانات...</p>
            </div>
        </div>
    `;
    document.body.appendChild(loadingIndicator);
    
    // إنشاء FormData لإرسال الملف
    const formData = new FormData();
    formData.append('file', file);
    
    // الحصول على CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        alert('خطأ في إعدادات الأمان');
        document.body.removeChild(loadingIndicator);
        return;
    }
    
    // إضافة معامل المشروع للـ FormData
    @if(isset($project))
    formData.append('project', '{{ $project }}');
    @endif
    
    // إرسال الملف للخادم للاستيراد والحفظ التلقائي
    console.log('Sending import request to:', '{{ route("admin.work-orders.revenues.import") }}');
    console.log('FormData contents:', Array.from(formData.entries()));
    
    fetch('{{ route("admin.work-orders.revenues.import") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        // إزالة مؤشر التحميل
        document.body.removeChild(loadingIndicator);
        
        if (result.success) {
            let message = result.message;
            if (result.imported_count) {
                message += '\n\nعدد السجلات المستوردة: ' + result.imported_count;
            }
            if (result.errors_count && result.errors_count > 0) {
                message += '\n\nعدد الأخطاء: ' + result.errors_count;
                if (result.errors && result.errors.length > 0) {
                    message += '\n\nتفاصيل الأخطاء: ' + result.errors.slice(0, 3).join('\n');
                    if (result.errors.length > 3) {
                        message += '\n... و ' + (result.errors.length - 3) + ' أخطاء أخرى';
                    }
                }
            }
            alert(message);
            
            // إعادة تحميل الصفحة لعرض البيانات المستوردة
            console.log('تم استيراد البيانات بنجاح، جاري إعادة تحميل الصفحة...');
            window.location.reload();
        } else {
            alert('خطأ في الاستيراد: ' + result.message);
        }
    })
    .catch(error => {
        // إزالة مؤشر التحميل
        if (document.getElementById('importLoadingIndicator')) {
            document.body.removeChild(document.getElementById('importLoadingIndicator'));
        }
        
        console.error('خطأ في الشبكة:', error);
        alert('حدث خطأ في الاتصال بالخادم: ' + error.message);
    });
    
    // إعادة تعيين قيمة input
    input.value = '';
}


// تنسيق التاريخ لحقل الإدخال
function formatDateForInput(dateValue) {
    if (!dateValue) return '';
    
    // إذا كان التاريخ من Excel (رقم تسلسلي)
    if (typeof dateValue === 'number') {
        const excelEpoch = new Date(1899, 11, 30);
        const date = new Date(excelEpoch.getTime() + dateValue * 24 * 60 * 60 * 1000);
        return date.toISOString().split('T')[0];
    }
    
    // إذا كان التاريخ نص
    if (typeof dateValue === 'string') {
        const date = new Date(dateValue);
        if (!isNaN(date.getTime())) {
            return date.toISOString().split('T')[0];
        }
    }
    
    return '';
}

// دوال الفلاتر
function applyFilters() {
    const startDate = document.getElementById('filter_start_date').value;
    const endDate = document.getElementById('filter_end_date').value;
    const office = document.getElementById('filter_office').value;
    const paymentStatus = document.getElementById('filter_payment_status').value;
    const dateOrder = document.getElementById('filter_date_order').value;
    const extractNumber = document.getElementById('filter_extract_number').value;
    const paymentType = document.getElementById('filter_payment_type').value;
    const taxPercentage = document.getElementById('filter_tax_percentage').value;
    
    const table = document.querySelector('.table tbody');
    let rows = Array.from(table.querySelectorAll('tr'));
    let visibleRows = [];
    
    rows.forEach(row => {
        let showRow = true;
        
        // فلتر المكتب
        if (office) {
            const officeCell = row.querySelector('[data-field="office"]');
            if (officeCell && officeCell.textContent.trim() !== office) {
                showRow = false;
            }
        }
        
        // فلتر حالة الصرف
        if (paymentStatus) {
            const statusCell = row.querySelector('[data-field="extract_status"]');
            if (statusCell) {
                const cellValue = statusCell.value || statusCell.textContent.trim();
                if (cellValue !== paymentStatus) {
                    showRow = false;
                }
            }
        }
        
        // فلتر رقم المستخلص
        if (extractNumber) {
            const extractNumberCell = row.querySelector('[data-field="extract_number"]');
            if (extractNumberCell) {
                const cellValue = extractNumberCell.textContent.trim().toLowerCase();
                if (!cellValue.includes(extractNumber.toLowerCase())) {
                    showRow = false;
                }
            }
        }
        
        // فلتر موقف المستخلص
        if (paymentType) {
            const paymentTypeCell = row.querySelector('[data-field="payment_type"]');
            if (paymentTypeCell) {
                const cellValue = paymentTypeCell.value || paymentTypeCell.textContent.trim();
                if (cellValue !== paymentType) {
                    showRow = false;
                }
            }
        }
        
        // فلتر جهة المستخلص (UDS / SAP)
        if (taxPercentage) {
            const taxPercentageCell = row.querySelector('[data-field="tax_percentage"]');
            if (taxPercentageCell) {
                const cellValue = taxPercentageCell.value || taxPercentageCell.textContent.trim();
                if (cellValue !== taxPercentage) {
                    showRow = false;
                }
            }
        }
        
        // فلتر التاريخ (باستخدام تاريخ إعداد المستخلص)
        if (startDate || endDate) {
            const dateCell = row.querySelector('[data-field="extract_date"]');
            if (dateCell) {
                const rowDate = dateCell.value || dateCell.textContent.trim();
                
                if (startDate && rowDate && rowDate < startDate) {
                    showRow = false;
                }
                if (endDate && rowDate && rowDate > endDate) {
                    showRow = false;
                }
            }
        }
        
        if (showRow) {
            visibleRows.push(row);
        } else {
            row.style.display = 'none';
        }
    });
    
    // ترتيب التاريخ
    if (dateOrder) {
        visibleRows.sort((a, b) => {
            const dateA = a.querySelector('[data-field="extract_date"]');
            const dateB = b.querySelector('[data-field="extract_date"]');
            const valueA = dateA ? (dateA.value || dateA.textContent.trim()) : '';
            const valueB = dateB ? (dateB.value || dateB.textContent.trim()) : '';
            
            if (!valueA) return 1;
            if (!valueB) return -1;
            
            if (dateOrder === 'asc') {
                return valueA.localeCompare(valueB);
            } else {
                return valueB.localeCompare(valueA);
            }
        });
        
        // إعادة ترتيب الصفوف في DOM
        visibleRows.forEach(row => {
            table.appendChild(row);
        });
    }
    
    // إظهار الصفوف المفلترة
    visibleRows.forEach(row => {
        row.style.display = '';
    });
    
    // تحديث عدد السجلات
    document.getElementById('recordCount').textContent = visibleRows.length;
}

// دالة لضبط الفترات الزمنية السريعة
function setQuickDateRange(period) {
    const today = new Date();
    const endDate = today.toISOString().split('T')[0];
    let startDate;
    
    switch(period) {
        case 'today':
            startDate = endDate;
            break;
        case 'week':
            const weekAgo = new Date(today);
            weekAgo.setDate(today.getDate() - 7);
            startDate = weekAgo.toISOString().split('T')[0];
            break;
        case 'month':
            const monthAgo = new Date(today);
            monthAgo.setMonth(today.getMonth() - 1);
            startDate = monthAgo.toISOString().split('T')[0];
            break;
        case 'quarter':
            const quarterAgo = new Date(today);
            quarterAgo.setMonth(today.getMonth() - 3);
            startDate = quarterAgo.toISOString().split('T')[0];
            break;
        case 'half':
            const halfYearAgo = new Date(today);
            halfYearAgo.setMonth(today.getMonth() - 6);
            startDate = halfYearAgo.toISOString().split('T')[0];
            break;
        case 'year':
            const yearAgo = new Date(today);
            yearAgo.setFullYear(today.getFullYear() - 1);
            startDate = yearAgo.toISOString().split('T')[0];
            break;
    }
    
    document.getElementById('filter_start_date').value = startDate;
    document.getElementById('filter_end_date').value = endDate;
}

function clearFilters() {
    // مسح قيم الفلاتر
    document.getElementById('filter_start_date').value = '';
    document.getElementById('filter_end_date').value = '';
    document.getElementById('filter_office').value = '';
    document.getElementById('filter_payment_status').value = '';
    document.getElementById('filter_date_order').value = '';
    document.getElementById('filter_extract_number').value = '';
    document.getElementById('filter_payment_type').value = '';
    document.getElementById('filter_tax_percentage').value = '';
    
    // إظهار جميع الصفوف
    const table = document.querySelector('.table tbody');
    const rows = table.querySelectorAll('tr');
    rows.forEach(row => {
        row.style.display = '';
    });
    
    // إعادة عدد السجلات للقيمة الأصلية
    document.getElementById('recordCount').textContent = rows.length;
}

// حساب قيمة الضريبة تلقائياً (15%)
// المعادلة: إجمالي قيمة المستخلصات × 0.15
function calculateTaxValue(row) {
    const extractValueInput = row.querySelector('input[data-field="extract_value"]');
    const taxValueField = row.querySelector('[data-field="tax_value"]');
    
    if (!extractValueInput || !taxValueField) {
        return;
    }
    
    // الحصول على قيمة المستخلص
    const extractValue = parseFloat(extractValueInput.value) || 0;
    
    // حساب قيمة الضريبة (15%)
    const taxValue = extractValue * 0.15;
    
    // تحديث الحقل فقط إذا تغيرت القيمة
    const currentValue = parseFloat(taxValueField.textContent.trim()) || 0;
    if (Math.abs(currentValue - taxValue) > 0.01) {
        taxValueField.textContent = taxValue.toFixed(2);
        
        // إضافة تأثير بصري للدلالة على أنه محسوب تلقائياً
        taxValueField.style.backgroundColor = '#fef3c7';
        taxValueField.style.fontWeight = 'bold';
        
        console.log('Tax value calculated (15%):', taxValue.toFixed(2));
    }
}

// حساب صافي قيمة المستخلص تلقائياً
// المعادلة: إجمالي قيمة المستخلصات + قيمة الضريبة - الغرامات
function calculateNetExtractValue(row) {
    const extractValueInput = row.querySelector('input[data-field="extract_value"]');
    const taxValueField = row.querySelector('[data-field="tax_value"]');
    const penaltiesField = row.querySelector('[data-field="penalties"]');
    const netExtractValueField = row.querySelector('[data-field="net_extract_value"]');
    
    if (!extractValueInput || !taxValueField || !penaltiesField || !netExtractValueField) {
        return;
    }
    
    // الحصول على القيم
    const extractValue = parseFloat(extractValueInput.value) || 0;
    const taxValue = parseFloat(taxValueField.textContent.trim()) || 0;
    const penalties = parseFloat(penaltiesField.value) || 0;
    
    // حساب صافي قيمة المستخلص
    const netValue = extractValue + taxValue - penalties;
    
    // تحديث الحقل فقط إذا تغيرت القيمة
    const currentValue = parseFloat(netExtractValueField.textContent.trim()) || 0;
    if (Math.abs(currentValue - netValue) > 0.01) {
        netExtractValueField.textContent = netValue.toFixed(2);
        
        // إضافة تأثير بصري للدلالة على أنه محسوب تلقائياً
        netExtractValueField.style.backgroundColor = '#fef3c7';
        netExtractValueField.style.fontWeight = 'bold';
        
        console.log('Net extract value calculated:', netValue.toFixed(2));
    }
}

// إضافة event listeners للحقول التي تؤثر في الحساب
function addCalculationListeners(row) {
    const extractValueInput = row.querySelector('input[data-field="extract_value"]');
    const taxValueField = row.querySelector('[data-field="tax_value"]');
    const penaltiesField = row.querySelector('[data-field="penalties"]');
    
    if (extractValueInput) {
        extractValueInput.addEventListener('input', function() {
            // حساب الضريبة أولاً (15%)
            calculateTaxValue(row);
            // ثم حساب صافي القيمة
            debounce(() => calculateNetExtractValue(row), 500)();
        });
    }
    
    if (taxValueField) {
        taxValueField.addEventListener('input', function() {
            debounce(() => calculateNetExtractValue(row), 500)();
        });
    }
    
    if (penaltiesField) {
        penaltiesField.addEventListener('input', function() {
            debounce(() => calculateNetExtractValue(row), 500)();
        });
    }
}

// ========== Enhanced Keyboard Navigation ==========
// تحسين التنقل بين الحقول باستخدام Enter
document.addEventListener('DOMContentLoaded', function() {
    const table = document.querySelector('#revenuesTableBody');
    if (!table) return;
    
    // Get all focusable elements in the table
    function getFocusableElements() {
        return Array.from(table.querySelectorAll('[contenteditable="true"], input, select'))
            .filter(el => !el.disabled && el.offsetParent !== null);
    }
    
    // Handle Enter key to move to next field
    table.addEventListener('keydown', function(e) {
        const target = e.target;
        
        // Check if it's an editable field or input
        if (target.matches('[contenteditable="true"], input, select')) {
            // Enter key (without Shift)
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                
                const focusableElements = getFocusableElements();
                const currentIndex = focusableElements.indexOf(target);
                
                if (currentIndex !== -1 && currentIndex < focusableElements.length - 1) {
                    const nextElement = focusableElements[currentIndex + 1];
                    nextElement.focus();
                    
                    // Select all text in the next field for easy replacement
                    if (nextElement.matches('[contenteditable="true"]')) {
                        const range = document.createRange();
                        range.selectNodeContents(nextElement);
                        const selection = window.getSelection();
                        selection.removeAllRanges();
                        selection.addRange(range);
                    } else if (nextElement.matches('input')) {
                        nextElement.select();
                    }
                }
            }
            
            // Shift+Enter to move to previous field
            if (e.key === 'Enter' && e.shiftKey) {
                e.preventDefault();
                
                const focusableElements = getFocusableElements();
                const currentIndex = focusableElements.indexOf(target);
                
                if (currentIndex > 0) {
                    const prevElement = focusableElements[currentIndex - 1];
                    prevElement.focus();
                    
                    // Select all text in the previous field
                    if (prevElement.matches('[contenteditable="true"]')) {
                        const range = document.createRange();
                        range.selectNodeContents(prevElement);
                        const selection = window.getSelection();
                        selection.removeAllRanges();
                        selection.addRange(range);
                    } else if (prevElement.matches('input')) {
                        prevElement.select();
                    }
                }
            }
        }
    });
    
    // Visual feedback on focus
    table.addEventListener('focus', function(e) {
        if (e.target.matches('[contenteditable="true"], input, select')) {
            e.target.classList.add('field-focused');
        }
    }, true);
    
    table.addEventListener('blur', function(e) {
        if (e.target.matches('[contenteditable="true"], input, select')) {
            e.target.classList.remove('field-focused');
        }
    }, true);
    
    // Add visual feedback classes for auto-save
    const originalAutoSave = window.autoSaveRevenue;
    if (originalAutoSave) {
        window.autoSaveRevenue = function(field, revenueId) {
            field.classList.add('saving');
            field.classList.remove('saved', 'error-field');
            
            originalAutoSave(field, revenueId).then(() => {
                field.classList.remove('saving');
                field.classList.add('saved');
                setTimeout(() => field.classList.remove('saved'), 1500);
            }).catch(() => {
                field.classList.remove('saving');
                field.classList.add('error-field');
                setTimeout(() => field.classList.remove('error-field'), 2000);
            });
        };
    }
    
    // Double-click to select all text in editable fields
    table.addEventListener('dblclick', function(e) {
        if (e.target.matches('[contenteditable="true"]')) {
            const range = document.createRange();
            range.selectNodeContents(e.target);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        } else if (e.target.matches('input')) {
            e.target.select();
        }
    });
    
    // Escape key to blur (lose focus) from current field
    table.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (e.target.matches('[contenteditable="true"], input, select')) {
                e.target.blur();
            }
        }
    });
    
    // Console log for debugging
    console.log('Enhanced Revenue Table Navigation Loaded ✓');
    console.log('Shortcuts:');
    console.log('- Enter: Move to next field');
    console.log('- Shift+Enter: Move to previous field');
    console.log('- Tab: Standard tab navigation');
    console.log('- Escape: Blur current field');
    console.log('- Double-click: Select all text');
});
</script>

<!-- إضافة مكتبة SheetJS لمعالجة Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

@endsection