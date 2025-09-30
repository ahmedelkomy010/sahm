@extends('layouts.app')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
<!-- SheetJS for Excel support -->
<script src="https://unpkg.com/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
@endpush

@push('styles')
<style>
    .revenues-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid #e2e8f0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .revenues-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #059669);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .revenues-card:hover::before {
        transform: scaleX(1);
    }
    
    .revenues-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        border-color: #10b981;
    }
    
    .header-gradient {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        position: relative;
        overflow: hidden;
    }
    
    .header-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        opacity: 0.3;
    }
    
    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }
    
    .table-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 2px solid #e2e8f0;
    }
    
    .table-row {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .table-row:hover {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        transform: translateX(4px);
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .status-paid {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-overdue {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .status-partial {
        background: #ddd6fe;
        color: #5b21b6;
    }
    
    .status-processing {
        background: #e0f2fe;
        color: #0c4a6e;
    }
    
    .status-cancelled {
        background: #f3f4f6;
        color: #374151;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
    }
    
    .btn-secondary {
        background: white;
        color: #374151;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s ease;
        border: 1px solid #d1d5db;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #10b981;
        color: #10b981;
    }
    
    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        margin: 0 2px;
    }
    
    .action-btn:hover {
        transform: translateY(-1px);
    }
    
    .btn-edit {
        background: #fef3c7;
        color: #92400e;
    }
    
    .btn-edit:hover {
        background: #fcd34d;
    }
    
    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .btn-delete:hover {
        background: #fca5a5;
    }
    
    .btn-view {
        background: #dbeafe;
        color: #1d4ed8;
    }
    
    .btn-view:hover {
        background: #93c5fd;
    }
    
    .summary-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--card-color);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .summary-card:hover::before {
        transform: scaleX(1);
    }
    
    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    /* Container Improvements */
    .revenues-main-container {
        width: 100vw;
        max-width: none;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .revenues-table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .revenues-table {
        min-width: 1800px;
        width: 100%;
        table-layout: fixed;
    }

    /* Column Widths */
    .col-index { width: 50px; }
    .col-client { width: 140px; }
    .col-project { width: 140px; }
    .col-contract { width: 100px; }
    .col-extract { width: 100px; }
    .col-office { width: 120px; }
    .col-type { width: 80px; }
    .col-po { width: 80px; }
    .col-invoice { width: 80px; }
    .col-value { width: 120px; }
    .col-tax-pct { width: 70px; }
    .col-tax-val { width: 100px; }
    .col-penalties { width: 100px; }
    .col-first-tax { width: 100px; }
    .col-net { width: 120px; }
    .col-date { width: 100px; }
    .col-year { width: 60px; }
    .col-ref { width: 100px; }
    .col-pay-date { width: 100px; }
    .col-pay-val { width: 120px; }
    .col-status { width: 100px; }
    .col-actions { width: 140px; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-100 py-12">
    <div class="w-full max-w-none mx-auto px-2 sm:px-4 lg:px-6">
        
        <!-- Back Button -->
        <div class="mb-8 flex justify-start">
            <a href="{{ route('projects.show', $project) }}" 
               class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-gray-200 text-gray-700 rounded-xl font-semibold shadow-lg hover:bg-white hover:shadow-xl transition-all duration-300 hover:transform hover:scale-105">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
                العودة للمشروع
            </a>
        </div>
        
        <!-- Header -->
        <div class="header-gradient rounded-3xl shadow-2xl overflow-hidden mb-12 relative">
            <div class="relative z-10 p-8 text-left text-white">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold mb-3">إدارة الإيرادات</h1>
                    <p class="text-green-100 text-lg">{{ $project->name }} - رقم العقد: {{ $project->contract_number }}</p>
                </div>
            </div>
        </div>

        <!-- Financial Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            
            <!-- Total Contract Value -->
            <div class="summary-card" style="--card-color: #10b981;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v2a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($totalValue ?? 0, 0) }} ريال</div>
                        <div class="text-sm text-gray-600">قيمة العقد</div>
                    </div>
                </div>
            </div>

            <!-- Invoiced Amount -->
            <div class="summary-card" style="--card-color: #3b82f6;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($totalValue ?? 0, 0) }} ريال</div>
                        <div class="text-sm text-gray-600">المفوتر</div>
                    </div>
                </div>
            </div>

            <!-- Received Amount -->
            <div class="summary-card" style="--card-color: #059669;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-emerald-600">{{ number_format($paidValue ?? 0, 0) }} ريال</div>
                        <div class="text-sm text-gray-600">المحصل</div>
                    </div>
                </div>
            </div>

            <!-- Outstanding Amount -->
            <div class="summary-card" style="--card-color: #f59e0b;">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-yellow-600">{{ number_format($pendingValue ?? 0, 0) }} ريال</div>
                        <div class="text-sm text-gray-600">المتبقي</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center mb-8">
            <div class="text-gray-600 text-sm">
                عدد السجلات: <span class="font-semibold text-gray-900">{{ $totalRevenues ?? 0 }}</span>
            </div>
            <div class="flex space-x-4">
                <button id="import-btn" class="btn-secondary" onclick="importExcelSimple()" title="استيراد ملف CSV">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                    </svg>
                    استيراد CSV
                </button>
                <button id="export-btn" class="btn-secondary" onclick="exportExcelSimple()" title="تصدير البيانات إلى ملف CSV">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    تصدير CSV
                </button>
                <button class="btn-primary" onclick="openAddModal()" title="إضافة صف جديد">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    إضافة صف جديد
                </button>
            </div>
        </div>

        <!-- Revenue Management Table -->
        <div class="w-full max-w-none">
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 text-right">إدارة الإيرادات</h2>
                </div>
            </div>
            
            <div class="overflow-x-auto bg-white rounded-lg shadow-lg border border-gray-200" style="width: 100%; max-width: none;">
                <table class="w-full divide-y divide-gray-200" style="min-width: 1600px; table-layout: fixed;">
                    <thead class="bg-gradient-to-r from-blue-50 to-indigo-50">
                        <tr>
                            <th class="col-index px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">#</th>
                            <th class="col-client px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">العميل</th>
                            <th class="col-project px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">المشروع</th>
                            <th class="col-contract px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">رقم العقد</th>
                            <th class="col-extract px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">رقم المستخلص</th>
                            <th class="col-office px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">المكتب</th>
                            <th class="col-type px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">النوع</th>
                            <th class="col-po px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">PO</th>
                            <th class="col-invoice px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الفاتورة</th>
                            <th class="col-value px-2 py-4 text-right text-xs font-semibold text-green-700 uppercase tracking-wider">قيمة المستخلص</th>
                            <th class="col-tax-pct px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الضريبة%</th>
                            <th class="col-tax-val px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">قيمة الضريبة</th>
                            <th class="col-penalties px-2 py-4 text-right text-xs font-semibold text-red-700 uppercase tracking-wider">الغرامات</th>
                            <th class="col-first-tax px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">ضريبة أولى</th>
                            <th class="col-net px-2 py-4 text-right text-xs font-semibold text-blue-700 uppercase tracking-wider">صافي القيمة</th>
                            <th class="col-date px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">تاريخ الإعداد</th>
                            <th class="col-year px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">السنة</th>
                            <th class="col-ref px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">المرجع</th>
                            <th class="col-pay-date px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">تاريخ الصرف</th>
                            <th class="col-pay-val px-2 py-4 text-right text-xs font-semibold text-green-700 uppercase tracking-wider">قيمة الصرف</th>
                            <th class="col-status px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الحالة</th>
                            <th class="col-actions px-2 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="revenueTableBody">
                        @forelse($revenues as $index => $revenue)
                        <tr class="table-row hover:bg-gray-50 transition-colors duration-150" data-revenue-id="{{ $revenue->id }}">
                            <td class="col-index px-2 py-3 text-sm font-medium text-gray-900 text-right">{{ $index + 1 }}</td>
                            <td class="col-client px-2 py-3 text-sm text-gray-900 text-right truncate" title="{{ $revenue->client_name }}">{{ $revenue->client_name }}</td>
                            <td class="col-project px-2 py-3 text-sm text-gray-900 text-right truncate" title="{{ $revenue->project_area }}">{{ $revenue->project_area }}</td>
                            <td class="col-contract px-2 py-3 text-sm text-gray-900 text-right font-mono">{{ $revenue->contract_number }}</td>
                            <td class="col-extract px-2 py-3 text-sm text-gray-900 text-right font-mono">{{ $revenue->extract_number }}</td>
                            <td class="col-office px-2 py-3 text-sm text-gray-900 text-right truncate" title="{{ $revenue->office }}">{{ $revenue->office }}</td>
                            <td class="col-type px-2 py-3 text-sm text-gray-900 text-right truncate" title="{{ $revenue->extract_type }}">{{ $revenue->extract_type }}</td>
                            <td class="col-po px-2 py-3 text-sm text-gray-900 text-right font-mono">{{ $revenue->po_number ?: '-' }}</td>
                            <td class="col-invoice px-2 py-3 text-sm text-gray-900 text-right font-mono">{{ $revenue->invoice_number ?: '-' }}</td>
                            <td class="col-value px-2 py-3 text-sm font-semibold text-green-600 text-right">{{ number_format($revenue->extract_value, 0) }}</td>
                            <td class="col-tax-pct px-2 py-3 text-sm text-gray-900 text-right">{{ $revenue->tax_percentage }}%</td>
                            <td class="col-tax-val px-2 py-3 text-sm text-gray-900 text-right">{{ number_format($revenue->tax_value, 0) }}</td>
                            <td class="col-penalties px-2 py-3 text-sm text-red-600 text-right">{{ number_format($revenue->penalties, 0) }}</td>
                            <td class="col-first-tax px-2 py-3 text-sm text-gray-900 text-right">{{ number_format($revenue->first_payment_tax, 0) }}</td>
                            <td class="col-net px-2 py-3 text-sm font-semibold text-blue-600 text-right">{{ number_format($revenue->net_extract_value, 0) }}</td>
                            <td class="col-date px-2 py-3 text-sm text-gray-900 text-right">{{ $revenue->extract_date ? $revenue->extract_date->format('Y/m/d') : '-' }}</td>
                            <td class="col-year px-2 py-3 text-sm text-gray-900 text-right">{{ $revenue->year }}</td>
                            <td class="col-ref px-2 py-3 text-sm text-gray-900 text-right font-mono">{{ $revenue->reference_number ?: '-' }}</td>
                            <td class="col-pay-date px-2 py-3 text-sm text-gray-900 text-right">{{ $revenue->payment_date ? $revenue->payment_date->format('Y/m/d') : '-' }}</td>
                            <td class="col-pay-val px-2 py-3 text-sm font-semibold text-green-600 text-right">{{ $revenue->payment_value ? number_format($revenue->payment_value, 0) : '-' }}</td>
                            <td class="col-status px-2 py-3 text-sm text-gray-900 text-right">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $revenue->extract_status == 'مكتمل' ? 'bg-green-100 text-green-800' : 
                                       ($revenue->extract_status == 'معلق' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $revenue->extract_status }}
                                </span>
                            </td>
                            <td class="col-actions px-2 py-3 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900 transition-colors" onclick="viewRevenue({{ $revenue->id }})" title="عرض">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button class="text-yellow-600 hover:text-yellow-900 transition-colors" onclick="editRevenue({{ $revenue->id }})" title="تعديل">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900 transition-colors" onclick="deleteRevenue({{ $revenue->id }})" title="حذف">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="22" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900 mb-2">لا توجد إيرادات مسجلة</p>
                                    <p class="text-gray-600 mb-4">ابدأ بإضافة أول سجل إيرادات لهذا المشروع</p>
                                    
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Progress Overview -->
        <div class="revenues-card mt-12 p-8">
            <div class="text-left mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Revenue Progress Overview</h2>
                <p class="text-gray-600">Track the progress of revenue collection and invoicing</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="text-left">
                    <div class="w-20 h-20 bg-blue-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Invoicing Progress</h3>
                    <div class="text-3xl font-bold text-blue-600 mb-2">70%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 70%"></div>
                    </div>
                </div>
                
                <div class="text-left">
                    <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Collection Rate</h3>
                    <div class="text-3xl font-bold text-green-600 mb-2">50%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 50%"></div>
                    </div>
                </div>
                
                <div class="text-left">
                    <div class="w-20 h-20 bg-yellow-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Outstanding</h3>
                    <div class="text-3xl font-bold text-yellow-600 mb-2">20%</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: 20%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Overall Progress -->
            <div class="text-left">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Overall Revenue Progress</h4>
                <div class="text-4xl font-bold text-green-600 mb-4">60%</div>
                <div class="w-full bg-gray-200 rounded-full h-4 max-w-md">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-4 rounded-full" style="width: 60%"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
        // Revenue Management Functions
        let isAddingNewRow = false;

        function openAddModal() {
            if (isAddingNewRow) {
                alert('يوجد صف قيد التحرير حالياً. يرجى حفظه أو إلغاؤه أولاً.');
                return;
            }
            addNewRow();
        }
        
        // Simplified export function for testing
        function exportExcelSimple() {
            console.log('exportExcelSimple function called');
            
            // Show immediate feedback
            if (typeof showNotification === 'function') {
                showNotification('بدء عملية التصدير...', 'success');
            } else {
                alert('بدء عملية التصدير...');
            }
            
            try {
                // Get table data
                const rows = document.querySelectorAll('tbody tr');
                if (rows.length === 0) {
                    showNotification('لا توجد بيانات للتصدير', 'error');
                    return;
                }
                
                // Simple CSV export
                let csvContent = "data:text/csv;charset=utf-8,\uFEFF";
                
                // Add header
                csvContent += '"#","اسم العميل","المشروع","رقم العقد","رقم المستخلص","المكتب","نوع المستخلص","رقم PO","رقم الفاتورة","قيمة المستخلص","نسبة الضريبة","قيمة الضريبة","الغرامات","ضريبة الدفعة الأولى","صافي قيمة المستخلص","تاريخ إعداد المستخلص","العام","الرقم المرجعي","تاريخ الصرف","قيمة الصرف","نوع المستخلص"\n';
                
                // Add data rows
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const rowData = [];
                    
                    for (let i = 0; i < cells.length - 1; i++) { // Skip actions column
                        if (i === 21) { // Status column
                            const statusBadge = cells[i].querySelector('.status-badge');
                            rowData.push(statusBadge ? statusBadge.textContent.trim() : '');
                        } else {
                            rowData.push(cells[i].textContent.trim());
                        }
                    }
                    
                    csvContent += rowData.map(cell => `"${cell.replace(/"/g, '""')}"`).join(',') + '\n';
                });
                
                // Create download
                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", `revenues_${new Date().toISOString().split('T')[0]}.csv`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                showNotification(`تم تصدير ${rows.length} سجل بنجاح`, 'success');
                
            } catch (error) {
                console.error('Export error:', error);
                showNotification('خطأ في التصدير: ' + error.message, 'error');
            }
        }
        
        // Enhanced import function with database save
        function importExcelSimple() {
            console.log('importExcelSimple function called');
            
            // Show immediate feedback
            if (typeof showNotification === 'function') {
                showNotification('بدء عملية الاستيراد...', 'success');
            } else {
                alert('بدء عملية الاستيراد...');
            }
            
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = '.csv,.xlsx,.xls';
            input.onchange = function(event) {
                const file = event.target.files[0];
                if (file) {
                    showNotification('جاري قراءة الملف...', 'success');
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        try {
                            let data;
                            const content = e.target.result;
                            
                            if (file.name.toLowerCase().endsWith('.csv')) {
                                data = parseCSV(content);
                            } else if ((file.name.toLowerCase().endsWith('.xlsx') || file.name.toLowerCase().endsWith('.xls')) && typeof XLSX !== 'undefined') {
                                const workbook = XLSX.read(content, { type: 'array' });
                                const sheetName = workbook.SheetNames[0];
                                const worksheet = workbook.Sheets[sheetName];
                                data = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                            } else {
                                showNotification('نوع الملف غير مدعوم. يرجى استخدام CSV أو Excel.', 'error');
                                return;
                            }
                            
                            if (data.length < 2) {
                                showNotification('الملف فارغ أو لا يحتوي على بيانات صالحة.', 'error');
                                return;
                            }
                            
                            // Process and save data
                            processImportedData(data);
                            
                        } catch (error) {
                            console.error('Import error:', error);
                            showNotification('خطأ في قراءة الملف: ' + error.message, 'error');
                        }
                    };
                    
                    // Read file based on type
                    if (file.name.toLowerCase().endsWith('.csv')) {
                        reader.readAsText(file, 'UTF-8');
                    } else {
                        reader.readAsArrayBuffer(file);
                    }
                }
            };
            input.click();
        }
        
        function processImportedData(data) {
            showNotification('جاري معالجة البيانات...', 'success');
            
            // Skip header row
            const dataRows = data.slice(1);
            const revenues = [];
            
            dataRows.forEach((row, index) => {
                if (row.length < 4) return; // Skip incomplete rows
                
                try {
                    // Validate required fields
                    if (!row[1] || !row[2] || !row[3] || !row[4]) {
                        return;
                    }
                    
                    // Process dates if they come from Excel
                    let preparationDate = row[15] || '';
                    let paymentDate = row[18] || '';
                    
                    if (typeof row[15] === 'number') {
                        const date = new Date((row[15] - 25569) * 86400 * 1000);
                        preparationDate = date.toISOString().split('T')[0];
                    }
                    
                    if (typeof row[19] === 'number') {
                        const date = new Date((row[19] - 25569) * 86400 * 1000);
                        paymentDate = date.toISOString().split('T')[0];
                    }
                    
                    // Prepare revenue data
                    const revenueData = {
                        client_name: row[1] || '',
                        project_area: row[2] || '',
                        contract_number: row[3] || '',
                        extract_number: row[4] || '',
                        office: row[5] || 'المكتب الرئيسي',
                        extract_type: row[6] || 'مرحلي',
                        po_number: row[7] || null,
                        invoice_number: row[8] || null,
                        extract_value: parseFloat(row[9]) || 0,
                        tax_percentage: parseFloat(row[10]) || 15,
                        tax_value: parseFloat(row[11]) || 0,
                        penalties: parseFloat(row[12]) || 0,
                        first_payment_tax: parseFloat(row[13]) || 0,
                        net_extract_value: parseFloat(row[14]) || 0,
                        extract_date: preparationDate,
                        year: row[16] || new Date().getFullYear(),
                        reference_number: row[17] || null,
                        payment_date: paymentDate,
                        payment_value: parseFloat(row[19]) || 0,
                        extract_status: row[20] || 'معلق'
                    };
                    
                    revenues.push(revenueData);
                    
                } catch (error) {
                    console.error(`Error processing row ${index + 2}:`, error);
                }
            });
            
            if (revenues.length > 0) {
                saveRevenuesToDatabase(revenues);
            } else {
                showNotification('لم يتم العثور على بيانات صالحة للاستيراد.', 'error');
            }
        }
        
        function saveRevenuesToDatabase(revenues) {
            showNotification('جاري حفظ البيانات في قاعدة البيانات...', 'success');
            
            // Get project ID from URL or page
            const projectId = getProjectId();
            
            fetch(`{{ route('revenues.import', $project) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    revenues: revenues
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Refresh the table with new data
                    loadRevenuesFromDatabase();
                } else {
                    showNotification(data.message || 'خطأ في حفظ البيانات', 'error');
                }
            })
            .catch(error => {
                console.error('Save error:', error);
                showNotification('خطأ في الاتصال بقاعدة البيانات', 'error');
            });
        }
        
        function getProjectId() {
            // Extract project ID from URL
            const pathParts = window.location.pathname.split('/');
            const projectIndex = pathParts.indexOf('projects');
            if (projectIndex !== -1 && projectIndex + 1 < pathParts.length) {
                return pathParts[projectIndex + 1];
            }
            return 1; // Default fallback
        }
        
        // Load revenues from database
        function loadRevenuesFromDatabase() {
            showNotification('جاري تحميل البيانات...', 'success');
            
            const projectId = getProjectId();
            
            fetch(`/projects/${projectId}/revenues-data`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data && Array.isArray(data)) {
                    displayRevenuesInTable(data);
                    showNotification(`تم تحميل ${data.length} مستخلص`, 'success');
                } else {
                    showNotification('لا توجد بيانات للعرض', 'info');
                }
            })
            .catch(error => {
                console.error('Load error:', error);
                showNotification('خطأ في تحميل البيانات', 'error');
            });
        }
        
        // Display revenues in table
        function displayRevenuesInTable(revenues) {
            const tbody = document.querySelector('#revenue-table tbody');
            if (!tbody) return;
            
            // Clear existing rows except the sample ones
            const existingRows = tbody.querySelectorAll('tr');
            existingRows.forEach(row => {
                if (!row.classList.contains('sample-row')) {
                    row.remove();
                }
            });
            
            let rowNumber = 1;
            revenues.forEach(revenue => {
                const row = document.createElement('tr');
                row.className = 'table-row';
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${rowNumber}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${revenue.client_name || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${revenue.project_area || revenue.project || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${revenue.contract_number || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${revenue.extract_number || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${revenue.office || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${revenue.extract_type || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${revenue.po_number || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${revenue.invoice_number || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 text-right">${parseFloat(revenue.extract_value || 0).toLocaleString('ar-SA', {minimumFractionDigits: 2})}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${parseFloat(revenue.tax_percentage || 0)}%</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${parseFloat(revenue.tax_value || 0).toLocaleString('ar-SA', {minimumFractionDigits: 2})}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-right">${parseFloat(revenue.penalties || 0).toLocaleString('ar-SA', {minimumFractionDigits: 2})}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${parseFloat(revenue.first_payment_tax || 0).toLocaleString('ar-SA', {minimumFractionDigits: 2})}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 text-right">${parseFloat(revenue.net_extract_value || 0).toLocaleString('ar-SA', {minimumFractionDigits: 2})}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${revenue.extract_date || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${revenue.year || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${revenue.reference_number || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${revenue.payment_date || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 text-right">${parseFloat(revenue.payment_value || 0).toLocaleString('ar-SA', {minimumFractionDigits: 2})}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                        ${revenue.extract_type || 'مرحلي'}
                    </td>
                    
                </tr>
                `;
                tbody.appendChild(row);
                rowNumber++;
            });
            
            // Update record count
            updateRecordCount();
        }
        
        // Get status CSS class
        function getStatusClass(status) {
            switch(status) {
                case 'مدفوع': return 'status-paid';
                case 'جزئي': return 'status-partial';
                case 'معلق': return 'status-pending';
                case 'ملغي': return 'status-cancelled';
                default: return 'status-pending';
            }
        }

        function exportExcel() {
            try {
                // Check if there's data to export
                const rows = document.querySelectorAll('tbody tr');
                if (rows.length === 0) {
                    showNotification('لا توجد بيانات للتصدير', 'error');
                    return;
                }
                
                showNotification('جاري إعداد ملف التصدير...', 'success');
                
                // Collect all table data
                const tableData = [];
            
            // Add headers
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
                'قيمة المستخلص',
                'نسبة الضريبة',
                'قيمة الضريبة',
                'الغرامات',
                'ضريبة الدفعة الأولى',
                'صافي قيمة المستخلص',
                'تاريخ إعداد المستخلص',
                'العام',
                'الرقم المرجعي',
                'تاريخ الصرف',
                'قيمة الصرف',
                'نوع المستخلص'
            ];
            tableData.push(headers);
            
            // Add data rows
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length > 0) {
                    const rowData = [];
                    for (let i = 0; i < cells.length - 1; i++) { // Skip actions column
                        if (i === 21) { // Status column
                            const statusBadge = cells[i].querySelector('.status-badge');
                            rowData.push(statusBadge ? statusBadge.textContent.trim() : '');
                        } else {
                            let cellText = cells[i].textContent.trim();
                            // Convert numbers properly
                            if (i >= 9 && i <= 14 && i !== 10) { // Numeric columns except percentage
                                const num = parseFloat(cellText);
                                rowData.push(isNaN(num) ? cellText : num);
                            } else if (i === 10) { // Tax percentage
                                rowData.push(cellText.replace('%', ''));
                            } else {
                                rowData.push(cellText);
                            }
                        }
                    }
                    tableData.push(rowData);
                }
            });
            
            // Create workbook using SheetJS
            if (typeof XLSX !== 'undefined') {
                const wb = XLSX.utils.book_new();
                const ws = XLSX.utils.aoa_to_sheet(tableData);
                
                // Set column widths
                const colWidths = [
                    { wch: 5 },   // #
                    { wch: 25 },  // اسم العميل
                    { wch: 25 },  // المشروع
                    { wch: 15 },  // رقم العقد
                    { wch: 15 },  // رقم المستخلص
                    { wch: 15 },  // المكتب
                    { wch: 12 },  // نوع المستخلص
                    { wch: 15 },  // رقم PO
                    { wch: 15 },  // رقم الفاتورة
                    { wch: 15 },  // قيمة المستخلص
                    { wch: 12 },  // نسبة الضريبة
                    { wch: 15 },  // قيمة الضريبة
                    { wch: 12 },  // الغرامات
                    { wch: 18 },  // ضريبة الدفعة الأولى
                    { wch: 18 },  // صافي قيمة المستخلص
                    { wch: 18 },  // تاريخ إعداد المستخلص
                    { wch: 8 },   // العام
                    { wch: 15 },  // الرقم المرجعي
                    { wch: 15 },  // تاريخ الصرف
                    { wch: 15 },  // قيمة الصرف
                    { wch: 15 }   // نوع المستخلص
                ];
                ws['!cols'] = colWidths;
                
                // Style header row
                const range = XLSX.utils.decode_range(ws['!ref']);
                for (let C = range.s.c; C <= range.e.c; ++C) {
                    const address = XLSX.utils.encode_cell({ r: 0, c: C });
                    if (!ws[address]) continue;
                    ws[address].s = {
                        font: { bold: true, color: { rgb: "FFFFFF" } },
                        fill: { fgColor: { rgb: "4472C4" } },
                        alignment: { horizontal: "center", vertical: "center" }
                    };
                }
                
                // Add worksheet to workbook
                XLSX.utils.book_append_sheet(wb, ws, "إدارة الإيرادات");
                
                // Generate filename with current date
                const now = new Date();
                const dateStr = now.toISOString().split('T')[0];
                const filename = `revenues_${dateStr}.xlsx`;
                
                // Save Excel file
                XLSX.writeFile(wb, filename);
                
                showNotification(`تم تصدير ${rows.length} سجل إلى ملف Excel ${filename}`, 'success');
            } else {
                // Fallback to CSV if XLSX is not loaded
                const csvContent = tableData.map(row => 
                    row.map(cell => `"${cell.toString().replace(/"/g, '""')}"`).join(',')
                ).join('\n');
                
                // Add BOM for Arabic support
                const BOM = '\uFEFF';
                const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });
                
                // Generate filename with current date
                const now = new Date();
                const dateStr = now.toISOString().split('T')[0];
                const filename = `revenues_${dateStr}.csv`;
                
                // Create download link
                const link = document.createElement('a');
                if (link.download !== undefined) {
                    const url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', filename);
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(url);
                }
                
                showNotification(`تم تصدير ${rows.length} سجل إلى ملف CSV ${filename}`, 'success');
            }
            
            } catch (error) {
                console.error('Export error:', error);
                showNotification('خطأ في تصدير البيانات: ' + error.message, 'error');
            }
        }

        function importExcel() {
            // Create file input element
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = '.csv,.xlsx,.xls';
            fileInput.style.display = 'none';
            
            fileInput.onchange = function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                showNotification('جاري قراءة الملف...', 'success');
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        let data;
                        const content = e.target.result;
                        
                        if (file.name.toLowerCase().endsWith('.csv')) {
                            // Parse CSV
                            data = parseCSV(content);
                        } else if ((file.name.toLowerCase().endsWith('.xlsx') || file.name.toLowerCase().endsWith('.xls')) && typeof XLSX !== 'undefined') {
                            // Parse Excel using SheetJS
                            const workbook = XLSX.read(content, { type: 'array' });
                            const sheetName = workbook.SheetNames[0];
                            const worksheet = workbook.Sheets[sheetName];
                            data = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                        } else {
                            showNotification('نوع الملف غير مدعوم. يرجى استخدام ملفات Excel (.xlsx, .xls) أو CSV.', 'error');
                            return;
                        }
                        
                        if (data.length < 2) {
                            showNotification('الملف فارغ أو لا يحتوي على بيانات صالحة.', 'error');
                            return;
                        }
                        
                        // Skip header row
                        const dataRows = data.slice(1);
                        let importedCount = 0;
                        let errorCount = 0;
                        
                        dataRows.forEach((row, index) => {
                            if (row.length < 4) return; // Skip incomplete rows
                            
                            try {
                                // Validate required fields
                                if (!row[1] || !row[2] || !row[3] || !row[4]) {
                                    errorCount++;
                                    return;
                                }
                                
                                // Process dates if they come from Excel
                                let preparationDate = row[15] || '';
                                let paymentDate = row[18] || '';
                                
                                if (typeof row[15] === 'number') {
                                    // Excel date serial number
                                    const date = new Date((row[15] - 25569) * 86400 * 1000);
                                    preparationDate = date.toISOString().split('T')[0];
                                }
                                
                                if (typeof row[19] === 'number') {
                                    // Excel date serial number
                                    const date = new Date((row[19] - 25569) * 86400 * 1000);
                                    paymentDate = date.toISOString().split('T')[0];
                                }
                                
                                // Add row to table
                                addImportedRow({
                                    client_name: row[1] || '',
                                    project_name: row[2] || '',
                                    contract_number: row[3] || '',
                                    extract_number: row[4] || '',
                                    office: row[5] || 'المكتب الرئيسي',
                                    extract_type: row[6] || 'مرحلي',
                                    po_number: row[7] || '',
                                    invoice_number: row[8] || '',
                                    extract_value: parseFloat(row[9]) || 0,
                                    tax_percentage: parseFloat(row[10]) || 15,
                                    tax_value: parseFloat(row[11]) || 0,
                                    penalties: parseFloat(row[12]) || 0,
                                    first_payment_tax: parseFloat(row[13]) || 0,
                                    net_value: parseFloat(row[14]) || 0,
                                    preparation_date: preparationDate,
                                    year: row[16] || new Date().getFullYear(),
                                    payment_type: row[17] || 'حوالة بنكية',
                                    reference_number: row[18] || '',
                                    payment_date: paymentDate,
                                    payment_value: parseFloat(row[20]) || 0,
                                    status: row[21] || 'معلق'
                                });
                                
                                importedCount++;
                            } catch (error) {
                                console.error(`Error importing row ${index + 2}:`, error);
                                errorCount++;
                            }
                        });
                        
                        // Update record count
                        updateRecordCountAfterImport(importedCount);
                        
                        // Show result notification
                        if (importedCount > 0) {
                            showNotification(`تم استيراد ${importedCount} سجل بنجاح${errorCount > 0 ? ` (${errorCount} سجل فشل)` : ''}`, 'success');
                        } else {
                            showNotification('لم يتم استيراد أي سجلات. تأكد من صحة البيانات.', 'error');
                        }
                        
                    } catch (error) {
                        console.error('Import error:', error);
                        showNotification('خطأ في قراءة الملف. تأكد من صحة تنسيق الملف.', 'error');
                    }
                };
                
                // Read file based on type
                if (file.name.toLowerCase().endsWith('.csv')) {
                    reader.readAsText(file, 'UTF-8');
                } else {
                    reader.readAsArrayBuffer(file);
                }
                
                document.body.removeChild(fileInput);
            };
            
            document.body.appendChild(fileInput);
            fileInput.click();
        }

        function addNewRow() {
            isAddingNewRow = true;
            const tbody = document.querySelector('tbody');
            const newRowNumber = tbody.children.length + 1;
            
            const newRow = document.createElement('tr');
            newRow.className = 'table-row bg-yellow-50 border-2 border-yellow-300';
            newRow.id = 'new-row';
            
            newRow.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${newRowNumber}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="text" id="client_name" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" placeholder="اسم العميل">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="text" id="project_name" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" placeholder="اسم المشروع">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="text" id="contract_number" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm font-mono" placeholder="2121-004">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="text" id="extract_number" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm font-mono" placeholder="EXT-004">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="text" id="office" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" placeholder="اسم المكتب">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="text" id="extract_type" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" placeholder="نوع المستخلص">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="text" id="po_number" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm font-mono" placeholder="PO-2025-004">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="text" id="invoice_number" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm font-mono" placeholder="INV-004">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="number" id="extract_value" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" placeholder="0.00" step="0.01" onchange="calculateTotals()">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="number" id="tax_percentage" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" placeholder="15" min="0" max="100" onchange="calculateTotals()">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="number" id="tax_value" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" placeholder="0.00" step="0.01" readonly>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="number" id="penalties" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" placeholder="0.00" step="0.01" onchange="calculateTotals()">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="number" id="first_payment_tax" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" placeholder="0.00" step="0.01" onchange="calculateTotals()">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="number" id="net_value" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" placeholder="0.00" step="0.01" readonly>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="date" id="preparation_date" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="number" id="year" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" placeholder="2025" min="2020" max="2030">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="text" id="reference_number" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm font-mono" placeholder="REF-004">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="date" id="payment_date" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <input type="number" id="payment_value" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" placeholder="0.00" step="0.01">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    <select id="extract_type" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm">
                        <option value="مرحلي">مرحلي</option>
                        <option value="نهائي">نهائي</option>
                        <option value="ابتدائي">ابتدائي</option>
                    </select>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button class="action-btn btn-view mr-1" onclick="saveNewRow()" title="حفظ">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>
                    <button class="action-btn btn-delete" onclick="cancelNewRow()" title="إلغاء">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </td>
            `;
            
            tbody.appendChild(newRow);
            
            // Focus on first input
            document.getElementById('client_name').focus();
            
            // Set current year as default
            document.getElementById('year').value = new Date().getFullYear();
            
            showNotification('تم إضافة صف جديد. يرجى ملء البيانات المطلوبة.', 'success');
        }

function viewRevenue(id) {
    const row = document.querySelector(`tr:nth-child(${id})`);
    if (!row) return;
    
    const cells = row.querySelectorAll('td');
    const data = {
        id: cells[0].textContent.trim(),
        client_name: cells[1].textContent.trim(),
        project_name: cells[2].textContent.trim(),
        contract_number: cells[3].textContent.trim(),
        extract_number: cells[4].textContent.trim(),
        office: cells[5].textContent.trim(),
        extract_type: cells[6].textContent.trim(),
        po_number: cells[7].textContent.trim(),
        invoice_number: cells[8].textContent.trim(),
        extract_value: cells[9].textContent.trim(),
        tax_percentage: cells[10].textContent.trim(),
        tax_value: cells[11].textContent.trim(),
        penalties: cells[12].textContent.trim(),
        first_payment_tax: cells[13].textContent.trim(),
        net_value: cells[14].textContent.trim(),
        preparation_date: cells[15].textContent.trim(),
        year: cells[16].textContent.trim(),
        reference_number: cells[17].textContent.trim(),
        payment_date: cells[18].textContent.trim(),
        payment_value: cells[19].textContent.trim(),
        extract_type: cells[20].textContent.trim()
    };
    
    showViewModal(data);
}

function editRevenue(id) {
    if (isAddingNewRow) {
        alert('يوجد صف قيد التحرير حالياً. يرجى حفظه أو إلغاؤه أولاً.');
        return;
    }
    
    const row = document.querySelector(`tbody tr:nth-child(${id})`);
    if (!row) return;
    
    const cells = row.querySelectorAll('td');
    
    // Store original content
    row.setAttribute('data-original-content', row.innerHTML);
    row.className = 'table-row bg-blue-50 border-2 border-blue-300';
    row.id = 'edit-row-' + id;
    
    row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${id}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="text" id="edit_client_name" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${cells[1].textContent.trim()}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="text" id="edit_project_name" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${cells[2].textContent.trim()}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="text" id="edit_contract_number" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm font-mono" value="${cells[3].textContent.trim()}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="text" id="edit_extract_number" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm font-mono" value="${cells[4].textContent.trim()}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="text" id="edit_office" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${cells[5].textContent.trim()}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="text" id="edit_extract_type" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${cells[6].textContent.trim()}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="text" id="edit_po_number" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm font-mono" value="${cells[7].textContent.trim()}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="text" id="edit_invoice_number" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm font-mono" value="${cells[8].textContent.trim()}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="number" id="edit_extract_value" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${parseFloat(cells[9].textContent.trim())}" step="0.01" onchange="calculateEditTotals()">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="number" id="edit_tax_percentage" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${parseFloat(cells[10].textContent.replace('%', ''))}" min="0" max="100" onchange="calculateEditTotals()">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="number" id="edit_tax_value" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${parseFloat(cells[11].textContent.trim())}" step="0.01" readonly>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="number" id="edit_penalties" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${parseFloat(cells[12].textContent.trim())}" step="0.01" onchange="calculateEditTotals()">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="number" id="edit_first_payment_tax" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${parseFloat(cells[13].textContent.trim())}" step="0.01" onchange="calculateEditTotals()">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="number" id="edit_net_value" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${parseFloat(cells[14].textContent.trim())}" step="0.01" readonly>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="date" id="edit_preparation_date" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${convertDateToInput(cells[15].textContent.trim())}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="number" id="edit_year" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${cells[16].textContent.trim()}" min="2020" max="2030">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="text" id="edit_reference_number" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm font-mono" value="${cells[17].textContent.trim()}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="date" id="edit_payment_date" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${convertDateToInput(cells[18].textContent.trim())}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <input type="number" id="edit_payment_value" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm" value="${cells[19].textContent.trim() !== '-' ? parseFloat(cells[19].textContent.trim()) : ''}" step="0.01">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
            <select id="edit_extract_type" class="w-full px-2 py-1 border border-gray-300 rounded text-right text-sm">
                <option value="مرحلي" ${cells[20].textContent.trim() === 'مرحلي' ? 'selected' : ''}>مرحلي</option>
                <option value="نهائي" ${cells[20].textContent.trim() === 'نهائي' ? 'selected' : ''}>نهائي</option>
                <option value="ابتدائي" ${cells[20].textContent.trim() === 'ابتدائي' ? 'selected' : ''}>ابتدائي</option>
            </select>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <button class="action-btn btn-view mr-1" onclick="saveEditRow(${id})" title="حفظ">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </button>
            <button class="action-btn btn-delete" onclick="cancelEditRow(${id})" title="إلغاء">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </td>
    `;
    
    // Focus on first input
    document.getElementById('edit_client_name').focus();
    
    showNotification('وضع التحرير مُفعل. قم بتعديل البيانات واضغط حفظ.', 'success');
}

function deleteRevenue(id) {
    const row = document.querySelector(`tbody tr[data-revenue-id="${id}"]`);
    if (!row) return;
    
    const clientName = row.querySelector('td:nth-child(2)').textContent.trim();
    const projectName = row.querySelector('td:nth-child(3)').textContent.trim();
    
    if (confirm(`هل أنت متأكد من حذف مستخلص "${projectName}" للعميل "${clientName}"؟\n\nهذا الإجراء لا يمكن التراجع عنه.`)) {
        // Show loading animation
        row.style.opacity = '0.5';
        row.style.pointerEvents = 'none';
        
        // Delete via API
        fetch(`{{ route('revenues.destroy', ['project' => $project, 'revenue' => '__ID__']) }}`.replace('__ID__', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Animate removal
                row.style.transition = 'all 0.3s ease';
                row.style.transform = 'translateX(100%)';
                row.style.opacity = '0';
                
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            } else {
                throw new Error(data.message || 'حدث خطأ في الحذف');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('حدث خطأ في حذف البيانات: ' + error.message, 'error');
            
            // Reset row state
            row.style.opacity = '1';
            row.style.pointerEvents = 'auto';
        });
    }
}

function calculateTotals() {
    const extractValue = parseFloat(document.getElementById('extract_value').value) || 0;
    const taxPercentage = parseFloat(document.getElementById('tax_percentage').value) || 0;
    const penalties = parseFloat(document.getElementById('penalties').value) || 0;
    const firstPaymentTax = parseFloat(document.getElementById('first_payment_tax').value) || 0;
    
    // Calculate tax value
    const taxValue = (extractValue * taxPercentage) / 100;
    document.getElementById('tax_value').value = taxValue.toFixed(2);
    
    // Calculate net value
    const netValue = extractValue + taxValue - penalties - firstPaymentTax;
    document.getElementById('net_value').value = netValue.toFixed(2);
}

function saveNewRow() {
    const formData = {
        client_name: document.getElementById('client_name').value,
        project_area: document.getElementById('project_name').value,
        contract_number: document.getElementById('contract_number').value,
        extract_number: document.getElementById('extract_number').value,
        office: document.getElementById('office').value,
        extract_type: document.getElementById('extract_type').value,
        po_number: document.getElementById('po_number').value,
        invoice_number: document.getElementById('invoice_number').value,
        extract_value: document.getElementById('extract_value').value,
        tax_percentage: document.getElementById('tax_percentage').value,
        tax_value: document.getElementById('tax_value').value,
        penalties: document.getElementById('penalties').value,
        first_payment_tax: document.getElementById('first_payment_tax').value,
        net_extract_value: document.getElementById('net_value').value,
        extract_date: document.getElementById('preparation_date').value,
        year: document.getElementById('year').value,
        reference_number: document.getElementById('reference_number').value,
        payment_date: document.getElementById('payment_date').value,
        payment_value: document.getElementById('payment_value').value,
        extract_status: document.getElementById('extract_type').value
    };
    
    // Validate required fields
    const requiredFields = ['client_name', 'project_name', 'contract_number', 'extract_number'];
    for (let field of requiredFields) {
        if (!formData[field]) {
            showNotification(`يرجى ملء حقل ${getFieldLabel(field)}`, 'error');
            document.getElementById(field).focus();
            return;
        }
    }
    
    // Show loading
    const saveBtn = document.querySelector('#new-row button[title="حفظ"]');
    const originalContent = saveBtn.innerHTML;
    saveBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    saveBtn.disabled = true;
    
    // Save to database via API
    fetch(`{{ route('revenues.store', $project) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to show updated data
            showNotification('تم حفظ البيانات بنجاح!', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(data.message || 'حدث خطأ في الحفظ');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('حدث خطأ في حفظ البيانات: ' + error.message, 'error');
        
        // Reset button
        saveBtn.innerHTML = originalContent;
        saveBtn.disabled = false;
    });
}

function convertNewRowToDisplay(data) {
    const newRow = document.getElementById('new-row');
    const rowNumber = newRow.querySelector('td:first-child').textContent;
    
    // Get status badge
    const statusBadge = getStatusBadge(data.status);
    
    newRow.className = 'table-row';
    newRow.id = '';
    
    newRow.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${rowNumber}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.client_name}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.project_name}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.contract_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.extract_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.office}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.extract_type}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.po_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.invoice_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 text-right">${parseFloat(data.extract_value).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.tax_percentage}%</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${parseFloat(data.tax_value).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-right">${parseFloat(data.penalties).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${parseFloat(data.first_payment_tax).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 text-right">${parseFloat(data.net_value).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${formatDate(data.preparation_date)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.year}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.reference_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.payment_date ? formatDate(data.payment_date) : '-'}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 text-right">${data.payment_value ? parseFloat(data.payment_value).toFixed(2) : '-'}</td>
        <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <button class="action-btn btn-view" onclick="viewRevenue(${rowNumber})">عرض</button>
            <button class="action-btn btn-edit" onclick="editRevenue(${rowNumber})">تعديل</button>
            <button class="action-btn btn-delete" onclick="deleteRevenue(${rowNumber})">حذف</button>
        </td>
    `;
}

function cancelNewRow() {
    if (confirm('هل أنت متأكد من إلغاء الصف الجديد؟ سيتم فقدان جميع البيانات المدخلة.')) {
        document.getElementById('new-row').remove();
        isAddingNewRow = false;
        showNotification('تم إلغاء الصف الجديد.', 'success');
    }
}

function getFieldLabel(field) {
    const labels = {
        'client_name': 'اسم العميل',
        'project_name': 'اسم المشروع',
        'contract_number': 'رقم العقد',
        'extract_number': 'رقم المستخلص'
    };
    return labels[field] || field;
}

function getStatusBadge(status) {
    const badges = {
        'معلق': '<span class="status-badge status-pending">معلق</span>',
        'جزئي': '<span class="status-badge status-partial">جزئي</span>',
        'مدفوع': '<span class="status-badge status-paid">مدفوع</span>',
        'ملغي': '<span class="status-badge status-cancelled">ملغي</span>'
    };
    return badges[status] || '<span class="status-badge status-pending">معلق</span>';
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('ar-SA');
}

function updateRecordCount() {
    const recordCount = document.querySelector('.text-gray-600 .font-semibold');
    const currentCount = parseInt(recordCount.textContent);
    recordCount.textContent = currentCount + 1;
}

function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform translate-x-full transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' 
                    ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                }
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Animate out and remove
    setTimeout(() => {
        notification.style.transform = 'translateX(full)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

function showViewModal(data) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    modal.onclick = (e) => {
        if (e.target === modal) closeViewModal();
    };
    
    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-8 py-6 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-900 text-right">تفاصيل المستخلص #${data.id}</h2>
                    <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-gray-600 mb-1 text-right">اسم العميل/الجهة المالكة</label>
                        <div class="text-lg font-semibold text-gray-900 text-right">${data.client_name}</div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-gray-600 mb-1 text-right">المشروع/المنطقة</label>
                        <div class="text-lg font-semibold text-gray-900 text-right">${data.project_name}</div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-gray-600 mb-1 text-right">رقم العقد</label>
                        <div class="text-lg font-mono text-gray-900 text-right">${data.contract_number}</div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-gray-600 mb-1 text-right">رقم المستخلص</label>
                        <div class="text-lg font-mono text-gray-900 text-right">${data.extract_number}</div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-gray-600 mb-1 text-right">المكتب</label>
                        <div class="text-lg text-gray-900 text-right">${data.office}</div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-gray-600 mb-1 text-right">نوع المستخلص</label>
                        <div class="text-lg text-gray-900 text-right">${data.extract_type}</div>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-blue-600 mb-1 text-right">قيمة المستخلص</label>
                        <div class="text-xl font-bold text-green-600 text-right">${data.extract_value} ريال</div>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-blue-600 mb-1 text-right">نسبة الضريبة</label>
                        <div class="text-xl font-bold text-gray-900 text-right">${data.tax_percentage}</div>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-blue-600 mb-1 text-right">قيمة الضريبة</label>
                        <div class="text-xl font-bold text-gray-900 text-right">${data.tax_value} ريال</div>
                    </div>
                    
                    <div class="bg-red-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-red-600 mb-1 text-right">الغرامات</label>
                        <div class="text-xl font-bold text-red-600 text-right">${data.penalties} ريال</div>
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-yellow-600 mb-1 text-right">ضريبة الدفعة الأولى</label>
                        <div class="text-xl font-bold text-yellow-600 text-right">${data.first_payment_tax} ريال</div>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-green-600 mb-1 text-right">صافي قيمة المستخلص</label>
                        <div class="text-xl font-bold text-green-600 text-right">${data.net_value} ريال</div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-gray-600 mb-1 text-right">تاريخ إعداد المستخلص</label>
                        <div class="text-lg text-gray-900 text-right">${data.preparation_date}</div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-gray-600 mb-1 text-right">العام</label>
                        <div class="text-lg text-gray-900 text-right">${data.year}</div>
                    </div>
                    
                    
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-gray-600 mb-1 text-right">الرقم المرجعي</label>
                        <div class="text-lg font-mono text-gray-900 text-right">${data.reference_number}</div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-gray-600 mb-1 text-right">تاريخ الصرف</label>
                        <div class="text-lg text-gray-900 text-right">${data.payment_date}</div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-gray-600 mb-1 text-right">قيمة الصرف</label>
                        <div class="text-lg font-semibold text-green-600 text-right">${data.payment_value} ريال</div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="block text-sm font-medium text-gray-600 mb-1 text-right">نوع المستخلص</label>
                        <div class="text-lg text-gray-900 text-right">${data.extract_type}</div>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-center">
                    <button onclick="closeViewModal()" class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold">
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
}

function closeViewModal() {
    const modal = document.querySelector('.fixed.inset-0');
    if (modal) {
        modal.remove();
        document.body.style.overflow = 'auto';
    }
}

function calculateEditTotals() {
    const extractValue = parseFloat(document.getElementById('edit_extract_value').value) || 0;
    const taxPercentage = parseFloat(document.getElementById('edit_tax_percentage').value) || 0;
    const penalties = parseFloat(document.getElementById('edit_penalties').value) || 0;
    const firstPaymentTax = parseFloat(document.getElementById('edit_first_payment_tax').value) || 0;
    
    // Calculate tax value
    const taxValue = (extractValue * taxPercentage) / 100;
    document.getElementById('edit_tax_value').value = taxValue.toFixed(2);
    
    // Calculate net value
    const netValue = extractValue + taxValue - penalties - firstPaymentTax;
    document.getElementById('edit_net_value').value = netValue.toFixed(2);
}

function saveEditRow(id) {
    const formData = {
        client_name: document.getElementById('edit_client_name').value,
        project_name: document.getElementById('edit_project_name').value,
        contract_number: document.getElementById('edit_contract_number').value,
        extract_number: document.getElementById('edit_extract_number').value,
        office: document.getElementById('edit_office').value,
        extract_type: document.getElementById('edit_extract_type').value,
        po_number: document.getElementById('edit_po_number').value,
        invoice_number: document.getElementById('edit_invoice_number').value,
        extract_value: document.getElementById('edit_extract_value').value,
        tax_percentage: document.getElementById('edit_tax_percentage').value,
        tax_value: document.getElementById('edit_tax_value').value,
        penalties: document.getElementById('edit_penalties').value,
        first_payment_tax: document.getElementById('edit_first_payment_tax').value,
        net_value: document.getElementById('edit_net_value').value,
        preparation_date: document.getElementById('edit_preparation_date').value,
        year: document.getElementById('edit_year').value,
        reference_number: document.getElementById('edit_reference_number').value,
        payment_date: document.getElementById('edit_payment_date').value,
        payment_value: document.getElementById('edit_payment_value').value,
        extract_type: document.getElementById('edit_extract_type').value
    };
    
    // Validate required fields
    const requiredFields = ['client_name', 'project_name', 'contract_number', 'extract_number'];
    for (let field of requiredFields) {
        if (!formData[field]) {
            showNotification(`يرجى ملء حقل ${getFieldLabel(field)}`, 'error');
            document.getElementById('edit_' + field).focus();
            return;
        }
    }
    
    // Show loading
    const saveBtn = document.querySelector(`#edit-row-${id} button[title="حفظ"]`);
    const originalContent = saveBtn.innerHTML;
    saveBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    saveBtn.disabled = true;
    
    // Simulate save
    setTimeout(() => {
        convertEditRowToDisplay(id, formData);
        showNotification('تم تحديث البيانات بنجاح!', 'success');
    }, 1000);
}

function convertEditRowToDisplay(id, data) {
    const editRow = document.getElementById('edit-row-' + id);
    const statusBadge = getStatusBadge(data.status);
    
    editRow.className = 'table-row';
    editRow.id = '';
    
    editRow.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${id}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.client_name}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.project_name}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.contract_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.extract_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.office}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.extract_type}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.po_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.invoice_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 text-right">${parseFloat(data.extract_value).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.tax_percentage}%</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${parseFloat(data.tax_value).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-right">${parseFloat(data.penalties).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${parseFloat(data.first_payment_tax).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 text-right">${parseFloat(data.net_value).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${formatDate(data.preparation_date)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.year}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.reference_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.payment_date ? formatDate(data.payment_date) : '-'}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 text-right">${data.payment_value ? parseFloat(data.payment_value).toFixed(2) : '-'}</td>
        <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <button class="action-btn btn-view" onclick="viewRevenue(${id})">عرض</button>
            <button class="action-btn btn-edit" onclick="editRevenue(${id})">تعديل</button>
            <button class="action-btn btn-delete" onclick="deleteRevenue(${id})">حذف</button>
        </td>
    `;
}

function cancelEditRow(id) {
    if (confirm('هل أنت متأكد من إلغاء التعديلات؟ سيتم فقدان جميع التغييرات.')) {
        const editRow = document.getElementById('edit-row-' + id);
        const originalContent = editRow.getAttribute('data-original-content');
        
        editRow.innerHTML = originalContent;
        editRow.className = 'table-row';
        editRow.id = '';
        editRow.removeAttribute('data-original-content');
        
        showNotification('تم إلغاء التعديلات.', 'success');
    }
}

function convertDateToInput(dateString) {
    if (!dateString || dateString === '-') return '';
    
    // Handle Arabic date format (DD/MM/YYYY)
    const parts = dateString.split('/');
    if (parts.length === 3) {
        const day = parts[0].padStart(2, '0');
        const month = parts[1].padStart(2, '0');
        const year = parts[2];
        return `${year}-${month}-${day}`;
    }
    
    return dateString;
}

function updateRecordCountAfterDelete() {
    const recordCount = document.querySelector('.text-gray-600 .font-semibold');
    const currentCount = parseInt(recordCount.textContent);
    recordCount.textContent = Math.max(0, currentCount - 1);
}

function renumberRows() {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach((row, index) => {
        const firstCell = row.querySelector('td:first-child');
        if (firstCell) {
            firstCell.textContent = index + 1;
        }
    });
}

function parseCSV(text) {
    const rows = [];
    let row = [];
    let inQuotes = false;
    let currentCell = '';
    
    for (let i = 0; i < text.length; i++) {
        const char = text[i];
        const nextChar = text[i + 1];
        
        if (char === '"') {
            if (inQuotes && nextChar === '"') {
                // Escaped quote
                currentCell += '"';
                i++; // Skip next quote
            } else {
                // Toggle quote state
                inQuotes = !inQuotes;
            }
        } else if (char === ',' && !inQuotes) {
            // End of cell
            row.push(currentCell.trim());
            currentCell = '';
        } else if ((char === '\n' || char === '\r') && !inQuotes) {
            // End of row
            if (currentCell || row.length > 0) {
                row.push(currentCell.trim());
                rows.push(row);
                row = [];
                currentCell = '';
            }
            // Skip \r\n
            if (char === '\r' && nextChar === '\n') {
                i++;
            }
        } else {
            currentCell += char;
        }
    }
    
    // Add last cell and row if exists
    if (currentCell || row.length > 0) {
        row.push(currentCell.trim());
        rows.push(row);
    }
    
    return rows;
}

function addImportedRow(data) {
    const tbody = document.querySelector('tbody');
    const newRowNumber = tbody.children.length + 1;
    
    const newRow = document.createElement('tr');
    newRow.className = 'table-row bg-green-50 border-2 border-green-300';
    
    // Get status badge
    const statusBadge = getStatusBadge(data.status);
    
    newRow.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${newRowNumber}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.client_name}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.project_name}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.contract_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.extract_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.office}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.extract_type}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.po_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.invoice_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 text-right">${parseFloat(data.extract_value).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.tax_percentage}%</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${parseFloat(data.tax_value).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-right">${parseFloat(data.penalties).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${parseFloat(data.first_payment_tax).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 text-right">${parseFloat(data.net_value).toFixed(2)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${formatDate(data.preparation_date)}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.year}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">${data.reference_number}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${data.payment_date ? formatDate(data.payment_date) : '-'}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 text-right">${data.payment_value ? parseFloat(data.payment_value).toFixed(2) : '-'}</td>
        <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <button class="action-btn btn-view" onclick="viewRevenue(${newRowNumber})">عرض</button>
            <button class="action-btn btn-edit" onclick="editRevenue(${newRowNumber})">تعديل</button>
            <button class="action-btn btn-delete" onclick="deleteRevenue(${newRowNumber})">حذف</button>
        </td>
    `;
    
    tbody.appendChild(newRow);
    
    // Remove import highlight after 3 seconds
    setTimeout(() => {
        newRow.className = 'table-row';
    }, 3000);
}

function updateRecordCountAfterImport(count) {
    const recordCount = document.querySelector('.text-gray-600 .font-semibold');
    const currentCount = parseInt(recordCount.textContent);
    recordCount.textContent = currentCount + count;
}

// Add some interactivity
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Setting up event handlers');
    
    // Check SheetJS library
    if (typeof XLSX === 'undefined') {
        console.warn('SheetJS library not loaded. Excel features will use CSV fallback.');
    } else {
        console.log('SheetJS library loaded successfully');
    }
    
    // Test functions availability
    console.log('Testing function availability:');
    console.log('exportExcelSimple:', typeof exportExcelSimple);
    console.log('importExcelSimple:', typeof importExcelSimple);
    console.log('showNotification:', typeof showNotification);
    
    // Load existing revenues from database
    loadRevenuesFromDatabase();
    
    // Test button functionality
    const exportBtn = document.querySelector('button[onclick="exportExcelSimple()"]');
    const importBtn = document.querySelector('button[onclick="importExcelSimple()"]');
    
    if (exportBtn) {
        console.log('Export button found');
        exportBtn.addEventListener('click', function(e) {
            console.log('Export button clicked via event listener');
        });
    } else {
        console.error('Export button not found - checking all buttons...');
        const allButtons = document.querySelectorAll('button');
        console.log('All buttons found:', allButtons.length);
        allButtons.forEach((btn, index) => {
            console.log(`Button ${index}:`, btn.getAttribute('onclick'), btn.textContent.trim());
        });
    }
    
    if (importBtn) {
        console.log('Import button found');
        importBtn.addEventListener('click', function(e) {
            console.log('Import button clicked via event listener');
        });
    } else {
        console.error('Import button not found');
    }
    
    // Alternative: Add event listeners by class or ID
    setTimeout(() => {
        const buttons = document.querySelectorAll('.btn-secondary');
        console.log('Found secondary buttons:', buttons.length);
        
        buttons.forEach((btn, index) => {
            const text = btn.textContent.trim();
            console.log(`Button ${index}: "${text}"`);
            
            if (text.includes('تصدير') || text.includes('CSV')) {
                btn.addEventListener('click', function(e) {
                    console.log('Export button clicked via class selector');
                    exportExcelSimple();
                });
                console.log('Added export listener to button');
            }
            
            if (text.includes('استيراد')) {
                btn.addEventListener('click', function(e) {
                    console.log('Import button clicked via class selector');
                    importExcelSimple();
                });
                console.log('Added import listener to button');
            }
        });
    }, 1000);
    
    // Direct ID-based event listeners
    const exportBtnById = document.getElementById('export-btn');
    const importBtnById = document.getElementById('import-btn');
    
    if (exportBtnById) {
        console.log('Export button found by ID');
        exportBtnById.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Export clicked via ID listener');
            exportExcelSimple();
        });
    }
    
    if (importBtnById) {
        console.log('Import button found by ID');
        importBtnById.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Import clicked via ID listener');
            importExcelSimple();
        });
    }
    
    // Add hover effects to table rows
    const tableRows = document.querySelectorAll('.table-row');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(4px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
    
    // Add click effects to buttons
    const buttons = document.querySelectorAll('.btn-primary, .btn-secondary');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
});
</script>
@endsection