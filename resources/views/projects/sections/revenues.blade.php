@extends('layouts.app')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
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
</style>
@endpush

@section('content')
@php
    $canEdit = auth()->user()->is_admin || (is_array(auth()->user()->permissions) && in_array('edit_turnkey_revenues', auth()->user()->permissions));
@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-12">
    <div class="container-fluid mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 95%;">
        
        <!-- Back Button -->
        <div class="mb-8 flex justify-end">
            <a href="{{ route('projects.show', $project) }}" 
               class="inline-flex items-center px-6 py-3 bg-white/80 backdrop-blur-sm border border-green-200 text-green-700 rounded-xl font-semibold shadow-lg hover:bg-white hover:shadow-xl transition-all duration-300 hover:transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Project
            </a>
        </div>
        
        <!-- Header -->
        <div class="header-gradient rounded-3xl shadow-2xl overflow-hidden mb-12 relative">
            <div class="relative z-10 p-8 text-center text-white">
                <div class="mb-6">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold mb-3">Turnkey Revenues</h1>
                    <p class="text-2xl font-semibold mb-2">إيرادات تسليم مفتاح</p>
                    <p class="text-green-100 text-lg">{{ $project->name }}</p>
                    <p class="text-green-100 text-sm mt-2">Contract: {{ $project->contract_number }}</p>
        </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-green-100 text-sm mb-1">Project Type</p>
                        <p class="font-semibold text-lg">{{ $project->getProjectTypeText() }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-green-100 text-sm mb-1">Location</p>
                        <p class="font-semibold text-lg">{{ $project->location }}</p>
                    </div>
                    </div>
                </div>
            </div>

        <!-- تنبيه الصلاحيات -->
        @if(!$canEdit)
        <div class="alert alert-warning mb-6 rounded-xl shadow-lg" role="alert" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 2px solid #f59e0b; padding: 1rem;">
            <div class="flex items-center">
                <i class="fas fa-lock text-amber-600 text-2xl me-3"></i>
                <div>
                    <strong class="text-amber-800">تنبيه:</strong>
                    <span class="text-amber-700">ليس لديك صلاحية تعديل بيانات الإيرادات. يمكنك فقط عرض البيانات.</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-8 gap-3 mb-8">
            <!-- عدد المستخلصات -->
            <div class="bg-white rounded-xl shadow-lg p-4 border-t-4 border-purple-500 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-gray-600 text-xs mb-1">عدد المستخلصات</p>
                <p class="text-2xl font-bold text-gray-800" id="stat_total_count">0</p>
            </div>

            <!-- إجمالي قيمة المستخلصات غير شامله الضريبه -->
            <div class="bg-white rounded-xl shadow-lg p-4 border-t-4 border-blue-500 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-gray-600 text-xs mb-1">إجمالي قيمة المستخلصات<br>غير شامله الضريبه</p>
                <p class="text-2xl font-bold text-gray-800" id="stat_total_extract_value">0.00</p>
            </div>

            <!-- إجمالي الضريبة -->
            <div class="bg-white rounded-xl shadow-lg p-4 border-t-4 border-green-500 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                    </svg>
                </div>
                <p class="text-gray-600 text-xs mb-1">إجمالي الضريبة</p>
                <p class="text-2xl font-bold text-gray-800" id="stat_total_tax">0.00</p>
            </div>

            <!-- إجمالي الغرامات -->
            <div class="bg-white rounded-xl shadow-lg p-4 border-t-4 border-red-500 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <p class="text-gray-600 text-xs mb-1">إجمالي الغرامات</p>
                <p class="text-2xl font-bold text-gray-800" id="stat_total_penalties">0.00</p>
            </div>

            <!-- صافي قيمة المستخلصات غير شامل الضريبة -->
            <div class="bg-white rounded-xl shadow-lg p-4 border-t-4 border-indigo-500 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-gray-600 text-xs mb-1">صافي قيمة المستخلصات<br>شامل الضريبة</p>
                <p class="text-2xl font-bold text-gray-800" id="stat_net_value">0.00</p>
            </div>

            <!-- إجمالي المدفوعات -->
            <div class="bg-white rounded-xl shadow-lg p-4 border-t-4 border-teal-500 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-8 h-8 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <p class="text-gray-600 text-xs mb-1">إجمالي المدفوعات</p>
                <p class="text-2xl font-bold text-gray-800" id="stat_total_payments">0.00</p>
            </div>

            <!-- المبلغ المتبقي عند العميل -->
            <div class="bg-white rounded-xl shadow-lg p-4 border-t-4 border-orange-500 hover:shadow-xl transition-all duration-300 cursor-pointer" onclick="showRemainingDetailsModal()" style="transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <p class="text-gray-600 text-xs mb-1"> المبلغ المتبقي <br>عند العميل شامل الضريبة</p>
                <p class="text-2xl font-bold text-gray-800" id="stat_remaining_amount">0.00</p>
                <p class="text-gray-500 text-xs mt-2">
                    <i class="fas fa-info-circle"></i> اضغط للتفاصيل
                </p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-green-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                    </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-red-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                    </div>
        @endif

        <!-- Turnkey Revenues Table -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-green-900 text-left">Turnkey Revenues Table</h2>
                    <button type="button" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-all flex items-center"
                            onclick="addNewTurnkeyRow()">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New Row
                    </button>
                </div>
                
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-sm text-left border-collapse" id="turnkeyRevenuesTable">
                        <thead class="bg-gradient-to-r from-green-600 to-green-700 text-white">
                            <tr>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap">#</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[200px]">اسم العميل</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[180px]">المشروع</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[200px]">رقم العقد</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[200px]">رقم المستخلص</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[180px]">نوع المستخلص</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[200px]">رقم PO</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[200px]">رقم الفاتورة</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[180px]">الموقع</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[200px]">إجمالي قيمة المستخلصات<br>غير شامله الضريبه</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[180px]">قيمة الضريبة</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[180px]">الغرامات</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[180px]">صافي قيمة المستخلص</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[180px]">تاريخ إعداد المستخلص</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[100px]">العام</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[180px]">موقف المستخلص<br>عند ...</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[200px]">الرقم المرجعي</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[180px]">تاريخ الصرف</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[180px]">قيمة الصرف</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[150px]">حالة الصرف</th>
                                <th class="px-3 py-3 font-semibold border-b border-green-500 whitespace-nowrap min-w-[100px]">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="turnkeyRevenuesTableBody">
                            @forelse($turnkeyRevenues ?? [] as $revenue)
                            <tr class="border-b hover:bg-green-50 transition-colors" data-id="{{ $revenue->id }}">
                                <td class="px-3 py-3 text-center font-medium text-gray-600">{{ $loop->iteration }}</td>
                                <!-- اسم العميل -->
                                <td class="px-3 py-3 border-x border-gray-200">
                                    <input type="text" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" 
                                           value="{{ $revenue->client_name }}" 
                                           data-field="client_name" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="اسم العميل"
                                           {{ !$canEdit ? 'readonly style=background-color:#f8f9fa;cursor:not-allowed;' : '' }}>
                                </td>
                                <!-- المشروع -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="text" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" 
                                           value="{{ $revenue->project }}" 
                                           data-field="project" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="المشروع"
                                           {{ !$canEdit ? 'readonly style=background-color:#f8f9fa;cursor:not-allowed;' : '' }}>
                                </td>
                                <!-- رقم العقد -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="text" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" 
                                           value="{{ $revenue->contract_number }}" 
                                           data-field="contract_number" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="رقم العقد"
                                           {{ !$canEdit ? 'readonly style=background-color:#f8f9fa;cursor:not-allowed;' : '' }}>
                                </td>
                                <!-- رقم المستخلص -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="text" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" 
                                           value="{{ $revenue->extract_number }}" 
                                           data-field="extract_number" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="رقم المستخلص"
                                           {{ !$canEdit ? 'readonly style=background-color:#f8f9fa;cursor:not-allowed;' : '' }}>
                                </td>
                                <!-- نوع المستخلص -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="text" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" 
                                           value="{{ $revenue->extract_type }}" 
                                           data-field="extract_type" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="نوع المستخلص"
                                           {{ !$canEdit ? 'readonly style=background-color:#f8f9fa;cursor:not-allowed;' : '' }}>
                                </td>
                                <!-- رقم PO -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="text" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" 
                                           value="{{ $revenue->po_number }}" 
                                           data-field="po_number" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="رقم PO"
                                           {{ !$canEdit ? 'readonly style=background-color:#f8f9fa;cursor:not-allowed;' : '' }}>
                                </td>
                                <!-- رقم الفاتورة -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="text" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" 
                                           value="{{ $revenue->invoice_number }}" 
                                           data-field="invoice_number" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="رقم الفاتورة"
                                           {{ !$canEdit ? 'readonly style=background-color:#f8f9fa;cursor:not-allowed;' : '' }}>
                                </td>
                                <!-- الموقع -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="text" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" 
                                           value="{{ $revenue->location }}" 
                                           data-field="location" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="الموقع"
                                           {{ !$canEdit ? 'readonly style=background-color:#f8f9fa;cursor:not-allowed;' : '' }}>
                                </td>
                                <!-- إجمالي قيمة المستخلصات غير شامله الضريبه -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="number" 
                                           step="0.01" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all text-right" 
                                           value="{{ $revenue->extract_value }}" 
                                           data-field="extract_value" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="0.00">
                                </td>
                                <!-- قيمة الضريبة -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="number" 
                                           step="0.01" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all text-right bg-yellow-50" 
                                           value="{{ $revenue->tax_value }}" 
                                           data-field="tax_value" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="0.00"
                                           readonly
                                           title="محسوب تلقائياً: قيمة المستخلصات × 15%">
                                </td>
                                <!-- الغرامات -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="number" 
                                           step="0.01" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all text-right" 
                                           value="{{ $revenue->penalties }}" 
                                           data-field="penalties" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="0.00">
                                </td>
                                <!-- صافي قيمة المستخلص -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="number" 
                                           step="0.01" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all text-right bg-yellow-50" 
                                           value="{{ $revenue->net_extract_value }}" 
                                           data-field="net_extract_value" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="0.00"
                                           readonly
                                           title="محسوب تلقائياً: قيمة المستخلص + قيمة الضريبة - الغرامات">
                                </td>
                                <!-- تاريخ إعداد المستخلص -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="date" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" 
                                           value="{{ $revenue->extract_date?->format('Y-m-d') }}" 
                                           data-field="extract_date" 
                                           onchange="autoSaveTurnkey(this)">
                                </td>
                                <!-- العام -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="number" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all text-right" 
                                           value="{{ $revenue->year }}" 
                                           data-field="year" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="{{ date('Y') }}">
                                </td>
                                <!-- موقف المستخلص عند ... -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <select class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" 
                                            data-field="extract_status" 
                                            onchange="autoSaveTurnkey(this)">
                                        
                                        <option value="المقاول" {{ $revenue->extract_status === 'المقاول' ? 'selected' : '' }}>المقاول</option>
                                        <option value="ادارة الكهرباء" {{ $revenue->extract_status === 'ادارة الكهرباء' ? 'selected' : '' }}>ادارة الكهرباء</option>
                                        <option value="المالية" {{ $revenue->extract_status === 'المالية' ? 'selected' : '' }}>المالية</option>
                                        <option value="الخزينة" {{ $revenue->extract_status === 'الخزينة' ? 'selected' : '' }}>الخزينة</option>
                                        <option value="تم الصرف" {{ $revenue->extract_status === 'تم الصرف' ? 'selected' : '' }}>تم الصرف</option>
                                    </select>
                                </td>
                                <!-- الرقم المرجعي -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="text" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" 
                                           value="{{ $revenue->reference_number }}" 
                                           data-field="reference_number" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="الرقم المرجعي">
                                </td>
                                <!-- تاريخ الصرف -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="date" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" 
                                           value="{{ $revenue->payment_date?->format('Y-m-d') }}" 
                                           data-field="payment_date" 
                                           onchange="autoSaveTurnkey(this)">
                                </td>
                                <!-- قيمة الصرف -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <input type="number" 
                                           step="0.01" 
                                           class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all text-right" 
                                           value="{{ $revenue->payment_value }}" 
                                           data-field="payment_value" 
                                           onchange="autoSaveTurnkey(this)"
                                           placeholder="0.00">
                                </td>
                                <!-- حالة الصرف -->
                                <td class="px-3 py-3 border-r border-gray-200">
                                    <select class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" 
                                            data-field="payment_status" 
                                            onchange="autoSaveTurnkey(this)">
                                        
                                        <option value="مدفوع" {{ $revenue->payment_status === 'مدفوع' || $revenue->payment_status === 'paid' ? 'selected' : '' }}>مدفوع</option>
                                        <option value="غير مدفوع" {{ $revenue->payment_status === 'غير مدفوع' || $revenue->payment_status === 'unpaid' || $revenue->payment_status === 'pending' ? 'selected' : '' }}>غير مدفوع</option>
                                    </select>
                                </td>
                                <!-- Actions -->
                                <td class="px-3 py-3 text-center">
                                    <div style="display: flex; gap: 0.25rem; justify-content: center; align-items: center;">
                                        <!-- زر رفع مرفق -->
                                        <button type="button" 
                                                class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 p-2 rounded transition-colors" 
                                                onclick="triggerTurnkeyFileUpload({{ $revenue->id }})"
                                                title="رفع مرفق">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                        </button>
                                        <input type="file" id="turnkeyFileInput_{{ $revenue->id }}" style="display: none;" onchange="uploadTurnkeyAttachment({{ $revenue->id }}, this)">
                                        
                                        <!-- زر عرض المرفق (يظهر فقط إذا كان هناك مرفق) -->
                                        @if($revenue->attachment_path)
                                        <a href="{{ $revenue->attachment_path }}" 
                                           target="_blank" 
                                           class="text-green-600 hover:text-green-800 hover:bg-green-50 p-2 rounded transition-colors" 
                                           title="عرض المرفق">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        @endif
                                        
                                        <!-- زر الحذف -->
                                        <button type="button" 
                                                class="text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded transition-colors" 
                                                onclick="deleteTurnkeyRow({{ $revenue->id }})"
                                                title="حذف">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="emptyTurnkeyRow">
                                <td colspan="25" class="text-center py-8 text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <p>لا توجد إيرادات بعد. اضغط "Add New Row" للبدء.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Folder & Upload Revenues -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="p-8">
                <h2 class="text-xl font-bold text-green-900 mb-6 text-left">Folders and Attachments Management</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Create Folder -->
                    <div class="bg-gradient-to-br from-green-50 to-white rounded-xl p-6 border-2 border-green-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
            </div>
                            <h3 class="text-lg font-semibold text-green-900">Create New Folder</h3>
        </div>

                        <form action="{{ route('projects.revenues.create-folder', $project) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="folder_name" class="block text-sm font-medium text-green-700 mb-2 text-left">Folder Name</label>
                                <input type="text" 
                                       id="folder_name" 
                                       name="folder_name" 
                                       required
                                       class="w-full px-4 py-3 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-left"
                                       placeholder="Example: Extracts">
                                @error('folder_name')
                                    <p class="mt-1 text-sm text-red-600 text-left">{{ $message }}</p>
                                @enderror
                </div>
                            
                            <div>
                                <label for="folder_description" class="block text-sm font-medium text-green-700 mb-2 text-left">Description (Optional)</label>
                                <textarea id="folder_description" 
                                          name="folder_description" 
                                          rows="2"
                                          class="w-full px-4 py-3 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-left"
                                          placeholder="Folder description..."></textarea>
            </div>

                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-sm transition duration-150 ease-in-out flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                                Create Folder
                            </button>
                        </form>
                </div>
                    
                    <!-- Upload Multiple Files -->
                    <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl p-6 border-2 border-blue-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                            <h3 class="text-lg font-semibold text-green-900">Upload Multiple Attachments</h3>
                        </div>

                        <form action="{{ route('projects.revenues.upload-files', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="folder_id" class="block text-sm font-medium text-green-700 mb-2 text-left">Select Folder</label>
                                <select id="folder_id" 
                                        name="folder_id" 
                                        class="w-full px-4 py-3 border border-green-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-left">
                                    <option value="">Main Folder</option>
                                    @foreach($folders as $folder)
                                    <option value="{{ $folder['name'] }}">{{ $folder['name'] }} ({{ $folder['file_count'] }} files)</option>
                                    @endforeach
                                </select>
                        </div>

                            <div>
                                <label for="files" class="block text-sm font-medium text-green-700 mb-2 text-left">
                                    Select Files
                                    <span class="text-xs text-green-500">(You can select multiple files)</span>
                                </label>
                                <div class="relative">
                                    <input type="file" 
                                           id="files" 
                                           name="files[]" 
                                           multiple
                                           required
                                           class="hidden"
                                           onchange="updateFileList(this)">
                                    <label for="files" 
                                           class="flex items-center justify-center w-full px-4 py-8 border-2 border-dashed border-green-300 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all">
                                        <div class="text-center">
                                            <svg class="w-10 h-10 text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                            <p class="text-sm text-green-600">Click to select files</p>
                                            <p class="text-xs text-green-500 mt-1">or drag and drop files here</p>
                                </div>
                                    </label>
                            </div>
                                <div id="file-list" class="mt-3 space-y-2"></div>
                                @error('files')
                                    <p class="mt-1 text-sm text-red-600 text-left">{{ $message }}</p>
                                @enderror
                        </div>

                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-sm transition duration-150 ease-in-out flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                Upload Files
                            </button>
                        </form>
                                </div>
                            </div>
                        </div>
                    </div>

        <!-- Folders and Files List -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-green-900 text-left">Folders and Files</h2>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-green-500">
                            {{ count($folders) }} Folders | {{ count($files) }} Files
                        </span>
                            </div>
                        </div>

                @if(count($folders) > 0 || count($files) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- المجلدات -->
                        @foreach($folders as $folder)
                        <div class="bg-gradient-to-br from-green-50 to-white border-2 border-green-100 rounded-xl p-4 hover:shadow-lg transition-all duration-300 hover:scale-105 cursor-pointer">
                            <div class="flex items-start mb-3">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center ml-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-green-900 mb-1 truncate text-left">{{ $folder['name'] }}</h3>
                                    @if($folder['description'])
                                    <p class="text-xs text-green-500 line-clamp-2 text-left">{{ $folder['description'] }}</p>
                                    @endif
                            </div>
                        </div>

                            <div class="flex items-center justify-between text-sm pt-3 border-t border-green-100">
                                <span class="text-green-500">{{ $folder['file_count'] }} Files</span>
                                <span class="text-green-400 text-xs">{{ $folder['created_at'] }}</span>
                        </div>

                            <div class="flex items-center gap-2 mt-3">
                                <a href="{{ route('projects.revenues.folder', ['project' => $project, 'folderName' => $folder['name']]) }}" class="flex-1 px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition-colors text-center">
                                    <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                        </svg>
                                    Open Folder
                                </a>
                                </div>
                                </div>
                        @endforeach
                        
                        <!-- الملفات في المجلد الرئيسي -->
                        @foreach($files as $file)
                        <div class="bg-gradient-to-br from-blue-50 to-white border-2 border-blue-100 rounded-xl p-4 hover:shadow-lg transition-all duration-300 hover:scale-105">
                            <div class="flex items-start mb-3">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center ml-3 flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-green-900 mb-1 truncate text-left">{{ $file['name'] }}</h3>
                                    <p class="text-xs text-green-500 text-left">{{ number_format($file['size'] / 1024, 2) }} KB</p>
                    </div>
                </div>
                
                            <div class="flex items-center justify-between text-xs pt-3 border-t border-blue-100">
                                <span class="text-green-400">{{ $file['created_at'] }}</span>
                    </div>
                    </div>
                        @endforeach
                </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                        <h3 class="text-lg font-semibold text-green-900 mb-2">No Folders or Files</h3>
                        <p class="text-green-600">Start by creating a new folder or uploading files</p>
                    </div>
                @endif
                </div>
            </div>
            
            </div>
        </div>

<!-- Modal: تفاصيل المبلغ المتبقي -->
<div class="modal fade" id="remainingDetailsModal" tabindex="-1" aria-labelledby="remainingDetailsModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); position: relative; z-index: 9999;">
            <div class="modal-header" style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title" id="remainingDetailsModalLabel">
                    <i class="fas fa-chart-pie me-2"></i>
                    تفاصيل المبلغ المتبقي عند العميل
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <div class="row g-2">
                    <!-- عند المقاول -->
                    <div class="col-md-6">
                        <div class="card" style="border: 2px solid #f59e0b; border-radius: 10px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);">
                            <div class="card-body text-center py-2 px-2">
                                <div style="font-size: 1.5rem; margin-bottom: 0.25rem;">
                                    <i class="fas fa-user-tie" style="color: #f59e0b;"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: #78350f; font-size: 0.9rem;">عند المقاول</h6>
                                <div class="fs-4 fw-bold" style="color: #b45309;" id="contractorAmount">0.00</div>
                                <small class="text-muted" style="font-size: 0.75rem;">ريال</small>
                            </div>
                        </div>
                    </div>

                    <!-- عند إدارة الكهرباء -->
                    <div class="col-md-6">
                        <div class="card" style="border: 2px solid #3b82f6; border-radius: 10px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);">
                            <div class="card-body text-center py-2 px-2">
                                <div style="font-size: 1.5rem; margin-bottom: 0.25rem;">
                                    <i class="fas fa-bolt" style="color: #3b82f6;"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: #1e3a8a; font-size: 0.9rem;">عند إدارة الكهرباء</h6>
                                <div class="fs-4 fw-bold" style="color: #1d4ed8;" id="electricityAmount">0.00</div>
                                <small class="text-muted" style="font-size: 0.75rem;">ريال</small>
                            </div>
                        </div>
                    </div>

                    <!-- عند المالية -->
                    <div class="col-md-6">
                        <div class="card" style="border: 2px solid #10b981; border-radius: 10px; background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);">
                            <div class="card-body text-center py-2 px-2">
                                <div style="font-size: 1.5rem; margin-bottom: 0.25rem;">
                                    <i class="fas fa-coins" style="color: #10b981;"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: #064e3b; font-size: 0.9rem;">عند المالية</h6>
                                <div class="fs-4 fw-bold" style="color: #047857;" id="financeAmount">0.00</div>
                                <small class="text-muted" style="font-size: 0.75rem;">ريال</small>
                            </div>
                        </div>
                    </div>

                    <!-- عند الخزينة -->
                    <div class="col-md-6">
                        <div class="card" style="border: 2px solid #8b5cf6; border-radius: 10px; background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);">
                            <div class="card-body text-center py-2 px-2">
                                <div style="font-size: 1.5rem; margin-bottom: 0.25rem;">
                                    <i class="fas fa-university" style="color: #8b5cf6;"></i>
                                </div>
                                <h6 class="fw-bold mb-1" style="color: #4c1d95; font-size: 0.9rem;">عند الخزينة</h6>
                                <div class="fs-4 fw-bold" style="color: #6d28d9;" id="treasuryAmount">0.00</div>
                                <small class="text-muted" style="font-size: 0.75rem;">ريال</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- المجموع الكلي -->
                <div class="mt-3">
                    <div class="card" style="border: 2px solid #ea580c; border-radius: 10px; background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);">
                        <div class="card-body text-center py-2">
                            <h6 class="fw-bold mb-1" style="color: #9a3412; font-size: 0.95rem;">
                                <i class="fas fa-calculator me-1"></i>
                                المجموع الإجمالي المتبقي
                            </h6>
                            <div class="fs-3 fw-bold" style="color: #ea580c;" id="totalRemainingModal">0.00</div>
                            <small class="text-muted" style="font-size: 0.75rem;">ريال سعودي (شامل الضريبة)</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #f3f4f6;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// متغير للتحقق من صلاحية التعديل
const canEdit = {{ $canEdit ? 'true' : 'false' }};

// حماية جميع الحقول عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    if (!canEdit) {
        // تعطيل جميع inputs في الجدول
        document.querySelectorAll('#turnkeyRevenuesTableBody input').forEach(input => {
            input.setAttribute('readonly', 'readonly');
            input.style.backgroundColor = '#f8f9fa';
            input.style.cursor = 'not-allowed';
        });
        
        // تعطيل جميع selects في الجدول
        document.querySelectorAll('#turnkeyRevenuesTableBody select').forEach(select => {
            select.setAttribute('disabled', 'disabled');
            select.style.backgroundColor = '#f8f9fa';
            select.style.cursor = 'not-allowed';
        });
        
        // تعطيل جميع textareas في الجدول
        document.querySelectorAll('#turnkeyRevenuesTableBody textarea').forEach(textarea => {
            textarea.setAttribute('readonly', 'readonly');
            textarea.style.backgroundColor = '#f8f9fa';
            textarea.style.cursor = 'not-allowed';
        });
        
        console.log('Turnkey Revenues: Fields protected - No edit permission');
    }
});

function updateFileList(input) {
    const fileList = document.getElementById('file-list');
    fileList.innerHTML = '';
    
    if (input.files.length > 0) {
        Array.from(input.files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200';
            fileItem.innerHTML = `
                <div class="flex items-center flex-1">
                    <svg class="w-5 h-5 text-blue-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-green-900 text-left">${file.name}</p>
                        <p class="text-xs text-green-500 text-left">${formatFileSize(file.size)}</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            `;
            fileList.appendChild(fileItem);
        });
        
        // إضافة ملخص
        const summary = document.createElement('div');
        summary.className = 'mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200';
        summary.innerHTML = `
            <p class="text-sm text-blue-700 font-medium text-right">
                تم اختيار ${input.files.length} ${input.files.length === 1 ? 'ملف' : 'ملفات'}
            </p>
        `;
        fileList.appendChild(summary);
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Turnkey Revenues - Calculate tax value automatically (15%)
function calculateTaxValue(tr) {
    const extractValueInput = tr.querySelector('input[data-field="extract_value"]');
    const taxValueInput = tr.querySelector('input[data-field="tax_value"]');
    
    if (extractValueInput && taxValueInput) {
        const extractValue = parseFloat(extractValueInput.value) || 0;
        
        // Formula: tax_value = extract_value * 0.15
        const taxValue = extractValue * 0.15;
        
        taxValueInput.value = taxValue.toFixed(2);
        
        // Add visual indicator for calculated value
        taxValueInput.style.backgroundColor = '#fef3c7';
    }
}

// Turnkey Revenues - Calculate net extract value automatically
function calculateNetExtractValue(tr) {
    const extractValueInput = tr.querySelector('input[data-field="extract_value"]');
    const taxValueInput = tr.querySelector('input[data-field="tax_value"]');
    const penaltiesInput = tr.querySelector('input[data-field="penalties"]');
    const netExtractValueInput = tr.querySelector('input[data-field="net_extract_value"]');
    
    if (extractValueInput && taxValueInput && penaltiesInput && netExtractValueInput) {
        const extractValue = parseFloat(extractValueInput.value) || 0;
        const taxValue = parseFloat(taxValueInput.value) || 0;
        const penalties = parseFloat(penaltiesInput.value) || 0;
        
        // Formula: extract_value + tax_value - penalties
        const netValue = extractValue + taxValue - penalties;
        
        netExtractValueInput.value = netValue.toFixed(2);
        
        // Add visual indicator for calculated value
        netExtractValueInput.style.backgroundColor = '#fef3c7';
    }
}

// Drag and drop support
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.querySelector('label[for="files"]');
    const fileInput = document.getElementById('files');
    
    if (dropZone && fileInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight(e) {
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        }
        
        function unhighlight(e) {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        }
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            updateFileList(fileInput);
        }
    }
    
    // Calculate tax and net extract values for existing rows on page load
    const turnkeyRows = document.querySelectorAll('#turnkeyRevenuesTable tbody tr[data-id]');
    turnkeyRows.forEach(row => {
        if (row.dataset.id && row.dataset.id !== 'new') {
            calculateTaxValue(row);
            calculateNetExtractValue(row);
        }
    });
    
    // Update statistics on page load
    updateTurnkeyStatistics();
});

// Turnkey Revenues Auto-Save Functions
function autoSaveTurnkey(element) {
    // التحقق من صلاحية التعديل
    if (!canEdit) {
        console.log('لا يوجد صلاحية للتعديل');
        return;
    }
    
    const tr = element.closest('tr');
    const id = tr.dataset.id;
    const field = element.dataset.field;
    const value = element.value;
    
    // Calculate tax value (15%) when extract_value changes
    if (field === 'extract_value') {
        calculateTaxValue(tr);
        calculateNetExtractValue(tr);
    }
    // Calculate net extract value if tax or penalties changed
    else if (field === 'tax_value' || field === 'penalties') {
        calculateNetExtractValue(tr);
    }
    
    // Visual indicator
    element.classList.add('bg-yellow-50');
    
    let data = {
        _token: '{{ csrf_token() }}',
        project_id: '{{ $project->id }}'  // ربط المشروع تلقائياً
    };
    
    // إذا كان صف جديد، اجمع كل البيانات من الصف
    if (!id || id === 'new') {
        const inputs = tr.querySelectorAll('input[data-field], select[data-field]');
        inputs.forEach(input => {
            const fieldName = input.dataset.field;
            const fieldValue = input.value;
            if (fieldValue) { // فقط إذا كان فيه قيمة
                data[fieldName] = fieldValue;
            }
        });
    } else {
        // إذا كان صف موجود، حدث الـ field المعدل
        data[field] = value;
        
        // إذا تم تعديل extract_value، احفظ أيضاً القيم المحسوبة تلقائياً
        if (field === 'extract_value') {
            const taxValueInput = tr.querySelector('input[data-field="tax_value"]');
            const netExtractValueInput = tr.querySelector('input[data-field="net_extract_value"]');
            if (taxValueInput) data['tax_value'] = taxValueInput.value;
            if (netExtractValueInput) data['net_extract_value'] = netExtractValueInput.value;
        }
        // إذا تم تعديل tax_value أو penalties، احفظ net_extract_value المحسوب
        else if (field === 'tax_value' || field === 'penalties') {
            const netExtractValueInput = tr.querySelector('input[data-field="net_extract_value"]');
            if (netExtractValueInput) data['net_extract_value'] = netExtractValueInput.value;
        }
    }
    
    const url = id && id !== 'new' 
        ? `/admin/turnkey-revenues/${id}/update`
        : '/admin/turnkey-revenues/store';
    
    console.log('Saving turnkey revenue:', {
        url: url,
        id: id,
        data: data
    });
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            element.classList.remove('bg-yellow-50');
            element.classList.add('bg-green-50');
            setTimeout(() => {
                element.classList.remove('bg-green-50');
            }, 1000);
            
            // Update row ID if it was new
            if (!id || id === 'new') {
                tr.dataset.id = result.data.id;
                console.log('Row saved with ID:', result.data.id);
            }
            
            // Update statistics
            updateTurnkeyStatistics();
        } else {
            element.classList.remove('bg-yellow-50');
            element.classList.add('bg-red-50');
            console.error('Error saving:', result.message);
            alert('Error saving data: ' + result.message);
        }
    })
    .catch(error => {
        element.classList.remove('bg-yellow-50');
        element.classList.add('bg-red-50');
        console.error('Error:', error);
        alert('Error saving data. Check console for details.');
    });
}

function addNewTurnkeyRow() {
    const tbody = document.getElementById('turnkeyRevenuesTableBody');
    const emptyRow = document.getElementById('emptyTurnkeyRow');
    
    if (emptyRow) {
        emptyRow.remove();
    }
    
    const rowCount = tbody.querySelectorAll('tr').length + 1;
    const newRow = document.createElement('tr');
    newRow.className = 'border-b hover:bg-gray-50';
    newRow.dataset.id = 'new';
    
    newRow.innerHTML = `
        <td class="px-3 py-3 text-center font-medium text-gray-600">${rowCount}</td>
        <td class="px-3 py-3 border-x border-gray-200"><input type="text" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" data-field="client_name" onchange="autoSaveTurnkey(this)" placeholder="اسم العميل"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="text" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" data-field="project" onchange="autoSaveTurnkey(this)" placeholder="المشروع"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="text" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" data-field="contract_number" onchange="autoSaveTurnkey(this)" placeholder="رقم العقد"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="text" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" data-field="extract_number" onchange="autoSaveTurnkey(this)" placeholder="رقم المستخلص"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="text" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" data-field="extract_type" onchange="autoSaveTurnkey(this)" placeholder="نوع المستخلص"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="text" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" data-field="po_number" onchange="autoSaveTurnkey(this)" placeholder="رقم PO"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="text" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" data-field="invoice_number" onchange="autoSaveTurnkey(this)" placeholder="رقم الفاتورة"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="text" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" data-field="location" onchange="autoSaveTurnkey(this)" placeholder="الموقع"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="number" step="0.01" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all text-right" data-field="extract_value" onchange="autoSaveTurnkey(this)" placeholder="0.00"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="number" step="0.01" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all text-right bg-yellow-50" data-field="tax_value" onchange="autoSaveTurnkey(this)" placeholder="0.00" readonly title="محسوب تلقائياً: قيمة المستخلصات × 15%"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="number" step="0.01" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all text-right" data-field="penalties" onchange="autoSaveTurnkey(this)" placeholder="0.00"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="number" step="0.01" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all text-right bg-yellow-50" data-field="net_extract_value" onchange="autoSaveTurnkey(this)" placeholder="0.00" readonly title="محسوب تلقائياً: قيمة المستخلص + قيمة الضريبة - الغرامات"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="date" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" data-field="extract_date" onchange="autoSaveTurnkey(this)"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="number" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all text-right" data-field="year" onchange="autoSaveTurnkey(this)" placeholder="${new Date().getFullYear()}"></td>
        <td class="px-3 py-3 border-r border-gray-200">
            <select class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" data-field="extract_status" onchange="autoSaveTurnkey(this)">
                
                <option value="المقاول">المقاول</option>
                <option value="ادارة الكهرباء">ادارة الكهرباء</option>
                <option value="المالية">المالية</option>
                <option value="الخزينة">الخزينة</option>
                <option value="تم الصرف">تم الصرف</option>
            </select>
        </td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="text" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" data-field="reference_number" onchange="autoSaveTurnkey(this)" placeholder="الرقم المرجعي"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="date" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" data-field="payment_date" onchange="autoSaveTurnkey(this)"></td>
        <td class="px-3 py-3 border-r border-gray-200"><input type="number" step="0.01" class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all text-right" data-field="payment_value" onchange="autoSaveTurnkey(this)" placeholder="0.00"></td>
        <td class="px-3 py-3 border-r border-gray-200">
            <select class="w-full px-2 py-1.5 border border-gray-300 rounded focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all" data-field="payment_status" onchange="autoSaveTurnkey(this)">
                
                <option value="مدفوع">مدفوع</option>
                <option value="غير مدفوع">غير مدفوع</option>
            </select>
        </td>
        <td class="px-3 py-3 text-center">
            <button type="button" class="text-red-600 hover:text-red-800 hover:bg-red-50 p-2 rounded transition-colors" onclick="deleteTurnkeyRow('new')" title="حذف">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
}

// Trigger file upload for Turnkey Revenue
function triggerTurnkeyFileUpload(id) {
    document.getElementById(`turnkeyFileInput_${id}`).click();
}

// Upload attachment for Turnkey Revenue
async function uploadTurnkeyAttachment(id, fileInput) {
    const file = fileInput.files[0];
    
    if (!file) return;
    
    const formData = new FormData();
    formData.append('attachment', file);
    formData.append('revenue_id', id);
    
    try {
        const response = await fetch('/admin/turnkey-revenues/upload-attachment', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('تم رفع المرفق بنجاح');
            // Reload the page to show the new attachment
            location.reload();
        } else {
            alert('حدث خطأ أثناء رفع المرفق: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ أثناء رفع المرفق');
    }
    
    // Clear the input
    fileInput.value = '';
}

function deleteTurnkeyRow(id) {
    if (!confirm('Are you sure you want to delete this row?')) {
        return;
    }
    
    if (id === 'new') {
        const row = document.querySelector(`tr[data-id="new"]`);
        row.remove();
        
        // Update statistics
        updateTurnkeyStatistics();
        return;
    }
    
    fetch(`/admin/turnkey-revenues/${id}/delete`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            row.remove();
            
            // Check if table is empty
            const tbody = document.getElementById('turnkeyRevenuesTableBody');
            if (tbody.querySelectorAll('tr').length === 0) {
                tbody.innerHTML = `
                    <tr id="emptyTurnkeyRow">
                        <td colspan="25" class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <p>لا توجد إيرادات بعد. اضغط "Add New Row" للبدء.</p>
                        </td>
                    </tr>
                `;
            }
            
            // Update statistics
            updateTurnkeyStatistics();
        } else {
            alert('Error deleting row: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting row');
    });
}

// Calculate and update statistics
function updateTurnkeyStatistics() {
    const rows = document.querySelectorAll('#turnkeyRevenuesTableBody tr[data-id]');
    
    let stats = {
        count: 0,
        totalExtractValue: 0,
        totalTax: 0,
        totalPenalties: 0,
        totalNetValue: 0,
        totalPayments: 0
    };
    
    let unpaidNetValue = 0; // لحساب المبلغ المتبقي عند العميل (فقط الغير مدفوع)
    let paidNetValue = 0; // لحساب إجمالي المدفوعات (فقط المدفوع)
    
    rows.forEach(row => {
        const id = row.dataset.id;
        if (id && id !== 'new') {
            stats.count++;
            
            // Get values from inputs
            const extractValue = parseFloat(row.querySelector('input[data-field="extract_value"]')?.value) || 0;
            const taxValue = parseFloat(row.querySelector('input[data-field="tax_value"]')?.value) || 0;
            const penalties = parseFloat(row.querySelector('input[data-field="penalties"]')?.value) || 0;
            const netValue = parseFloat(row.querySelector('input[data-field="net_extract_value"]')?.value) || 0;
            const paymentValue = parseFloat(row.querySelector('input[data-field="payment_value"]')?.value) || 0;
            const paymentStatus = row.querySelector('select[data-field="payment_status"]')?.value || '';
            
            stats.totalExtractValue += extractValue;
            stats.totalTax += taxValue;
            stats.totalPenalties += penalties;
            stats.totalNetValue += netValue;
            
            // احسب صافي قيمة المستخلص للسجلات الغير مدفوعة فقط
            if (paymentStatus === 'غير مدفوع') {
                unpaidNetValue += netValue;
            }
            
            // احسب صافي قيمة المستخلص للسجلات المدفوعة فقط (إجمالي المدفوعات)
            if (paymentStatus === 'مدفوع') {
                paidNetValue += netValue;
            }
        }
    });
    
    // Calculate payments (فقط المستخلصات المدفوعة)
    stats.totalPayments = paidNetValue;
    
    // Calculate remaining amount (فقط المستخلصات الغير مدفوعة)
    const remainingAmount = unpaidNetValue;
    
    // Format numbers with commas
    const formatter = new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    // Update statistics in DOM
    document.getElementById('stat_total_count').textContent = stats.count;
    document.getElementById('stat_total_extract_value').textContent = formatter.format(stats.totalExtractValue);
    document.getElementById('stat_total_tax').textContent = formatter.format(stats.totalTax);
    document.getElementById('stat_total_penalties').textContent = formatter.format(stats.totalPenalties);
    document.getElementById('stat_net_value').textContent = formatter.format(stats.totalNetValue);
    document.getElementById('stat_total_payments').textContent = formatter.format(stats.totalPayments);
    document.getElementById('stat_remaining_amount').textContent = formatter.format(remainingAmount);
}

// وظيفة إظهار نافذة تفاصيل المبلغ المتبقي
function showRemainingDetailsModal() {
    // حساب المبالغ المتبقية حسب موقف المستخلص
    let contractorAmount = 0;
    let electricityAmount = 0;
    let financeAmount = 0;
    let treasuryAmount = 0;
    
    const tbody = document.getElementById('turnkeyRevenuesTableBody');
    const rows = tbody.querySelectorAll('tr[data-id]');
    
    rows.forEach(row => {
        const id = row.dataset.id;
        if (id && id !== 'new') {
            // حساب صافي القيمة
            const extractValue = parseFloat(row.querySelector('[data-field="extract_value"]')?.value) || 0;
            const taxValue = parseFloat(row.querySelector('[data-field="tax_value"]')?.value) || 0;
            const penalties = parseFloat(row.querySelector('[data-field="penalties"]')?.value) || 0;
            const netValue = extractValue + taxValue - penalties;
            
            // جلب حالة الدفع
            const paymentStatus = row.querySelector('[data-field="payment_status"]')?.value || '';
            
            // إذا كان غير مدفوع، احسب المبلغ المتبقي حسب الموقف
            if (paymentStatus === 'غير مدفوع' || paymentStatus === 'unpaid' || paymentStatus === 'pending' || paymentStatus === '') {
                const extractStatus = row.querySelector('[data-field="extract_status"]')?.value || '';
                
                if (extractStatus === 'المقاول') {
                    contractorAmount += netValue;
                } else if (extractStatus === 'ادارة الكهرباء') {
                    electricityAmount += netValue;
                } else if (extractStatus === 'المالية') {
                    financeAmount += netValue;
                } else if (extractStatus === 'الخزينة') {
                    treasuryAmount += netValue;
                }
            }
        }
    });
    
    // المجموع الكلي
    const totalRemaining = contractorAmount + electricityAmount + financeAmount + treasuryAmount;
    
    // تحديث قيم المودال
    document.getElementById('contractorAmount').textContent = contractorAmount.toFixed(2);
    document.getElementById('electricityAmount').textContent = electricityAmount.toFixed(2);
    document.getElementById('financeAmount').textContent = financeAmount.toFixed(2);
    document.getElementById('treasuryAmount').textContent = treasuryAmount.toFixed(2);
    document.getElementById('totalRemainingModal').textContent = totalRemaining.toFixed(2);
    
    // إظهار المودال
    const modal = new bootstrap.Modal(document.getElementById('remainingDetailsModal'));
    modal.show();
}
</script>
@endpush
@endsection
