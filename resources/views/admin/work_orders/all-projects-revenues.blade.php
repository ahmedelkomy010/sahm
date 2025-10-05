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
                        <div class="stat-title">صافي قيمة المستخلصات غير شامل الضريبة</div>
                        <div class="stat-value">{{ number_format($grandTotal['total_value'] - $grandTotal['total_penalties'], 2) }}</div>
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
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-title">المبلغ المتبقي عند العميل</div>
                        <div class="stat-value">{{ number_format(($grandTotal['total_value'] + $grandTotal['total_tax']) - $grandTotal['total_payments'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="stat-title">ضريبة الدفعة الأولى</div>
                        <div class="stat-value">{{ number_format($grandTotal['first_payment_tax'] ?? 0, 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- مشروع الرياض -->
        <div class="project-section">
            <div class="section-title riyadh">
                <i class="fas fa-building me-2"></i>
                مشروع الرياض
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
                        <div class="stat-title">إجمالي قيمة المستخلصات</div>
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
                        <div class="stat-value">{{ number_format($workOrdersStats['riyadh']['total_value'] - $workOrdersStats['riyadh']['total_penalties'], 2) }}</div>
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
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-title">المبلغ المتبقي عند العميل</div>
                        <div class="stat-value">{{ number_format(($workOrdersStats['riyadh']['total_value'] + $workOrdersStats['riyadh']['total_tax']) - $workOrdersStats['riyadh']['total_payments'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- مشروع المدينة المنورة -->
        <div class="project-section">
            <div class="section-title madinah">
                <i class="fas fa-mosque me-2"></i>
                مشروع المدينة المنورة
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
                        <div class="stat-title">إجمالي قيمة المستخلصات</div>
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
                        <div class="stat-value">{{ number_format($workOrdersStats['madinah']['total_value'] - $workOrdersStats['madinah']['total_penalties'], 2) }}</div>
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
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="stat-title">المبلغ المتبقي عند العميل</div>
                        <div class="stat-value">{{ number_format(($workOrdersStats['madinah']['total_value'] + $workOrdersStats['madinah']['total_tax']) - $workOrdersStats['madinah']['total_payments'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
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
                        <div class="stat-title">إجمالي قيمة المستخلصات</div>
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
            </div>
        </div>

        <!-- مشاريع تسليم المفتاح - التفاصيل لكل مشروع -->
        @foreach($turnkeyProjectsStats as $projectStat)
        <div class="project-section">
            <div class="section-title turnkey">
                <i class="fas fa-building me-2"></i>
                {{ $projectStat['project_name'] }}
                <small style="font-size: 0.8rem; opacity: 0.8; margin-right: 10px;">
                    ({{ $projectStat['project_type'] }} - {{ $projectStat['contract_number'] }})
                </small>
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
                        <div class="stat-title">صافي قيمة المستخلصات غير شامل الضريبة</div>
                        <div class="stat-value">{{ number_format($projectStat['total_value'] - $projectStat['total_penalties'], 2) }}</div>
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
                        <div class="stat-title">المبلغ المتبقي عند العميل</div>
                        <div class="stat-value">{{ number_format(($projectStat['total_value'] + $projectStat['total_tax']) - $projectStat['total_payments'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="stat-title">ضريبة الدفعة الأولى</div>
                        <div class="stat-value">{{ number_format($projectStat['first_payment_tax'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
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
                        <div class="stat-title">إجمالي قيمة المستخلصات</div>
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
            </div>
        </div>

        <!-- المشاريع الخاصة - التفاصيل لكل مشروع -->
        @foreach($specialProjectsStats as $projectStat)
        <div class="project-section">
            <div class="section-title special">
                <i class="fas fa-star-half-alt me-2"></i>
                {{ $projectStat['project_name'] }}
                <small style="font-size: 0.8rem; opacity: 0.8; margin-right: 10px;">
                    ({{ $projectStat['contract_number'] }} - {{ $projectStat['location'] }})
                </small>
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
                        <div class="stat-title">صافي قيمة المستخلصات غير شامل الضريبة</div>
                        <div class="stat-value">{{ number_format($projectStat['total_value'] - $projectStat['total_penalties'], 2) }}</div>
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
                        <div class="stat-title">المبلغ المتبقي عند العميل</div>
                        <div class="stat-value">{{ number_format(($projectStat['total_value'] + $projectStat['total_tax']) - $projectStat['total_payments'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
                
                <div class="stat-card-small p-2">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="stat-title">ضريبة الدفعة الأولى</div>
                        <div class="stat-value">{{ number_format($projectStat['first_payment_tax'], 2) }}</div>
                        <div class="stat-currency">ريال سعودي</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection

