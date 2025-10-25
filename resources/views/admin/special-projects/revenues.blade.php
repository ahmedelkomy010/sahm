@extends('layouts.app')

@push('styles')
<style>
    .revenue-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .revenue-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        opacity: 0.3;
    }
    
    .revenue-table {
        background: white;
        border-radius: 16px;
        overflow-x: auto;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    
    .revenue-table table {
        width: 100%;
        min-width: 2800px;
        border-collapse: collapse;
    }
    
    .revenue-table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 0.5rem;
        text-align: right;
        font-weight: 600;
        font-size: 0.85rem;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .revenue-table td {
        padding: 0.5rem;
        border-bottom: 1px solid #e2e8f0;
        text-align: right;
        vertical-align: middle;
    }
    
    .revenue-table tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .revenue-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
        text-decoration: none;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .action-btn {
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-radius: 0.5rem;
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .action-btn svg {
        width: 1.25rem;
        height: 1.25rem;
    }
    
    .stats-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stats-label {
        font-size: 0.7rem;
        color: #6b7280;
        font-weight: 600;
        margin-bottom: 0.3rem;
        line-height: 1.2;
    }
    
    .stats-label svg {
        width: 1rem;
        height: 1rem;
    }
    
    .stats-value {
        font-size: 1.5rem;
        color: #111827;
        font-weight: 700;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6b7280;
    }
    
    .empty-state svg {
        margin: 0 auto 1.5rem;
    }
    
    input[type="text"],
    input[type="number"],
    input[type="date"],
    select,
    textarea {
        width: 100%;
        min-width: 120px;
        padding: 0.4rem;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }
    
    input[type="text"]:focus,
    input[type="number"]:focus,
    input[type="date"]:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .action-cell {
        min-width: 80px;
        text-align: center;
    }
</style>
@endpush

@section('content')
@php
    $canEdit = auth()->user()->is_admin || (is_array(auth()->user()->permissions) && in_array('edit_special_projects_revenues', auth()->user()->permissions));
@endphp

<div class="container-fluid px-4 py-4">
    <!-- تنبيه الصلاحيات -->
    @if(!$canEdit)
    <div class="alert alert-warning mb-3 rounded-3 shadow-sm" role="alert" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border: 2px solid #f59e0b;">
        <div class="d-flex align-items-center">
            <i class="fas fa-lock text-amber-600 fs-4 me-3"></i>
            <div>
                <strong class="text-amber-800">تنبيه:</strong>
                <span class="text-amber-700">ليس لديك صلاحية تعديل بيانات الإيرادات. يمكنك فقط عرض البيانات.</span>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Revenue Header -->
    <div class="revenue-header rounded-3 shadow mb-4">
        <div class="p-4 position-relative" style="z-index: 10;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="h2 fw-bold text-white mb-2">إيرادات المشروع</h1>
                    <p class="text-white mb-2" style="opacity: 0.9; font-size: 1.1rem;">{{ $project->name }}</p>
                    <p class="text-white small d-inline-block px-3 py-2 rounded" style="background-color: rgba(255, 255, 255, 0.2); font-family: monospace;">
                        {{ $project->contract_number }}
                    </p>
                </div>
                <a href="{{ route('admin.special-projects.show', $project) }}" class="btn btn-light d-flex align-items-center gap-2" style="background-color: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); color: white; backdrop-filter: blur(10px);">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    العودة للمشروع
                </a>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end align-items-center mb-3 gap-2">
            <a href="{{ route('admin.special-projects.revenues.export', $project) }}" class="action-btn btn-primary">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                تصدير Excel
            </a>
            
            @if($canEdit)
            <button onclick="addNewRevenue()" class="action-btn btn-success">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة إيراد جديد
            </button>
            @endif
    </div>

    <!-- Statistics Cards -->
    <div class="row g-2 mb-4">
        <!-- All Stats in One Row -->
        <div class="col-md-3 col-lg-2">
        <div class="stats-card">
            <div class="stats-label">
                    <svg style="width: 20px; height: 20px; display: inline-block; color: #7c3aed; margin-bottom: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <div style="font-size: 0.9rem;">عدد المستخلصات</div>
            </div>
            <div class="stats-value" style="font-size: 1.8rem;" id="totalRevenues">0</div>
            </div>
        </div>

        <div class="col-md-3 col-lg-2">
        <div class="stats-card">
            <div class="stats-label">
                    <svg style="width: 20px; height: 20px; display: inline-block; color: #6366f1; margin-bottom: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                    <div style="font-size: 0.85rem;">إجمالي قيمة المستخلصات<br>غير شامله الضريبه</div>
                </div>
                <div class="stats-value" style="font-size: 1.5rem; color: #6366f1;" id="totalValueWithoutTax">0.00 ريال</div>
            </div>
        </div>

        <div class="col-md-3 col-lg-2">
        <div class="stats-card">
            <div class="stats-label">
                    <svg style="width: 20px; height: 20px; display: inline-block; color: #eab308; margin-bottom: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                </svg>
                <div style="font-size: 0.9rem;">إجمالي الضريبة</div>
            </div>
                <div class="stats-value" style="font-size: 1.5rem; color: #eab308;" id="totalTax">0.00 ريال</div>
            </div>
        </div>

        <div class="col-md-3 col-lg-2">
        <div class="stats-card">
            <div class="stats-label">
                    <svg style="width: 20px; height: 20px; display: inline-block; color: #dc2626; margin-bottom: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div style="font-size: 0.9rem;">إجمالي الغرامات</div>
            </div>
                <div class="stats-value" style="font-size: 1.5rem; color: #dc2626;" id="totalPenalties">0.00 ريال</div>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="col-md-3 col-lg-2">
        <div class="stats-card">
            <div class="stats-label">
                    <svg style="width: 20px; height: 20px; display: inline-block; color: #059669; margin-bottom: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                    <div style="font-size: 0.85rem;">صافي قيمة المستخلصات</div>
                </div>
                <div class="stats-value" style="font-size: 1.5rem; color: #059669;" id="netValueWithoutTax">0.00 ريال</div>
            </div>
        </div>

        <div class="col-md-3 col-lg-2">
        <div class="stats-card">
            <div class="stats-label">
                    <svg style="width: 20px; height: 20px; display: inline-block; color: #2563eb; margin-bottom: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                    <div style="font-size: 0.9rem;">إجمالي المدفوعات<br>شامل ضريبة</div>
                </div>
                <div class="stats-value" style="font-size: 1.5rem; color: #2563eb;" id="paidAmount">0.00 ريال</div>
            </div>
        </div>

        <div class="col-md-3 col-lg-2">
        <div class="stats-card" onclick="showRemainingDetailsModal()" style="cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            <div class="stats-label">
                    <svg style="width: 20px; height: 20px; display: inline-block; color: #ea580c; margin-bottom: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                    <div style="font-size: 0.85rem;">المبلغ المتبقي عند العميل<br>شامل الضريبة</div>
                </div>
                <div class="stats-value" style="font-size: 1.5rem; color: #ea580c;" id="remainingAmount">0.00 ريال</div>
                <div style="font-size: 0.7rem; color: #6b7280; margin-top: 5px;">
                    <i class="fas fa-info-circle"></i> اضغط للتفاصيل
                </div>
            </div>
        </div>

    </div>

    <!-- Revenues Table -->
    <div class="revenue-table">
        <table>
            <thead>
                <tr>
                    <th style="min-width: 40px;">#</th>
                    <th style="min-width: 150px;">اسم العميل</th>
                    <th style="min-width: 150px;">المشروع</th>
                    <th style="min-width: 130px;">رقم العقد</th>
                    <th style="min-width: 150px;">رقم المستخلص</th>
                    <th style="min-width: 120px;">رقم PO</th>
                    <th style="min-width: 130px;">رقم الفاتورة</th>
                    <th style="min-width: 180px;">إجمالي قيمة المستخلصات<br>غير شامله الضريبه</th>
                    <th style="min-width: 130px;">قيمة الضريبة</th>
                    <th style="min-width: 110px;">الغرامات</th>
                    <th style="min-width: 150px;">صافي قيمة المستخلص</th>
                    <th style="min-width: 130px;">تاريخ إعداد المستخلص</th>
                    <th style="min-width: 80px;">العام</th>
                    <th style="min-width: 180px;">موقف المستخلص عند ...</th>
                    <th style="min-width: 140px;">الرقم المرجعي</th>
                    <th style="min-width: 130px;">تاريخ الصرف</th>
                    <th style="min-width: 140px;">قيمة الصرف</th>
                    <th style="min-width: 130px;">حالة الصرف</th>
                    <th class="action-cell">حذف</th>
                </tr>
            </thead>
            <tbody id="revenuesTableBody">
                <tr>
                    <td colspan="23" class="empty-state">
                        <svg style="width: 80px; height: 80px; color: #d1d5db; margin-bottom: 10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="h5 fw-bold mb-2">لا توجد إيرادات</h3>
                        <p class="text-muted">ابدأ بإضافة أول إيراد للمشروع</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    
</div>
@endsection

@push('scripts')
<script>
    // متغير للتحقق من صلاحية التعديل
    const canEdit = {{ $canEdit ? 'true' : 'false' }};
    
    let revenues = @json($revenues ?? []);
    let revenueCounter = revenues.length;
    const projectId = {{ $project->id }};
    const csrfToken = '{{ csrf_token() }}';
    
    // Add new revenue
    async function addNewRevenue() {
        // التحقق من صلاحية التعديل
        if (!canEdit) {
            alert('ليس لديك صلاحية لإضافة إيرادات جديدة');
            return;
        }
        
        const currentYear = new Date().getFullYear();
        const newRevenue = {
            client_name: '',
            project: '{{ $project->name }}',
            contract_number: '{{ $project->contract_number }}',
            extract_number: '',
            po_number: '',
            invoice_number: '',
            total_value: 0,
            tax_value: 0,
            penalties: 0,
            net_value: 0,
            preparation_date: new Date().toISOString().split('T')[0],
            year: currentYear,
            extract_status: 'المقاول',
            reference_number: '',
            payment_date: null,
            payment_amount: 0,
            payment_status: 'unpaid'
        };
        
        try {
            const response = await fetch(`/admin/special-projects/${projectId}/revenues`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(newRevenue)
            });
            
            const data = await response.json();
            
            if (data.success) {
                revenues.push(data.revenue);
                renderRevenues();
            } else {
                alert('حدث خطأ أثناء إضافة الإيراد');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إضافة الإيراد');
        }
    }
    
    // Calculate net value and tax
    // قيمة الضريبة = إجمالي قيمة المستخلصات (غير شامله الضريبه) × 0.15
    // صافي قيمة المستخلص = إجمالي قيمة المستخلصات (غير شامله الضريبه) + قيمة الضريبة - الغرامات
    function calculateRevenue(index) {
        const revenue = revenues[index];
        const totalValue = parseFloat(revenue.total_value) || 0;
        
        // حساب قيمة الضريبة تلقائياً (15%)
        revenue.tax_value = (totalValue * 0.15).toFixed(2);
        
        const taxValue = parseFloat(revenue.tax_value) || 0;
        const penalties = parseFloat(revenue.penalties) || 0;
        
        // حساب صافي قيمة المستخلص
        revenue.net_value = (totalValue + taxValue - penalties).toFixed(2);
        
        renderRevenues();
        updateStatistics();
    }
    
    // Update field value with auto-save
    async function updateField(index, field, value) {
        // التحقق من صلاحية التعديل
        if (!canEdit) {
            console.log('لا يوجد صلاحية للتعديل');
            return;
        }
        
        // تحويل التواريخ الفارغة إلى null
        if ((field === 'preparation_date' || field === 'payment_date') && value === '') {
            value = null;
        }
        
        revenues[index][field] = value;
        
        console.log(`Updated ${field} to:`, value); // للتتبع
        
        // تحديث الحساب عند تغيير إجمالي القيمة أو الغرامات (سيحسب الضريبة والصافي تلقائياً)
        if (field === 'total_value' || field === 'penalties') {
            calculateRevenue(index);
        } 
        // إذا تم تغيير قيمة الضريبة يدوياً، احسب الصافي فقط
        else if (field === 'tax_value') {
            const revenue = revenues[index];
            const totalValue = parseFloat(revenue.total_value) || 0;
            const taxValue = parseFloat(revenue.tax_value) || 0;
            const penalties = parseFloat(revenue.penalties) || 0;
            revenue.net_value = (totalValue + taxValue - penalties).toFixed(2);
            renderRevenues();
            updateStatistics();
        } 
        else {
            renderRevenues();
        }
        
        // Auto-save to database
        await saveRevenue(index);
    }
    
    // Save revenue to database
    async function saveRevenue(index) {
        const revenue = revenues[index];
        
        if (!revenue.id) {
            console.log('No revenue ID, skipping save');
            return;
        }
        
        // تنظيف البيانات: تحويل empty strings إلى null للتواريخ
        const cleanedRevenue = {...revenue};
        if (cleanedRevenue.preparation_date === '') cleanedRevenue.preparation_date = null;
        if (cleanedRevenue.payment_date === '') cleanedRevenue.payment_date = null;
        
        console.log('Saving revenue:', cleanedRevenue);
        
        try {
            const response = await fetch(`/admin/special-projects/${projectId}/revenues/${revenue.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(cleanedRevenue)
            });
            
            const data = await response.json();
            
            if (data.success) {
                console.log('Revenue saved successfully:', data);
            } else {
                console.error('Error saving:', data.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
    
    // Trigger file upload
    function triggerFileUpload(index) {
        document.getElementById(`fileInput_${index}`).click();
    }
    
    // Upload attachment
    async function uploadAttachment(index, fileInput) {
        const revenue = revenues[index];
        const file = fileInput.files[0];
        
        if (!file) return;
        
        if (!revenue.id) {
            alert('يجب حفظ الإيراد أولاً قبل رفع المرفق');
            return;
        }
        
        const formData = new FormData();
        formData.append('attachment', file);
        
        try {
            const response = await fetch(`/admin/special-projects/${projectId}/revenues/${revenue.id}/attachment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                revenues[index].attachment_path = data.attachment_path;
                renderRevenues();
                alert('تم رفع المرفق بنجاح');
            } else {
                alert('حدث خطأ أثناء رفع المرفق: ' + data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء رفع المرفق');
        }
        
        // مسح الـ input
        fileInput.value = '';
    }
    
    // Delete revenue
    async function deleteRevenue(index) {
        if (!confirm('هل أنت متأكد من حذف هذا الإيراد؟')) {
            return;
        }
        
        const revenue = revenues[index];
        
        if (!revenue.id) {
            revenues.splice(index, 1);
            renderRevenues();
            updateStatistics();
            return;
        }
        
        try {
            const response = await fetch(`/admin/special-projects/${projectId}/revenues/${revenue.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                revenues.splice(index, 1);
                renderRevenues();
                updateStatistics();
            } else {
                alert('حدث خطأ أثناء حذف الإيراد');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حذف الإيراد');
        }
    }
    
    // Helper function to format date
    function formatDate(dateValue) {
        if (!dateValue) return '';
        // إذا كانت القيمة object (Carbon date من backend)
        if (typeof dateValue === 'object' && dateValue.date) {
            return dateValue.date.split(' ')[0]; // أخذ الجزء الخاص بالتاريخ فقط
        }
        // إذا كانت string
        if (typeof dateValue === 'string') {
            return dateValue.split(' ')[0]; // أخذ الجزء الخاص بالتاريخ فقط
        }
        return '';
    }

    // Render revenues table
    function renderRevenues() {
        const tbody = document.getElementById('revenuesTableBody');
        
        if (revenues.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="19" class="empty-state">
                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-xl font-bold mb-2">لا توجد إيرادات</h3>
                        <p>ابدأ بإضافة أول إيراد للمشروع</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        const readonlyAttr = !canEdit ? 'readonly style="background-color:#f8f9fa;cursor:not-allowed;"' : '';
        const disabledAttr = !canEdit ? 'disabled style="background-color:#f8f9fa;cursor:not-allowed;"' : '';
        
        tbody.innerHTML = revenues.map((revenue, index) => `
            <tr>
                <td>${index + 1}</td>
                <td><input type="text" value="${revenue.client_name}" onchange="updateField(${index}, 'client_name', this.value)" placeholder="اسم العميل" ${readonlyAttr}></td>
                <td><input type="text" value="${revenue.project}" onchange="updateField(${index}, 'project', this.value)" placeholder="المشروع" ${readonlyAttr}></td>
                <td><input type="text" value="${revenue.contract_number}" onchange="updateField(${index}, 'contract_number', this.value)" placeholder="رقم العقد" ${readonlyAttr}></td>
                <td><input type="text" value="${revenue.extract_number}" onchange="updateField(${index}, 'extract_number', this.value)" placeholder="رقم المستخلص" ${readonlyAttr}></td>
                <td><input type="text" value="${revenue.po_number}" onchange="updateField(${index}, 'po_number', this.value)" placeholder="رقم PO" ${readonlyAttr}></td>
                <td><input type="text" value="${revenue.invoice_number}" onchange="updateField(${index}, 'invoice_number', this.value)" placeholder="رقم الفاتورة" ${readonlyAttr}></td>
                <td><input type="number" value="${revenue.total_value}" onchange="updateField(${index}, 'total_value', this.value)" step="0.01" placeholder="0.00" ${readonlyAttr}></td>
                <td><input type="number" value="${revenue.tax_value}" onchange="updateField(${index}, 'tax_value', this.value)" step="0.01" placeholder="0.00" style="background-color: #f0fdf4;" title="محسوب تلقائياً: إجمالي القيمة × 15%" ${readonlyAttr}></td>
                <td><input type="number" value="${revenue.penalties}" onchange="updateField(${index}, 'penalties', this.value)" step="0.01" placeholder="0.00" ${readonlyAttr}></td>
                <td style="background-color: #fef3c7;"><strong>${parseFloat(revenue.net_value).toFixed(2)}</strong></td>
                <td><input type="date" value="${formatDate(revenue.preparation_date)}" onchange="updateField(${index}, 'preparation_date', this.value)" ${readonlyAttr}></td>
                <td><input type="number" value="${revenue.year}" onchange="updateField(${index}, 'year', this.value)" placeholder="${new Date().getFullYear()}" ${readonlyAttr}></td>
                <td>
                    <select onchange="updateField(${index}, 'extract_status', this.value)" ${disabledAttr}>
                        <option value="المقاول" ${revenue.extract_status === 'المقاول' ? 'selected' : ''}>المقاول</option>
                        <option value="ادارة الكهرباء" ${revenue.extract_status === 'ادارة الكهرباء' ? 'selected' : ''}>ادارة الكهرباء</option>
                        <option value="المالية" ${revenue.extract_status === 'المالية' ? 'selected' : ''}>المالية</option>
                        <option value="الخزينة" ${revenue.extract_status === 'الخزينة' ? 'selected' : ''}>الخزينة</option>
                        <option value="تم الصرف" ${revenue.extract_status === 'تم الصرف' ? 'selected' : ''}>تم الصرف</option>
                    </select>
                </td>
                <td><input type="text" value="${revenue.reference_number}" onchange="updateField(${index}, 'reference_number', this.value)" placeholder="الرقم المرجعي" ${readonlyAttr}></td>
                <td><input type="date" value="${formatDate(revenue.payment_date)}" onchange="updateField(${index}, 'payment_date', this.value)" ${readonlyAttr}></td>
                <td><input type="number" value="${revenue.payment_amount}" onchange="updateField(${index}, 'payment_amount', this.value)" step="0.01" placeholder="0.00" ${readonlyAttr}></td>
                <td>
                    <select onchange="updateField(${index}, 'payment_status', this.value)" ${disabledAttr}>
                        <option value="unpaid" ${revenue.payment_status === 'unpaid' || revenue.payment_status === 'pending' ? 'selected' : ''}>غير مدفوع</option>
                        <option value="paid" ${revenue.payment_status === 'paid' ? 'selected' : ''}>مدفوع</option>
                    </select>
                </td>
                <td class="action-cell">
                    <div style="display: flex; gap: 0.25rem; justify-content: center;">
                        <button onclick="triggerFileUpload(${index})" class="action-btn btn-primary" style="padding: 0.35rem 0.75rem;" title="رفع مرفق">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </button>
                        <input type="file" id="fileInput_${index}" style="display: none;" onchange="uploadAttachment(${index}, this)">
                        ${revenue.attachment_path ? `
                        <a href="${revenue.attachment_path}" target="_blank" class="action-btn btn-success" style="padding: 0.35rem 0.75rem;" title="عرض المرفق">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        ` : ''}
                        ${canEdit ? `
                        <button onclick="deleteRevenue(${index})" class="action-btn btn-danger" style="padding: 0.35rem 0.75rem;" title="حذف">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                        ` : '<span class="text-muted small"><i class="fas fa-lock"></i></span>'}
                    </div>
                </td>
            </tr>
        `).join('');
        
        updateStatistics();
    }
    
    // Update statistics
    function updateStatistics() {
        // 1. عدد المستخلصات
        const totalRevenues = revenues.length;
        
        // 2. إجمالي قيمة المستخلصات غير شامله الضريبه (total_value)
        const totalValueWithoutTax = revenues.reduce((sum, r) => sum + (parseFloat(r.total_value) || 0), 0);
        
        // 3. إجمالي الضريبة (tax_value)
        const totalTax = revenues.reduce((sum, r) => sum + (parseFloat(r.tax_value) || 0), 0);
        
        // 4. إجمالي الغرامات (penalties)
        const totalPenalties = revenues.reduce((sum, r) => sum + (parseFloat(r.penalties) || 0), 0);
        
        // 5. صافي قيمة المستخلصات (total_value + tax_value - penalties)
        const netValueWithoutTax = revenues.reduce((sum, r) => sum + (parseFloat(r.net_value) || 0), 0);
        
        // 6. إجمالي المدفوعات = مجموع قيمة الصرف للمدفوع فقط (payment_amount)
        const paidAmount = revenues
            .filter(r => r.payment_status === 'paid')
            .reduce((sum, r) => sum + (parseFloat(r.payment_amount) || 0), 0);
        
        // 7. المبلغ المتبقي عند العميل = إجمالي صافي القيمة - إجمالي المدفوعات
        const remainingAmount = netValueWithoutTax - paidAmount;
        
        // Update UI
        document.getElementById('totalRevenues').textContent = totalRevenues;
        document.getElementById('totalValueWithoutTax').textContent = totalValueWithoutTax.toFixed(2) + ' ريال';
        document.getElementById('totalTax').textContent = totalTax.toFixed(2) + ' ريال';
        document.getElementById('totalPenalties').textContent = totalPenalties.toFixed(2) + ' ريال';
        document.getElementById('netValueWithoutTax').textContent = netValueWithoutTax.toFixed(2) + ' ريال';
        document.getElementById('paidAmount').textContent = paidAmount.toFixed(2) + ' ريال';
        document.getElementById('remainingAmount').textContent = remainingAmount.toFixed(2) + ' ريال';
    }
    
    // وظيفة إظهار نافذة تفاصيل المبلغ المتبقي
    // function showRemainingDetailsModal() {
    //     // حساب المبالغ المتبقية حسب حالة المستخلص
    //     let contractorAmount = 0;
    //     let electricityAmount = 0;
    //     let financeAmount = 0;
    //     let treasuryAmount = 0;
        
    //     revenues.forEach(revenue => {
    //         // حساب المبلغ المتبقي لكل مستخلص (صافي القيمة - المدفوع)
    //         const netValue = parseFloat(revenue.net_value) || 0;
    //         const paidAmount = revenue.payment_status === 'paid' ? (parseFloat(revenue.payment_amount) || 0) : 0;
    //         const remainingForThisRevenue = netValue - paidAmount;
            
    //         // إذا كان المبلغ المتبقي موجب، أضفه للفئة المناسبة
    //         if (remainingForThisRevenue > 0) {
    //             const status = revenue.extract_status || '';
                
    //             if (status === 'المقاول') {
    //                 contractorAmount += remainingForThisRevenue;
    //             } else if (status === 'ادارة الكهرباء') {
    //                 electricityAmount += remainingForThisRevenue;
    //             } else if (status === 'المالية') {
    //                 financeAmount += remainingForThisRevenue;
    //             } else if (status === 'الخزينة') {
    //                 treasuryAmount += remainingForThisRevenue;
    //             }
    //         }
    //     });
        
    //     // المجموع الكلي
    //     const totalRemaining = contractorAmount + electricityAmount + financeAmount + treasuryAmount;
        
    //     // تحديث قيم المودال
    //     document.getElementById('contractorAmount').textContent = contractorAmount.toFixed(2);
    //     document.getElementById('electricityAmount').textContent = electricityAmount.toFixed(2);
    //     document.getElementById('financeAmount').textContent = financeAmount.toFixed(2);
    //     document.getElementById('treasuryAmount').textContent = treasuryAmount.toFixed(2);
    //     document.getElementById('totalRemainingModal').textContent = totalRemaining.toFixed(2);
        
    //     // إظهار المودال
    //     const modal = new bootstrap.Modal(document.getElementById('remainingDetailsModal'));
    //     modal.show();
    // }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Special Project Revenues Page Loaded');
        console.log('Revenues loaded:', revenues.length);
        console.log('Revenues data:', revenues);
        renderRevenues();
        updateStatistics();
    });
</script>
@endpush

