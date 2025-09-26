@extends('layouts.app')

@section('title', 'إدارة الإيرادات')

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
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
        max-height: 70vh;
        overflow: auto;
    }

    .table {
        margin-bottom: 0;
        font-size: 0.75rem;
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
        font-size: 0.7rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        position: sticky;
        top: 0;
        z-index: 10;
        vertical-align: middle;
        line-height: 1.2;
    }

    .table tbody td {
        padding: 3px;
        border: 1px solid #e9ecef;
        vertical-align: middle;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        min-width: 75px;
        max-width: 130px;
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
        min-height: 24px;
        min-width: 65px;
        padding: 4px;
        border: 1px solid transparent;
        border-radius: 3px;
        background: transparent;
        transition: all 0.3s ease;
        cursor: text;
        display: block;
        width: 100%;
        font-size: 0.75rem;
        line-height: 1.2;
    }

    .editable-field:hover {
        background-color: rgba(13, 110, 253, 0.05);
        border-color: rgba(13, 110, 253, 0.2);
    }

    .editable-field:focus {
        outline: none;
        background-color: rgba(13, 110, 253, 0.1);
        border-color: #0d6efd;
        box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.1);
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
        border: none;
        border-radius: 3px;
        padding: 3px 4px;
        font-size: 0.75rem;
        width: 100%;
        background: transparent;
        cursor: pointer;
        min-height: 24px;
    }

    .date-input:focus {
        outline: none;
        border: none;
        box-shadow: none;
        background: rgba(13, 110, 253, 0.05);
    }

    .date-input:hover {
        border: none;
        background: rgba(13, 110, 253, 0.02);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table {
            font-size: 0.65rem;
            min-width: 1400px;
        }
        
        .editable-field {
            font-size: 0.65rem;
            min-height: 22px;
            padding: 3px;
        }

        .date-input {
            font-size: 0.65rem;
            min-height: 22px;
            padding: 2px 3px;
            border: none;
            background: transparent;
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
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <span class="fs-5">
                            <i class="fas fa-chart-line me-2"></i>
                            إدارة الإيرادات
                        </span>
                        <span class="badge bg-light text-dark ms-2">
                            <i class="fas fa-database me-1"></i>
                            قاعدة البيانات
                        </span>
                    </div>
                    <div class="auto-save-status">
                        <i class="fas fa-save me-1"></i>
                        حفظ تلقائي مُفعل
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- عدد النتائج وعناصر التحكم -->
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light border-bottom">
                        <div class="text-muted">
                            <i class="fas fa-list me-1"></i>
                            عدد السجلات: <span id="recordCount">{{ $revenues->count() }}</span>
                        </div>
                        
                        <div class="d-flex gap-2">
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
                                    <th>قيمة المستخلص</th>
                                    <th>نسبة الضريبة</th>
                                    <th>قيمة الضريبة</th>
                                    <th>الغرامات</th>
                                    <th>ضريبة الدفعة الأولى</th>
                                    <th>صافي قيمة المستخلص</th>
                                    <th class="date-col">تاريخ إعداد المستخلص</th>
                                    <th>العام</th>
                                    <th>نوع الدفع</th>
                                    <th>الرقم المرجعي</th>
                                    <th class="date-col">تاريخ الصرف</th>
                                    <th>قيمة الصرف</th>
                                    <th>حالة المستخلص</th>
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
                                            <div class="editable-field" contenteditable="true" data-field="contract_number" placeholder="رقم العقد">{{ $revenue->contract_number }}</div>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="extract_number" placeholder="رقم المستخلص">{{ $revenue->extract_number }}</div>
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
                                            <div class="editable-field" contenteditable="true" data-field="extract_value" placeholder="قيمة المستخلص">{{ $revenue->extract_value }}</div>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="tax_percentage" placeholder="نسبة الضريبة">{{ $revenue->tax_percentage }}</div>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="tax_value" placeholder="قيمة الضريبة">{{ $revenue->tax_value }}</div>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="penalties" placeholder="الغرامات">{{ $revenue->penalties }}</div>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="first_payment_tax" placeholder="ضريبة الدفعة الأولى">{{ $revenue->first_payment_tax }}</div>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="net_extract_value" placeholder="صافي قيمة المستخلص">{{ $revenue->net_extract_value }}</div>
                                        </td>
                                        <td class="date-col">
                                            <input type="date" class="date-input" data-field="extract_date" value="{{ $revenue->extract_date ? $revenue->extract_date->format('Y-m-d') : '' }}" placeholder="تاريخ الإعداد">
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="year" placeholder="العام">{{ $revenue->year }}</div>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="payment_type" placeholder="نوع الدفع">{{ $revenue->payment_type }}</div>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="reference_number" placeholder="الرقم المرجعي">{{ $revenue->reference_number }}</div>
                                        </td>
                                        <td class="date-col">
                                            <input type="date" class="date-input" data-field="payment_date" value="{{ $revenue->payment_date ? $revenue->payment_date->format('Y-m-d') : '' }}" placeholder="تاريخ الصرف">
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="payment_value" placeholder="قيمة الصرف">{{ $revenue->payment_value }}</div>
                                        </td>
                                        <td>
                                            <div class="editable-field" contenteditable="true" data-field="extract_status" placeholder="حالة المستخلص">{{ $revenue->extract_status }}</div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow({{ $loop->iteration }})" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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
    });
    
    // تحديث عداد الصفوف
    updateRowCounter();
    
    console.log('Event listeners attached to existing rows:', existingRows.length);
});

let rowCounter = 0;

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
        <td><div class="editable-field" contenteditable="true" data-field="contract_number" placeholder="رقم العقد"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="extract_number" placeholder="رقم المستخلص"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="office" placeholder="المكتب"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="extract_type" placeholder="نوع المستخلص"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="po_number" placeholder="رقم PO"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="invoice_number" placeholder="رقم الفاتورة"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="extract_value" placeholder="قيمة المستخلص"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="tax_percentage" placeholder="نسبة الضريبة"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="tax_value" placeholder="قيمة الضريبة"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="penalties" placeholder="الغرامات"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="first_payment_tax" placeholder="ضريبة الدفعة الأولى"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="net_extract_value" placeholder="صافي قيمة المستخلص"></div></td>
        <td class="date-col"><input type="date" class="date-input" data-field="extract_date" placeholder="تاريخ الإعداد"></td>
        <td><div class="editable-field" contenteditable="true" data-field="year" placeholder="العام"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="payment_type" placeholder="نوع الدفع"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="reference_number" placeholder="الرقم المرجعي"></div></td>
        <td class="date-col"><input type="date" class="date-input" data-field="payment_date" placeholder="تاريخ الصرف"></td>
        <td><div class="editable-field" contenteditable="true" data-field="payment_value" placeholder="قيمة الصرف"></div></td>
        <td><div class="editable-field" contenteditable="true" data-field="extract_status" placeholder="حالة المستخلص"></div></td>
        <td>
            <div class="action-buttons">
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(${rowCounter})" title="حذف">
                    <i class="fas fa-trash"></i>
                </button>
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
        client_name: row.querySelector('[data-field="client_name"]').textContent.trim(),
        project_area: row.querySelector('[data-field="project_area"]').textContent.trim(),
        contract_number: row.querySelector('[data-field="contract_number"]').textContent.trim(),
        extract_number: row.querySelector('[data-field="extract_number"]').textContent.trim(),
        office: row.querySelector('[data-field="office"]').textContent.trim(),
        extract_type: row.querySelector('[data-field="extract_type"]').textContent.trim(),
        po_number: row.querySelector('[data-field="po_number"]').textContent.trim(),
        invoice_number: row.querySelector('[data-field="invoice_number"]').textContent.trim(),
        extract_value: row.querySelector('[data-field="extract_value"]').textContent.trim(),
        tax_percentage: row.querySelector('[data-field="tax_percentage"]').textContent.trim(),
        tax_value: row.querySelector('[data-field="tax_value"]').textContent.trim(),
        penalties: row.querySelector('[data-field="penalties"]').textContent.trim(),
        first_payment_tax: row.querySelector('[data-field="first_payment_tax"]').textContent.trim(),
        net_extract_value: row.querySelector('[data-field="net_extract_value"]').textContent.trim(),
        extract_date: row.querySelector('[data-field="extract_date"]').value || '',
        year: row.querySelector('[data-field="year"]').textContent.trim(),
        payment_type: row.querySelector('[data-field="payment_type"]').textContent.trim(),
        reference_number: row.querySelector('[data-field="reference_number"]').textContent.trim(),
        payment_date: row.querySelector('[data-field="payment_date"]').value || '',
        payment_value: row.querySelector('[data-field="payment_value"]').textContent.trim(),
        extract_status: row.querySelector('[data-field="extract_status"]').textContent.trim()
    };

    // التحقق من وجود بيانات قبل الحفظ
    const hasData = Object.values(data).some(value => value.length > 0);
    if (!hasData) return;

    // إضافة row_id للتحديث
    data.row_id = row.dataset.revenueId || null;

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

// حذف صف
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
    const table = document.querySelector('.table');
    const wb = XLSX.utils.table_to_book(table, {sheet: "الإيرادات"});
    
    // تنسيق اسم الملف بالتاريخ الحالي
    const today = new Date();
    const dateStr = today.getFullYear() + '-' + 
                   String(today.getMonth() + 1).padStart(2, '0') + '-' + 
                   String(today.getDate()).padStart(2, '0');
    
    XLSX.writeFile(wb, `revenues_${dateStr}.xlsx`);
    
    console.log('تم تصدير البيانات إلى Excel');
}

// استيراد البيانات من Excel
function importExcel(input) {
    const file = input.files[0];
    if (!file) return;
    
    // التحقق من نوع الملف
    const fileType = file.name.split('.').pop().toLowerCase();
    if (!['xlsx', 'xls'].includes(fileType)) {
        alert('يرجى اختيار ملف Excel صحيح (.xlsx أو .xls)');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {type: 'array'});
            
            // قراءة الورقة الأولى
            const firstSheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheetName];
            
            // تحويل إلى JSON
            const jsonData = XLSX.utils.sheet_to_json(worksheet, {header: 1});
            
            if (jsonData.length < 2) {
                alert('الملف فارغ أو لا يحتوي على بيانات صحيحة');
                return;
            }
            
            // تأكيد الاستيراد
            if (confirm(`تم العثور على ${jsonData.length - 1} سجل. هل تريد المتابعة مع الاستيراد؟`)) {
                processImportedData(jsonData);
            }
            
        } catch (error) {
            console.error('خطأ في قراءة الملف:', error);
            alert('حدث خطأ في قراءة الملف. يرجى التأكد من أن الملف صحيح.');
        }
    };
    
    reader.readAsArrayBuffer(file);
    
    // إعادة تعيين قيمة input
    input.value = '';
}

// معالجة البيانات المستوردة
function processImportedData(data) {
    const tbody = document.getElementById('revenuesTableBody');
    
    // إزالة رسالة "لا توجد بيانات" إذا كانت موجودة
    const emptyRow = document.getElementById('emptyRow');
    if (emptyRow) {
        emptyRow.remove();
    }
    
    // تخطي الصف الأول (العناوين) والبدء من الصف الثاني
    for (let i = 1; i < data.length; i++) {
        const rowData = data[i];
        
        // تخطي الصفوف الفارغة
        if (!rowData || rowData.every(cell => !cell)) continue;
        
        rowCounter++;
        
        const newRow = document.createElement('tr');
        newRow.className = 'new-row';
        newRow.dataset.rowId = rowCounter;
        newRow.dataset.revenueId = 'null';
        
        newRow.innerHTML = `
            <td class="serial-col">
                <span class="badge bg-primary">${rowCounter}</span>
            </td>
            <td><div class="editable-field" contenteditable="true" data-field="client_name" placeholder="اسم العميل">${rowData[0] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="project_area" placeholder="المشروع">${rowData[1] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="contract_number" placeholder="رقم العقد">${rowData[2] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="extract_number" placeholder="رقم المستخلص">${rowData[3] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="office" placeholder="المكتب">${rowData[4] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="extract_type" placeholder="نوع المستخلص">${rowData[5] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="po_number" placeholder="رقم PO">${rowData[6] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="invoice_number" placeholder="رقم الفاتورة">${rowData[7] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="extract_value" placeholder="قيمة المستخلص">${rowData[8] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="tax_percentage" placeholder="نسبة الضريبة">${rowData[9] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="tax_value" placeholder="قيمة الضريبة">${rowData[10] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="penalties" placeholder="الغرامات">${rowData[11] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="first_payment_tax" placeholder="ضريبة الدفعة الأولى">${rowData[12] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="net_extract_value" placeholder="صافي قيمة المستخلص">${rowData[13] || ''}</div></td>
            <td class="date-col"><input type="date" class="date-input" data-field="extract_date" value="${formatDateForInput(rowData[14])}" placeholder="تاريخ الإعداد"></td>
            <td><div class="editable-field" contenteditable="true" data-field="year" placeholder="العام">${rowData[15] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="payment_type" placeholder="نوع الدفع">${rowData[16] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="reference_number" placeholder="الرقم المرجعي">${rowData[17] || ''}</div></td>
            <td class="date-col"><input type="date" class="date-input" data-field="payment_date" value="${formatDateForInput(rowData[18])}" placeholder="تاريخ الصرف"></td>
            <td><div class="editable-field" contenteditable="true" data-field="payment_value" placeholder="قيمة الصرف">${rowData[19] || ''}</div></td>
            <td><div class="editable-field" contenteditable="true" data-field="extract_status" placeholder="حالة المستخلص">${rowData[20] || ''}</div></td>
            <td>
                <div class="action-buttons">
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(${rowCounter})" title="حذف">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(newRow);
        
        // إضافة event listeners للصف الجديد
        addEditableFieldListeners(newRow);
    }
    
    // تحديث العداد
    updateRowCounter();
    
    alert(`تم استيراد ${data.length - 1} سجل بنجاح!`);
    console.log('تم استيراد البيانات من Excel');
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
</script>

<!-- إضافة مكتبة SheetJS لمعالجة Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

@endsection