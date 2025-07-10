@php($hideNavbar = true)
@extends('layouts.admin')

@section('content')

<div class="text-start mb-4">
                <a href="{{ route('admin.work-orders.execution', $workOrder) }}" 
                   class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>عودة للتنفيذ
                </a>
            </div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">بيانات التركيبات</h5>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            
                            <thead class="bg-light">
                                <tr>
                                    <th>نوع التركيب</th>
                                    <th width="200">السعر (ريال)</th>
                                    <th width="200">العدد</th>
                                    <th width="200">الإجمالي (ريال)</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                @foreach($installations as $key => $label)
                                <tr data-installation="{{ $key }}">
                                    <td>{{ $label }}</td>
                                    <td>
                                        <input type="number" 
                                            class="form-control installation-price" 
                                            step="0.01" 
                                            min="0" 
                                            placeholder="أدخل السعر"
                                            data-installation="{{ $key }}">
                                    </td>
                                    <td>
                                        <input type="number" 
                                            class="form-control installation-number" 
                                            min="0" 
                                            placeholder="أدخل العدد">
                                    </td>
                                    <td>
                                        <input type="text" 
                                            class="form-control installation-total" 
                                            readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- ملخص التركيبات -->
                    <div class="card mt-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">ملخص التركيبات</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="summary-table" class="table">
                                    <thead>
                                        <tr>
                                            <th>نوع التركيب</th>
                                            <th class="text-center">السعر</th>
                                            <th class="text-center">العدد</th>
                                            <th class="text-center">الإجمالي</th>
                                        </tr>
                                    </thead>
                                    <tbody id="summary-tbody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- إجمالي التركيبات -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <button type="button" id="save-installations-btn" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>
                                حفظ التركيبات
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>الإجمالي الكلي:</strong>
                                        <span id="total-installations-amount" class="h5 mb-0">0.00 ريال</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- مؤشر الحفظ -->
                    <div id="save-indicator" class="mt-3 text-center" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">جاري الحفظ...</span>
                        </div>
                        <div class="text-muted mt-2">جاري حفظ البيانات...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
console.log('JavaScript loaded successfully!'); // للتأكد من تحميل الملف

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    
    const saveButton = document.getElementById('save-installations-btn');
    const saveIndicator = document.getElementById('save-indicator');

    // تحديث إجمالي الصف والإجمالي الكلي
    function updateRowTotal(input) {
        console.log('updateRowTotal called'); // للتأكد من استدعاء الدالة
        const row = input.closest('tr');
        const priceInput = row.querySelector('.installation-price');
        const numberInput = row.querySelector('.installation-number');
        const totalInput = row.querySelector('.installation-total');
        
        const price = parseFloat(priceInput.value) || 0;
        const number = parseFloat(numberInput.value) || 0;
        const total = price * number;
        
        console.log('Price:', price, 'Number:', number, 'Total:', total); // للتتبع
        
        totalInput.value = total.toFixed(2);
        updateTotals();
    }

    // تحديث الإجمالي الكلي
    function updateTotals() {
        let grandTotal = 0;
        
        document.querySelectorAll('tr[data-installation]').forEach(row => {
            const totalInput = row.querySelector('.installation-total');
            if (totalInput && totalInput.value) {
                grandTotal += parseFloat(totalInput.value) || 0;
            }
        });
        
        console.log('Grand Total:', grandTotal); // للتتبع
        
        document.getElementById('total-installations-amount').textContent = grandTotal.toFixed(2) + ' ريال';
        updateSummaryTable(); // تحديث جدول الملخص تلقائياً
    }

    // تحديث جدول الملخص
    function updateSummaryTable() {
        const summaryTbody = document.getElementById('summary-tbody');
        summaryTbody.innerHTML = '';
        let hasData = false;
        
        document.querySelectorAll('tr[data-installation]').forEach(row => {
            const label = row.querySelector('td:first-child')?.textContent;
            const priceInput = row.querySelector('.installation-price');
            const numberInput = row.querySelector('.installation-number');
            const totalInput = row.querySelector('.installation-total');
            
            if (label && priceInput && numberInput && totalInput) {
                const price = parseFloat(priceInput.value) || 0;
                const number = parseFloat(numberInput.value) || 0;
                const total = parseFloat(totalInput.value) || 0;
                
                if (price > 0 || number > 0) {
                    hasData = true;
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${label}</td>
                        <td class="text-center">${price.toFixed(2)} ريال</td>
                        <td class="text-center">${number}</td>
                        <td class="text-center">${total.toFixed(2)} ريال</td>
                    `;
                    summaryTbody.appendChild(tr);
                }
            }
        });
        
        if (!hasData) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td colspan="4" class="text-center text-muted">
                    <i class="fas fa-info-circle me-2"></i>
                    لا توجد بيانات مدخلة بعد
                </td>
            `;
            summaryTbody.appendChild(tr);
        }
    }

    // حفظ البيانات
    async function saveInstallations() {
        try {
            saveButton.disabled = true;
            saveIndicator.style.display = 'block';
            
            const data = {};
            let hasData = false;
            
            document.querySelectorAll('tr[data-installation]').forEach(row => {
                const installationType = row.dataset.installation;
                const priceInput = row.querySelector('.installation-price');
                const numberInput = row.querySelector('.installation-number');
                const totalInput = row.querySelector('.installation-total');
                
                if (priceInput && numberInput && totalInput) {
                    const price = parseFloat(priceInput.value) || 0;
                    const number = parseFloat(numberInput.value) || 0;
                    const total = parseFloat(totalInput.value) || 0;
                    
                    if (price > 0 && number > 0) {
                        hasData = true;
                        data[installationType] = {
                            type: installationType,
                            price: price,
                            number: number,
                            total: total
                        };
                    }
                }
            });

            if (!hasData) {
                toastr.warning('الرجاء إدخال بيانات التركيبات أولاً');
                return;
            }
            
            const response = await fetch(`/admin/work-orders/{{ $workOrder->id }}/installations/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ installations: data })
            });
            
            if (!response.ok) {
                throw new Error('فشل حفظ البيانات');
            }
            
            const result = await response.json();
            
            if (result.status === 'success') {
                toastr.success('تم حفظ التركيبات بنجاح');
                updateSummaryTable(); // تحديث جدول الملخص بعد الحفظ
            } else {
                throw new Error(result.message || 'فشل حفظ البيانات');
            }
            
        } catch (error) {
            console.error('Error saving installations:', error);
            toastr.error(error.message || 'حدث خطأ أثناء حفظ البيانات');
        } finally {
            saveButton.disabled = false;
            saveIndicator.style.display = 'none';
        }
    }

    // تحميل البيانات المحفوظة
    async function loadSavedInstallations() {
        try {
            const response = await fetch(`/admin/work-orders/{{ $workOrder->id }}/installations/data`);
            if (!response.ok) {
                throw new Error('فشل تحميل البيانات');
            }
            
            const result = await response.json();
            if (result.status === 'success' && result.data) {
                Object.entries(result.data).forEach(([type, installation]) => {
                    const row = document.querySelector(`[data-installation="${type}"]`);
                    if (row) {
                        const priceInput = row.querySelector('.installation-price');
                        const numberInput = row.querySelector('.installation-number');
                        
                        if (priceInput && numberInput) {
                            priceInput.value = installation.price || '';
                            numberInput.value = installation.number || '';
                            updateRowTotal(priceInput); // تحديث الإجمالي تلقائياً
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error loading installations:', error);
            toastr.error('حدث خطأ أثناء تحميل البيانات');
        }
    }

    // إضافة مستمعي الأحداث لحقول الإدخال بطرق متعددة
    function attachEventListeners() {
        console.log('Attaching event listeners...');
        
        // طريقة 1: مستمعي أحداث input
        document.querySelectorAll('.installation-price, .installation-number').forEach(input => {
            input.addEventListener('input', function() {
                console.log('Input event triggered on:', this.className);
                updateRowTotal(this);
            });
            
            input.addEventListener('change', function() {
                console.log('Change event triggered on:', this.className);
                updateRowTotal(this);
            });
            
            input.addEventListener('keyup', function() {
                console.log('Keyup event triggered on:', this.className);
                updateRowTotal(this);
            });
        });
    }

    // دالة لإعادة حساب جميع الإجماليات يدوياً
    function recalculateAll() {
        console.log('Recalculating all totals...');
        document.querySelectorAll('tr[data-installation]').forEach(row => {
            const priceInput = row.querySelector('.installation-price');
            const numberInput = row.querySelector('.installation-number');
            const totalInput = row.querySelector('.installation-total');
            
            if (priceInput && numberInput && totalInput) {
                const price = parseFloat(priceInput.value) || 0;
                const number = parseFloat(numberInput.value) || 0;
                const total = price * number;
                
                totalInput.value = total.toFixed(2);
            }
        });
        updateTotals();
    }

    // إضافة مستمع الحدث لزر الحفظ
    saveButton.addEventListener('click', saveInstallations);

    // تحميل البيانات عند تحميل الصفحة
    loadSavedInstallations();
    
    // إضافة مستمعي الأحداث
    attachEventListeners();
    
    // إعادة حساب كل شيء بعد ثانية واحدة
    setTimeout(recalculateAll, 1000);
    
    // إضافة زر إعادة حساب للاختبار (يمكن إزالته لاحقاً)
    window.recalculateAll = recalculateAll;
});
</script>
@endpush 