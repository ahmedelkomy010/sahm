@extends('layouts.app')

@section('title', 'إيرادات المشاريع')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        margin: 0 auto 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }
    
    .stat-title {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .stat-value {
        font-size: 1.8rem;
        font-weight: bold;
        color: #2d3748;
        margin-bottom: 5px;
        direction: ltr;
    }
    
    .stat-currency {
        font-size: 0.85rem;
        color: #888;
        font-weight: 500;
    }
    
    .section-title {
        color: white;
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 20px;
        text-align: center;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.15);
        padding: 15px 20px;
        border-radius: 12px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    /* ألوان مختلفة للعناوين */
    .section-title.grand-total {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.3), rgba(118, 75, 162, 0.3));
        border-color: rgba(102, 126, 234, 0.5);
    }
    
    .section-title.riyadh {
        background: linear-gradient(135deg, rgba(240, 147, 251, 0.3), rgba(245, 87, 108, 0.3));
        border-color: rgba(240, 147, 251, 0.5);
    }
    
    .section-title.madinah {
        background: linear-gradient(135deg, rgba(79, 172, 254, 0.3), rgba(0, 242, 254, 0.3));
        border-color: rgba(79, 172, 254, 0.5);
    }
    
    .section-title.turnkey {
        background: linear-gradient(135deg, rgba(67, 233, 123, 0.3), rgba(56, 249, 215, 0.3));
        border-color: rgba(67, 233, 123, 0.5);
    }
    
    .section-title.special {
        background: linear-gradient(135deg, rgba(250, 112, 154, 0.3), rgba(254, 225, 64, 0.3));
        border-color: rgba(250, 112, 154, 0.5);
    }
    
    .project-section {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    
    .page-header {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 30px;
        text-align: center;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .page-header h1 {
        color: white;
        font-size: 2.5rem;
        font-weight: bold;
        margin: 0;
        text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.3);
    }
    
    /* تخصيص عرض 8 كروت في صف واحد */
    .stat-card-small {
        flex: 0 0 12.5%;
        max-width: 12.5%;
    }
    
    @media (max-width: 1400px) {
        .stat-card-small {
            flex: 0 0 25%;
            max-width: 25%;
        }
    }
    
    @media (max-width: 992px) {
        .stat-card-small {
            flex: 0 0 33.333%;
            max-width: 33.333%;
        }
    }
    
    @media (max-width: 768px) {
        .stat-card-small {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
    
    .stat-card {
        padding: 12px 8px;
        min-height: 165px;
    }
    
    .stat-icon {
        width: 35px;
        height: 35px;
        font-size: 18px;
        margin-bottom: 8px;
    }
    
    .stat-title {
        font-size: 0.7rem;
        margin-bottom: 6px;
        min-height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1.2;
    }
    
    .stat-value {
        font-size: 1.3rem;
        margin-bottom: 3px;
    }
    
    .stat-currency {
        font-size: 0.7rem;
    }
    
    /* Filter Section Styles */
    .filter-section {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }
    
    .filter-section .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 8px;
    }
    
    .filter-section .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    
    .filter-section .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .quick-date-btn {
        padding: 8px 16px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        background: white;
        color: #4a5568;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .quick-date-btn:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
        transform: translateY(-2px);
    }
    
    .filter-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 30px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }
    
    .reset-btn {
        background: #e2e8f0;
        color: #4a5568;
        padding: 10px 30px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .reset-btn:hover {
        background: #cbd5e0;
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="py-8">
    <div class="container-fluid px-3" style="max-width: 100%;">
        
        <!-- Header -->
        <div class="page-header">
            <h1>
                <i class="fas fa-chart-line me-3"></i>
             اجمالي إيرادات المشاريع 
            </h1>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.all-projects-revenues') }}" id="filterForm">
                <div class="row g-3 align-items-end">
                    <!-- Start Date -->
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="fas fa-calendar-alt me-2"></i>
                            تاريخ البداية
                        </label>
                        <input type="date" 
                               name="start_date" 
                               id="start_date"
                               class="form-control" 
                               value="{{ request('start_date') }}">
                    </div>
                    
                    <!-- End Date -->
                    <div class="col-md-3">
                        <label class="form-label">
                            <i class="fas fa-calendar-alt me-2"></i>
                            تاريخ النهاية
                        </label>
                        <input type="date" 
                               name="end_date" 
                               id="end_date"
                               class="form-control" 
                               value="{{ request('end_date') }}">
                    </div>
                    
                    <!-- Filter Buttons -->
                    <div class="col-md-6">
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="filter-btn">
                                <i class="fas fa-filter me-2"></i>
                                فلترة
                            </button>
                            <a href="{{ route('admin.all-projects-revenues') }}" class="reset-btn">
                                <i class="fas fa-redo me-2"></i>
                                إعادة تعيين
                            </a>
                            <a href="{{ route('admin.all-projects-revenues.export', request()->all()) }}" class="btn btn-success">
                                <i class="fas fa-file-excel me-2"></i>
                                تصدير Excel
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Date Range Buttons -->
                <div class="row mt-3">
                    <div class="col-12">
                        <label class="form-label d-block mb-2">
                            <i class="fas fa-clock me-2"></i>
                            فترات زمنية سريعة
                        </label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="quick-date-btn" onclick="setQuickDateRange('today')">
                                <i class="fas fa-calendar-day me-1"></i> اليوم
                            </button>
                            <button type="button" class="quick-date-btn" onclick="setQuickDateRange('week')">
                                <i class="fas fa-calendar-week me-1"></i> أسبوع
                            </button>
                            <button type="button" class="quick-date-btn" onclick="setQuickDateRange('month')">
                                <i class="fas fa-calendar me-1"></i> شهر
                            </button>
                            <button type="button" class="quick-date-btn" onclick="setQuickDateRange('quarter')">
                                <i class="fas fa-calendar-alt me-1"></i> ربع سنة
                            </button>
                            <button type="button" class="quick-date-btn" onclick="setQuickDateRange('half')">
                                <i class="fas fa-calendar-plus me-1"></i> نصف سنة
                            </button>
                            <button type="button" class="quick-date-btn" onclick="setQuickDateRange('year')">
                                <i class="fas fa-calendar-check me-1"></i> سنة
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- الإجمالي العام -->
        <div class="project-section">
            <div class="section-title grand-total">
                <i class="fas fa-calculator me-2"></i>
                الإجمالي العام لجميع المشاريع
            </div>
            
            <div class="row g-2">
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-list-ol"></i>
                        </div>
                        <div class="stat-title">عدد المستخلصات</div>
                        <div class="stat-value">{{ number_format($grandTotal['count']) }}</div>
                        <div class="stat-currency">مستخلص</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="stat-title">إجمالي قيمة المستخلصات غير شامل الضريبة</div>
                        <div class="stat-value">{{ number_format($grandTotal['total_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div class="stat-title">إجمالي الضريبة</div>
                        <div class="stat-value">{{ number_format($grandTotal['total_tax'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-title">إجمالي الغرامات</div>
                        <div class="stat-value">{{ number_format($grandTotal['total_penalties'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <div class="stat-title">صافي قيمة المستخلصات</div>
                        <div class="stat-value">{{ number_format($grandTotal['total_net_extract_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="stat-title">إجمالي المدفوعات</div>
                        <div class="stat-value">{{ number_format($grandTotal['total_payments'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card" onclick="showGrandTotalRemainingDetailsModal()" style="cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-title">المبلغ المتبقي عند العميل<br>شامل الضريبة</div>
                        <div class="stat-value">{{ number_format($grandTotal['unpaid_amount'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                        <small class="text-muted" style="font-size: 0.7rem; display: block; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> اضغط للتفاصيل
                        </small>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <!-- <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-heart"></i>
                        </div>
                        <div class="stat-title">الإجمالي العام عند العميل</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['riyadh']['first_payment_tax'] + $workOrdersStats['madinah']['first_payment_tax'] + $grandTotal['unpaid_amount'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- مشروع الرياض -->
        <div class="project-section">
            <div class="section-title riyadh d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-building me-2"></i>
                    مشروع الرياض
                </div>
                <a href="{{ route('admin.work-orders.revenues', ['project' => 'riyadh']) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-eye me-1"></i>
                    عرض الإيرادات
                </a>
            </div>
            
            <div class="row g-2">
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-list-ol"></i>
                        </div>
                        <div class="stat-title">عدد المستخلصات</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['riyadh']['count']) }}</div>
                        <div class="stat-currency">مستخلص</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="stat-title">إجمالي قيمة المستخلصات غير شامل الضريبة </div>
                        <div class="stat-value">{{ number_format($workOrdersStats['riyadh']['total_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div class="stat-title">إجمالي الضريبة</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['riyadh']['total_tax'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-title">إجمالي الغرامات</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['riyadh']['total_penalties'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <div class="stat-title">صافي قيمة المستخلصات</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['riyadh']['total_net_extract_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="stat-title">إجمالي المدفوعات</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['riyadh']['total_payments'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card" onclick="showRiyadhRemainingDetailsModal()" style="cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-title">المبلغ المتبقي عند العميل<br>شامل الضريبة</div>
                        <div class="stat-value">{{ number_format(
                            $workOrdersStats['riyadh']['remaining_by_status']['contractor'] +
                            $workOrdersStats['riyadh']['remaining_by_status']['electricity'] +
                            $workOrdersStats['riyadh']['remaining_by_status']['finance'] +
                            $workOrdersStats['riyadh']['remaining_by_status']['treasury'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                        <small class="text-muted" style="font-size: 0.7rem; display: block; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> اضغط للتفاصيل
                        </small>
                    </div>
                </div>
                
                <!-- <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="stat-title">المتبقي لدي العميل (ضريبة الدفعة الاولي)</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['riyadh']['first_payment_tax'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div> -->
                
                <!-- <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="stat-title">الاجمالي لدي العميل (شامل الضريبة + ضريبة الدفعة الاولي)</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['riyadh']['unpaid_amount'] + $workOrdersStats['riyadh']['first_payment_tax'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div> -->
            </div>
        </div>

        <!-- مشروع المدينة المنورة -->
        <div class="project-section">
            <div class="section-title madinah d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-mosque me-2"></i>
                    مشروع المدينة المنورة
                </div>
                <a href="{{ route('admin.work-orders.revenues', ['project' => 'madinah']) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-eye me-1"></i>
                    عرض الإيرادات
                </a>
            </div>
            
            <div class="row g-2">
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-list-ol"></i>
                        </div>
                        <div class="stat-title">عدد المستخلصات</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['madinah']['count']) }}</div>
                        <div class="stat-currency">مستخلص</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="stat-title"> إجمالي قيمة المستخلصات غير شامل الضريبة</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['madinah']['total_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div class="stat-title">إجمالي الضريبة</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['madinah']['total_tax'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-title">إجمالي الغرامات</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['madinah']['total_penalties'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <div class="stat-title">صافي قيمة المستخلصات</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['madinah']['total_net_extract_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="stat-title">إجمالي المدفوعات</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['madinah']['total_payments'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card" onclick="showMadinahRemainingDetailsModal()" style="cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-title">المبلغ المتبقي عند العميل<br>شامل الضريبة</div>
                        <div class="stat-value">{{ number_format(
                            $workOrdersStats['madinah']['remaining_by_status']['contractor'] +
                            $workOrdersStats['madinah']['remaining_by_status']['electricity'] +
                            $workOrdersStats['madinah']['remaining_by_status']['finance'] +
                            $workOrdersStats['madinah']['remaining_by_status']['treasury'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                        <small class="text-muted" style="font-size: 0.7rem; display: block; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> اضغط للتفاصيل
                        </small>
                    </div>
                </div>
                
                <!-- <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="stat-title">المتبقي لدي العميل (ضريبة الدفعة الاولي)</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['madinah']['first_payment_tax'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div> -->
                
                <!-- <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="stat-title">الاجمالي لدي العميل (شامل الضريبة + ضريبة الدفعة الاولي)</div>
                        <div class="stat-value">{{ number_format($workOrdersStats['madinah']['unpaid_amount'] + $workOrdersStats['madinah']['first_payment_tax'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div> -->
            </div>
        </div>

        <!-- مشاريع تسليم المفتاح - الإجمالي -->
        <div class="project-section">
            <div class="section-title turnkey">
                <i class="fas fa-key me-2"></i>
                مشاريع تسليم المفتاح - الإجمالي الكلي
            </div>
            
            <div class="row g-2">
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-list-ol"></i>
                        </div>
                        <div class="stat-title">عدد المستخلصات</div>
                        <div class="stat-value">{{ number_format($turnkeyStats['count']) }}</div>
                        <div class="stat-currency">مستخلص</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="stat-title">إجمالي قيمة المستخلصات غير شامل الضريبة</div>
                        <div class="stat-value">{{ number_format($turnkeyStats['total_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div class="stat-title">إجمالي الضريبة</div>
                        <div class="stat-value">{{ number_format($turnkeyStats['total_tax'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-title">إجمالي الغرامات</div>
                        <div class="stat-value">{{ number_format($turnkeyStats['total_penalties'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <div class="stat-title">صافي قيمة المستخلصات</div>
                        <div class="stat-value">{{ number_format($turnkeyStats['total_net_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="stat-title">إجمالي المدفوعات</div>
                        <div class="stat-value">{{ number_format($turnkeyStats['total_payments'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card" onclick="showTurnkeyRemainingDetailsModal()" style="cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-title">المبلغ المتبقي عند العميل<br>شامل الضريبة</div>
                        <div class="stat-value">{{ number_format(
                            $turnkeyStats['remaining_by_status']['contractor'] +
                            $turnkeyStats['remaining_by_status']['electricity'] +
                            $turnkeyStats['remaining_by_status']['finance'] +
                            $turnkeyStats['remaining_by_status']['treasury'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                        <small class="text-muted" style="font-size: 0.7rem; display: block; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> اضغط للتفاصيل
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- مشاريع تسليم المفتاح - التفاصيل لكل مشروع -->
        @foreach($turnkeyProjectsStats as $projectStat)
        <div class="project-section">
            <div class="section-title turnkey d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <i class="fas fa-building me-2"></i>
                    {{ $projectStat['project_name'] }}
                    <small style="font-size: 0.8rem; opacity: 0.8; margin-right: 10px;">
                        ({{ $projectStat['project_type'] }} - {{ $projectStat['contract_number'] }})
                    </small>
                </div>
                @if(isset($projectStat['project_id']))
                <a href="{{ route('projects.revenues', ['project' => $projectStat['project_id']]) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-eye me-1"></i>
                    عرض الإيرادات
                </a>
                @endif
            </div>
            
            <div class="row g-2">
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-list-ol"></i>
                        </div>
                        <div class="stat-title">عدد المستخلصات</div>
                        <div class="stat-value">{{ number_format($projectStat['count']) }}</div>
                        <div class="stat-currency">مستخلص</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="stat-title">إجمالي قيمة المستخلصات غير شامل الضريبة</div>
                        <div class="stat-value">{{ number_format($projectStat['total_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div class="stat-title">إجمالي الضريبة</div>
                        <div class="stat-value">{{ number_format($projectStat['total_tax'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-title">إجمالي الغرامات</div>
                        <div class="stat-value">{{ number_format($projectStat['total_penalties'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <div class="stat-title">صافي قيمة المستخلصات</div>
                        <div class="stat-value">{{ number_format($projectStat['total_net_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="stat-title">إجمالي المدفوعات</div>
                        <div class="stat-value">{{ number_format($projectStat['total_payments'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card" onclick="showTurnkeyProjectRemainingDetailsModal({{ $loop->index }})" style="cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-title">المبلغ المتبقي عند العميل<br>شامل الضريبة</div>
                        <div class="stat-value">{{ number_format(
                            $projectStat['remaining_by_status']['contractor'] +
                            $projectStat['remaining_by_status']['electricity'] +
                            $projectStat['remaining_by_status']['finance'] +
                            $projectStat['remaining_by_status']['treasury'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                        <small class="text-muted" style="font-size: 0.7rem; display: block; margin-top: 5px;">
                            <i class="fas fa-info-circle"></i> اضغط للتفاصيل
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- المشاريع الخاصة - الإجمالي -->
        <div class="project-section">
            <div class="section-title special">
                <i class="fas fa-star me-2"></i>
                المشاريع الخاصة - الإجمالي الكلي
            </div>
            
            <div class="row g-2">
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-list-ol"></i>
                        </div>
                        <div class="stat-title">عدد المستخلصات</div>
                        <div class="stat-value">{{ number_format($specialStats['count']) }}</div>
                        <div class="stat-currency">مستخلص</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="stat-title">إجمالي قيمة المستخلصات غير شامله الضريبه</div>
                        <div class="stat-value">{{ number_format($specialStats['total_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div class="stat-title">إجمالي الضريبة</div>
                        <div class="stat-value">{{ number_format($specialStats['total_tax'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-title">إجمالي الغرامات</div>
                        <div class="stat-value">{{ number_format($specialStats['total_penalties'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <div class="stat-title">صافي قيمة المستخلصات</div>
                        <div class="stat-value">{{ number_format($specialStats['total_net_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="stat-title">إجمالي المدفوعات</div>
                        <div class="stat-value">{{ number_format($specialStats['total_payments'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-title">المبلغ المتبقي عند العميل<br>شامل الضريبة</div>
                        <div class="stat-value">{{ number_format($specialStats['unpaid_amount'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- المشاريع الخاصة - التفاصيل لكل مشروع -->
        @foreach($specialProjectsStats as $projectStat)
        <div class="project-section">
            <div class="section-title special d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <i class="fas fa-star-half-alt me-2"></i>
                    {{ $projectStat['project_name'] }}
                    <small style="font-size: 0.8rem; opacity: 0.8; margin-right: 10px;">
                        ({{ $projectStat['contract_number'] }} - {{ $projectStat['location'] }})
                    </small>
                </div>
                @if(isset($projectStat['project_id']))
                <a href="{{ route('admin.special-projects.revenues', ['project' => $projectStat['project_id']]) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-eye me-1"></i>
                    عرض الإيرادات
                </a>
                @endif
            </div>
            
            <div class="row g-2">
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-list-ol"></i>
                        </div>
                        <div class="stat-title">عدد المستخلصات</div>
                        <div class="stat-value">{{ number_format($projectStat['count']) }}</div>
                        <div class="stat-currency">مستخلص</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="stat-title">إجمالي قيمة المستخلصات غير شامله الضريبه</div>
                        <div class="stat-value">{{ number_format($projectStat['total_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div class="stat-title">إجمالي الضريبة</div>
                        <div class="stat-value">{{ number_format($projectStat['total_tax'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-title">إجمالي الغرامات</div>
                        <div class="stat-value">{{ number_format($projectStat['total_penalties'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-money-check-alt"></i>
                        </div>
                        <div class="stat-title">صافي قيمة المستخلصات</div>
                        <div class="stat-value">{{ number_format($projectStat['total_net_value'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="stat-title">إجمالي المدفوعات</div>
                        <div class="stat-value">{{ number_format($projectStat['total_payments'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-title">المبلغ المتبقي عند العميل<br>شامل الضريبة</div>
                        <div class="stat-value">{{ number_format($projectStat['unpaid_amount'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

@push('scripts')
<script>
// Set quick date range
function setQuickDateRange(range) {
    const today = new Date();
    let startDate = new Date();
    let endDate = new Date();
    
    switch(range) {
        case 'today':
            startDate = new Date();
            endDate = new Date();
            break;
        case 'week':
            startDate.setDate(today.getDate() - 7);
            break;
        case 'month':
            startDate.setMonth(today.getMonth() - 1);
            break;
        case 'quarter':
            startDate.setMonth(today.getMonth() - 3);
            break;
        case 'half':
            startDate.setMonth(today.getMonth() - 6);
            break;
        case 'year':
            startDate.setFullYear(today.getFullYear() - 1);
            break;
    }
    
    // Format dates to YYYY-MM-DD
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
    
    document.getElementById('start_date').value = formatDate(startDate);
    document.getElementById('end_date').value = formatDate(endDate);
    
    // Auto submit form
    document.getElementById('filterForm').submit();
}

// وظيفة إظهار نافذة تفاصيل المبلغ المتبقي للإجمالي العام
function showGrandTotalRemainingDetailsModal() {
    const modal = new bootstrap.Modal(document.getElementById('grandTotalRemainingDetailsModal'));
    modal.show();
}

// وظيفة إظهار نافذة تفاصيل المبلغ المتبقي لمشروع الرياض
function showRiyadhRemainingDetailsModal() {
    const modal = new bootstrap.Modal(document.getElementById('riyadhRemainingDetailsModal'));
    modal.show();
}

// وظيفة إظهار نافذة تفاصيل المبلغ المتبقي لمشروع المدينة
function showMadinahRemainingDetailsModal() {
    const modal = new bootstrap.Modal(document.getElementById('madinahRemainingDetailsModal'));
    modal.show();
}

// وظيفة إظهار نافذة تفاصيل المبلغ المتبقي لمشاريع تسليم المفتاح (الإجمالي)
function showTurnkeyRemainingDetailsModal() {
    const modal = new bootstrap.Modal(document.getElementById('turnkeyRemainingDetailsModal'));
    modal.show();
}

// وظيفة إظهار نافذة تفاصيل المبلغ المتبقي لمشروع فردي في تسليم المفتاح
function showTurnkeyProjectRemainingDetailsModal(index) {
    const modal = new bootstrap.Modal(document.getElementById('turnkeyProjectRemainingDetailsModal' + index));
    modal.show();
}

// وظيفة تنسيق الأرقام
function formatNumber(num) {
    return new Intl.NumberFormat('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(num);
}
</script>

<!-- Modal: تفاصيل المبلغ المتبقي للإجمالي العام لجميع المشاريع -->
<div class="modal fade" id="grandTotalRemainingDetailsModal" tabindex="-1" aria-labelledby="grandTotalRemainingDetailsModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="position: relative; z-index: 1056;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="modal-title" id="grandTotalRemainingDetailsModalLabel">
                    <i class="fas fa-chart-pie me-2"></i>
                    تفاصيل المبلغ المتبقي عند العميل - الإجمالي العام لجميع المشاريع
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: #f8f9fa; padding: 1.5rem;">
                <div class="row g-3">
                    <!-- المبلغ المتبقي عند العميل شامل الضريبة (بدون المشاريع الخاصة) -->
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">المبلغ المتبقي عند العميل شامل الضريبة</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format(
                                            $grandTotal['remaining_by_status']['contractor'] +
                                            $grandTotal['remaining_by_status']['electricity'] +
                                            $grandTotal['remaining_by_status']['finance'] +
                                            $grandTotal['remaining_by_status']['treasury'] +
                                            $grandTotal['grand_first_payment_tax'], 2) }}</h4>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                        <i class="fas fa-hourglass-half fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- المبالغ حسب موقف المستخلص -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند المقاول</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($grandTotal['remaining_by_status']['contractor'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-hard-hat fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند إدارة الكهرباء</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($grandTotal['remaining_by_status']['electricity'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-bolt fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند المالية</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($grandTotal['remaining_by_status']['finance'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-coins fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند الخزينة</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($grandTotal['remaining_by_status']['treasury'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-university fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- المتبقي لدى العميل (ضريبة الدفعة الأولى) -->
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">المتبقي لدى العميل (ضريبة الدفعة الأولى)</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format($grandTotal['grand_first_payment_tax'], 2) }}</h4>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                        <i class="fas fa-percentage fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إجمالي أعمال خاصة -->
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #fc6076 0%, #ff9a44 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">إجمالي أعمال خاصة</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format($specialStats['unpaid_amount'], 2) }}</h4>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                        <i class="fas fa-star fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إجمالي المتبقي (شامل ضريبة الدفعة الأولى - بدون المشاريع الخاصة) -->
                    <div class="col-md-12">
                        <div class="alert alert-info mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-calculator me-2"></i>
                                        إجمالي المبلغ المتبقي عند العميل (شامل ضريبة الدفعة الأولى)
                                    </h5>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold text-primary">{{ number_format(
                                        $grandTotal['remaining_by_status']['contractor'] +
                                        $grandTotal['remaining_by_status']['electricity'] +
                                        $grandTotal['remaining_by_status']['finance'] +
                                        $grandTotal['remaining_by_status']['treasury'] +
                                        $grandTotal['grand_first_payment_tax'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: تفاصيل المبلغ المتبقي لمشروع الرياض -->
<div class="modal fade" id="riyadhRemainingDetailsModal" tabindex="-1" aria-labelledby="riyadhRemainingDetailsModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="position: relative; z-index: 1056;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white;">
                <h5 class="modal-title" id="riyadhRemainingDetailsModalLabel">
                    <i class="fas fa-chart-pie me-2"></i>
                    تفاصيل المبلغ المتبقي عند العميل - مشروع الرياض
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: #f8f9fa; padding: 1.5rem;">
                <div class="row g-3">
                    <!-- المبلغ المتبقي عند العميل شامل الضريبة (مع ضريبة الدفعة الأولى) -->
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">المبلغ المتبقي عند العميل شامل الضريبة</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format(
                                            $workOrdersStats['riyadh']['remaining_by_status']['contractor'] +
                                            $workOrdersStats['riyadh']['remaining_by_status']['electricity'] +
                                            $workOrdersStats['riyadh']['remaining_by_status']['finance'] +
                                            $workOrdersStats['riyadh']['remaining_by_status']['treasury'] +
                                            $workOrdersStats['riyadh']['first_payment_tax'], 2) }}</h4>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                        <i class="fas fa-hourglass-half fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- المبالغ حسب موقف المستخلص -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند المقاول</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($workOrdersStats['riyadh']['remaining_by_status']['contractor'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-hard-hat fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند إدارة الكهرباء</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($workOrdersStats['riyadh']['remaining_by_status']['electricity'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-bolt fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند المالية</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($workOrdersStats['riyadh']['remaining_by_status']['finance'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-coins fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند الخزينة</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($workOrdersStats['riyadh']['remaining_by_status']['treasury'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-university fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- المتبقي لدى العميل (ضريبة الدفعة الأولى) -->
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">المتبقي لدى العميل (ضريبة الدفعة الأولى)</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format($workOrdersStats['riyadh']['first_payment_tax'], 2) }}</h4>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                        <i class="fas fa-percentage fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إجمالي المتبقي (شامل ضريبة الدفعة الأولى) -->
                    <div class="col-md-12">
                        <div class="alert alert-info mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-calculator me-2"></i>
                                        إجمالي المبلغ المتبقي عند العميل
                                    </h5>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold text-primary">{{ number_format(
                                        $workOrdersStats['riyadh']['remaining_by_status']['contractor'] +
                                        $workOrdersStats['riyadh']['remaining_by_status']['electricity'] +
                                        $workOrdersStats['riyadh']['remaining_by_status']['finance'] +
                                        $workOrdersStats['riyadh']['remaining_by_status']['treasury'] +
                                        $workOrdersStats['riyadh']['first_payment_tax'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: تفاصيل المبلغ المتبقي لمشروع المدينة المنورة -->
<div class="modal fade" id="madinahRemainingDetailsModal" tabindex="-1" aria-labelledby="madinahRemainingDetailsModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="position: relative; z-index: 1056;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white;">
                <h5 class="modal-title" id="madinahRemainingDetailsModalLabel">
                    <i class="fas fa-chart-pie me-2"></i>
                    تفاصيل المبلغ المتبقي عند العميل - مشروع المدينة المنورة
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: #f8f9fa; padding: 1.5rem;">
                <div class="row g-3">
                    <!-- المبلغ المتبقي عند العميل شامل الضريبة (مع ضريبة الدفعة الأولى) -->
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">المبلغ المتبقي عند العميل شامل الضريبة</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format(
                                            $workOrdersStats['madinah']['remaining_by_status']['contractor'] +
                                            $workOrdersStats['madinah']['remaining_by_status']['electricity'] +
                                            $workOrdersStats['madinah']['remaining_by_status']['finance'] +
                                            $workOrdersStats['madinah']['remaining_by_status']['treasury'] +
                                            $workOrdersStats['madinah']['first_payment_tax'], 2) }}</h4>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                        <i class="fas fa-hourglass-half fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- المبالغ حسب موقف المستخلص -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند المقاول</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($workOrdersStats['madinah']['remaining_by_status']['contractor'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-hard-hat fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند إدارة الكهرباء</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($workOrdersStats['madinah']['remaining_by_status']['electricity'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-bolt fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند المالية</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($workOrdersStats['madinah']['remaining_by_status']['finance'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-coins fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند الخزينة</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($workOrdersStats['madinah']['remaining_by_status']['treasury'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-university fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- المتبقي لدى العميل (ضريبة الدفعة الأولى) -->
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">المتبقي لدى العميل (ضريبة الدفعة الأولى)</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format($workOrdersStats['madinah']['first_payment_tax'], 2) }}</h4>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                        <i class="fas fa-percentage fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إجمالي المتبقي (شامل ضريبة الدفعة الأولى) -->
                    <div class="col-md-12">
                        <div class="alert alert-info mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-calculator me-2"></i>
                                        إجمالي المبلغ المتبقي عند العميل
                                    </h5>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold text-primary">{{ number_format(
                                        $workOrdersStats['madinah']['remaining_by_status']['contractor'] +
                                        $workOrdersStats['madinah']['remaining_by_status']['electricity'] +
                                        $workOrdersStats['madinah']['remaining_by_status']['finance'] +
                                        $workOrdersStats['madinah']['remaining_by_status']['treasury'] +
                                        $workOrdersStats['madinah']['first_payment_tax'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: تفاصيل المبلغ المتبقي لمشاريع تسليم المفتاح -->
<div class="modal fade" id="turnkeyRemainingDetailsModal" tabindex="-1" aria-labelledby="turnkeyRemainingDetailsModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="position: relative; z-index: 1056;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white;">
                <h5 class="modal-title" id="turnkeyRemainingDetailsModalLabel">
                    <i class="fas fa-chart-pie me-2"></i>
                    تفاصيل المبلغ المتبقي عند العميل - مشاريع تسليم المفتاح
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: #f8f9fa; padding: 1.5rem;">
                <div class="row g-3">
                    <!-- المبلغ المتبقي عند العميل شامل الضريبة -->
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">المبلغ المتبقي عند العميل شامل الضريبة</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format(
                                            $turnkeyStats['remaining_by_status']['contractor'] +
                                            $turnkeyStats['remaining_by_status']['electricity'] +
                                            $turnkeyStats['remaining_by_status']['finance'] +
                                            $turnkeyStats['remaining_by_status']['treasury'], 2) }}</h4>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                        <i class="fas fa-hourglass-half fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- المبالغ حسب موقف المستخلص -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند المقاول</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($turnkeyStats['remaining_by_status']['contractor'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-hard-hat fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند إدارة الكهرباء</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($turnkeyStats['remaining_by_status']['electricity'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-bolt fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند المالية</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($turnkeyStats['remaining_by_status']['finance'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-coins fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند الخزينة</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($turnkeyStats['remaining_by_status']['treasury'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-university fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إجمالي المتبقي -->
                    <div class="col-md-12">
                        <div class="alert alert-info mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-calculator me-2"></i>
                                        إجمالي المبلغ المتبقي عند العميل
                                    </h5>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold text-primary">{{ number_format(
                                        $turnkeyStats['remaining_by_status']['contractor'] +
                                        $turnkeyStats['remaining_by_status']['electricity'] +
                                        $turnkeyStats['remaining_by_status']['finance'] +
                                        $turnkeyStats['remaining_by_status']['treasury'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals: تفاصيل المبلغ المتبقي لكل مشروع فردي في تسليم المفتاح -->
@foreach($turnkeyProjectsStats as $index => $projectStat)
<div class="modal fade" id="turnkeyProjectRemainingDetailsModal{{ $index }}" tabindex="-1" aria-labelledby="turnkeyProjectRemainingDetailsModalLabel{{ $index }}" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="position: relative; z-index: 1056;">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white;">
                <h5 class="modal-title" id="turnkeyProjectRemainingDetailsModalLabel{{ $index }}">
                    <i class="fas fa-chart-pie me-2"></i>
                    تفاصيل المبلغ المتبقي عند العميل - {{ $projectStat['project_name'] }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background: #f8f9fa; padding: 1.5rem;">
                <div class="row g-3">
                    <!-- المبلغ المتبقي عند العميل شامل الضريبة -->
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">المبلغ المتبقي عند العميل شامل الضريبة</h6>
                                        <h4 class="mb-0 fw-bold">{{ number_format(
                                            $projectStat['remaining_by_status']['contractor'] +
                                            $projectStat['remaining_by_status']['electricity'] +
                                            $projectStat['remaining_by_status']['finance'] +
                                            $projectStat['remaining_by_status']['treasury'], 2) }}</h4>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                                        <i class="fas fa-hourglass-half fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- المبالغ حسب موقف المستخلص -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند المقاول</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($projectStat['remaining_by_status']['contractor'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-hard-hat fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند إدارة الكهرباء</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($projectStat['remaining_by_status']['electricity'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-bolt fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند المالية</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($projectStat['remaining_by_status']['finance'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-coins fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                            <div class="card-body text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 opacity-75">عند الخزينة</h6>
                                        <h5 class="mb-0 fw-bold">{{ number_format($projectStat['remaining_by_status']['treasury'], 2) }}</h5>
                                        <small class="opacity-75">ريال سعودي</small>
                                    </div>
                                    <div class="bg-white bg-opacity-25 p-2 rounded-circle">
                                        <i class="fas fa-university fa-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إجمالي المتبقي -->
                    <div class="col-md-12">
                        <div class="alert alert-info mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-calculator me-2"></i>
                                        إجمالي المبلغ المتبقي عند العميل
                                    </h5>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold text-primary">{{ number_format(
                                        $projectStat['remaining_by_status']['contractor'] +
                                        $projectStat['remaining_by_status']['electricity'] +
                                        $projectStat['remaining_by_status']['finance'] +
                                        $projectStat['remaining_by_status']['treasury'], 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@endpush

@endsection

