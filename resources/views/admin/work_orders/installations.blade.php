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

                    <!-- ملخص التركيبات اليومي -->
                    <div class="card mt-4">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-calendar-day me-2"></i>
                                ملخص التركيبات اليومي
                            </h6>
                            <div>
                                <input type="date" id="daily-summary-date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="daily-summary-table" class="table table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>نوع التركيب</th>
                                            <th class="text-center">العدد</th>
                                            <th class="text-center">الإجمالي</th>
                                            <th class="text-center">وقت التركيب</th>
                                        </tr>
                                    </thead>
                                    <tbody id="daily-summary-tbody">
                                        <!-- سيتم ملء البيانات عن طريق JavaScript -->
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="1"><strong>إجمالي اليوم</strong></td>
                                            <td class="text-center" id="daily-total-count">0</td>
                                            <td class="text-center" id="daily-total-amount">0.00 ريال</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
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
console.log('JavaScript loaded successfully!');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    
    const saveButton = document.getElementById('save-installations-btn');
    const saveIndicator = document.getElementById('save-indicator');
    const dailySummaryDate = document.getElementById('daily-summary-date');

    // تحميل البيانات المحفوظة مسبقاً
    function loadSavedData() {
        @if(isset($currentInstallations) && $currentInstallations->count() > 0)
            @foreach($currentInstallations as $index => $installation)
                try {
                    const savedRow = document.querySelector(`tr[data-installation="{{ $installation->installation_type }}"]`);
                    if (savedRow) {
                        const savedPriceInput = savedRow.querySelector('.installation-price');
                        const savedNumberInput = savedRow.querySelector('.installation-number');
                        if (savedPriceInput && savedNumberInput) {
                            savedPriceInput.value = {{ $installation->price }};
                            savedNumberInput.value = {{ $installation->quantity }};
                            calculateRowTotal(savedPriceInput);
                        }
                    }
                } catch (error) {
                    console.error('Error loading saved data for installation {{ $index }}:', error);
                }
            @endforeach
        @endif
    }

    // تحديث الملخص اليومي عند تغيير التاريخ
    if (dailySummaryDate) {
        dailySummaryDate.addEventListener('change', function() {
            updateDailySummary(this.value);
        });
    }

    // تحديث الملخص اليومي
    async function updateDailySummary(date) {
        try {
            const response = await fetch(`/admin/work-orders/{{ $workOrder->id }}/installations/daily-summary?date=${date}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (!response.ok) throw new Error('فشل في جلب البيانات');
            
            const data = await response.json();
            const tbody = document.getElementById('daily-summary-tbody');
            if (!tbody) return;
            
            tbody.innerHTML = '';
            
            let totalCount = 0;
            let totalAmount = 0;
            
            if (data.installations && data.installations.length > 0) {
                data.installations.forEach(item => {
                    const dailyTableRow = document.createElement('tr');
                    dailyTableRow.innerHTML = `
                        <td>${item.installation_type}</td>
                        <td class="text-center">${item.quantity}</td>
                        <td class="text-center">${parseFloat(item.total).toFixed(2)} ريال</td>
                        <td class="text-center">${new Date(item.created_at).toLocaleTimeString('ar')}</td>
                    `;
                    tbody.appendChild(dailyTableRow);
                    
                    totalCount += parseFloat(item.quantity);
                    totalAmount += parseFloat(item.total);
                });
            } else {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            لا توجد تركيبات لهذا اليوم
                        </td>
                    </tr>
                `;
                tbody.appendChild(emptyRow);
            }
            
            // تحديث الإجماليات اليومية
            const dailyTotalCount = document.getElementById('daily-total-count');
            const dailyTotalAmount = document.getElementById('daily-total-amount');
            
            if (dailyTotalCount) dailyTotalCount.textContent = totalCount.toFixed(0);
            if (dailyTotalAmount) dailyTotalAmount.textContent = totalAmount.toFixed(2) + ' ريال';
            
        } catch (error) {
            console.error('Error fetching daily summary:', error);
            console.log('حدث خطأ أثناء تحديث الملخص اليومي');
        }
    }

    // تحديث إجمالي الصف والإجمالي الكلي
    function calculateRowTotal(inputElement) {
        console.log('calculateRowTotal called for input:', inputElement);
        
        if (!inputElement) {
            console.error('Input element is null');
            return;
        }
        
        const currentRow = inputElement.closest('tr');
        if (!currentRow) {
            console.error('Row not found for input:', inputElement);
            return;
        }
        
        const priceField = currentRow.querySelector('.installation-price');
        const numberField = currentRow.querySelector('.installation-number');
        const totalField = currentRow.querySelector('.installation-total');
        
        if (!priceField || !numberField || !totalField) {
            console.error('Required inputs not found in row');
            console.log('priceField:', priceField);
            console.log('numberField:', numberField);
            console.log('totalField:', totalField);
            return;
        }
        
        const priceValue = parseFloat(priceField.value) || 0;
        const numberValue = parseFloat(numberField.value) || 0;
        const totalValue = priceValue * numberValue;
        
        console.log('Calculation - Price:', priceValue, 'Number:', numberValue, 'Total:', totalValue);
        
        totalField.value = totalValue.toFixed(2);
        
        // تحديث الإجماليات
        calculateTotals();
        refreshSummaryTable();
    }

    // تحديث الإجمالي الكلي
    function calculateTotals() {
        let grandTotal = 0;
        
        document.querySelectorAll('tr[data-installation]').forEach(dataRow => {
            const totalField = dataRow.querySelector('.installation-total');
            if (totalField && totalField.value) {
                const value = parseFloat(totalField.value) || 0;
                grandTotal += value;
                console.log('Adding to total:', value, 'Current total:', grandTotal);
            }
        });
        
        console.log('Final Grand Total:', grandTotal);
        
        const totalElement = document.getElementById('total-installations-amount');
        if (totalElement) {
            totalElement.textContent = grandTotal.toFixed(2) + ' ريال';
        }
    }

    // تحديث جدول الملخص
    function refreshSummaryTable() {
        const summaryTbody = document.getElementById('summary-tbody');
        if (!summaryTbody) {
            console.error('Summary tbody not found');
            return;
        }
        
        summaryTbody.innerHTML = '';
        let hasData = false;
        
        document.querySelectorAll('tr[data-installation]').forEach(dataRow => {
            const label = dataRow.querySelector('td:first-child')?.textContent;
            const priceField = dataRow.querySelector('.installation-price');
            const numberField = dataRow.querySelector('.installation-number');
            const totalField = dataRow.querySelector('.installation-total');
            
            if (label && priceField && numberField && totalField) {
                const priceValue = parseFloat(priceField.value) || 0;
                const numberValue = parseFloat(numberField.value) || 0;
                const totalValue = parseFloat(totalField.value) || 0;
                
                if (priceValue > 0 || numberValue > 0) {
                    hasData = true;
                    const summaryTableRow = document.createElement('tr');
                    summaryTableRow.innerHTML = `
                        <td>${label}</td>
                        <td class="text-center">${priceValue.toFixed(2)} ريال</td>
                        <td class="text-center">${numberValue}</td>
                        <td class="text-center">${totalValue.toFixed(2)} ريال</td>
                    `;
                    summaryTbody.appendChild(summaryTableRow);
                }
            }
        });
        
        if (!hasData) {
            const emptyDataRow = document.createElement('tr');
            emptyDataRow.innerHTML = `
                <td colspan="4" class="text-center text-muted">
                    <i class="fas fa-info-circle me-2"></i>
                    لا توجد بيانات مدخلة بعد
                </td>
            `;
            summaryTbody.appendChild(emptyDataRow);
        }
    }

    // حفظ البيانات
    async function saveInstallations() {
        try {
            console.log('Starting save process...');
            saveButton.disabled = true;
            saveIndicator.style.display = 'block';
            
            const installationsData = {};
            let hasValidData = false;
            
            document.querySelectorAll('tr[data-installation]').forEach(dataRow => {
                const installationType = dataRow.dataset.installation;
                const priceField = dataRow.querySelector('.installation-price');
                const numberField = dataRow.querySelector('.installation-number');
                const totalField = dataRow.querySelector('.installation-total');
                
                if (priceField && numberField && totalField) {
                    const priceValue = parseFloat(priceField.value) || 0;
                    const numberValue = parseFloat(numberField.value) || 0;
                    const totalValue = parseFloat(totalField.value) || 0;
                    
                    console.log(`Processing ${installationType}: price=${priceValue}, number=${numberValue}, total=${totalValue}`);
                    
                    if (priceValue > 0 && numberValue > 0) {
                        hasValidData = true;
                        installationsData[installationType] = {
                            type: installationType,
                            price: priceValue,
                            quantity: numberValue,
                            total: totalValue
                        };
                    }
                }
            });

            console.log('Data to save:', installationsData);

            if (!hasValidData) {
                alert('الرجاء إدخال بيانات التركيبات أولاً');
                return;
            }
            
            const response = await fetch(`/admin/work-orders/{{ $workOrder->id }}/installations/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(installationsData)
            });

            console.log('Response status:', response.status);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Save failed:', errorText);
                throw new Error('فشل في حفظ البيانات');
            }
            
            const result = await response.json();
            console.log('Save result:', result);
            
            alert('تم حفظ البيانات بنجاح');
            
            // تحديث الملخص اليومي بعد الحفظ
            if (dailySummaryDate) {
                updateDailySummary(dailySummaryDate.value);
            }
            
        } catch (error) {
            console.error('Save error:', error);
            alert('حدث خطأ أثناء حفظ البيانات: ' + error.message);
        } finally {
            saveButton.disabled = false;
            saveIndicator.style.display = 'none';
        }
    }

    // إضافة مستمعي الأحداث للحسابات
    function setupEventListeners() {
        console.log('Setting up event listeners...');
        
        // إزالة المستمعات القديمة أولاً
        document.querySelectorAll('.installation-price, .installation-number').forEach(inputField => {
            inputField.removeEventListener('input', handleInputChange);
            inputField.removeEventListener('change', handleInputChange);
            inputField.removeEventListener('keyup', handleInputChange);
        });
        
        // إضافة المستمعات الجديدة
        document.querySelectorAll('.installation-price, .installation-number').forEach(inputField => {
            console.log('Adding listener to:', inputField.className, inputField);
            inputField.addEventListener('input', handleInputChange);
            inputField.addEventListener('change', handleInputChange);
            inputField.addEventListener('keyup', handleInputChange);
        });
    }
    
    // معالج تغيير الإدخال
    function handleInputChange(event) {
        console.log('Input changed:', event.target.value, 'Element:', event.target);
        calculateRowTotal(event.target);
    }

    // إضافة مستمع للحفظ
    if (saveButton) {
        saveButton.addEventListener('click', saveInstallations);
        console.log('Save button listener attached');
    }

    // تهيئة الصفحة
    console.log('Initializing page...');
    loadSavedData();
    setupEventListeners();
    calculateTotals();
    refreshSummaryTable();
    
    // تحديث الملخص اليومي عند تحميل الصفحة
    if (dailySummaryDate) {
        updateDailySummary(dailySummaryDate.value);
    }
    
    console.log('Initialization complete');
});
</script>
@endpush 