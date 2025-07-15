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

                    <!-- حفظ البيانات اليومية -->
                    <div class="card mt-4">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-calendar-plus me-2"></i>
                                حفظ البيانات اليومية
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="daily-save-date" class="form-label">تاريخ العمل</label>
                                        <input type="date" id="daily-save-date" class="form-control" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="daily-notes" class="form-label">ملاحظات</label>
                                        <textarea id="daily-notes" class="form-control" rows="2" placeholder="ملاحظات إضافية..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <button type="button" id="save-daily-installations-btn" class="btn btn-success w-100">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ البيانات اليومية
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" id="load-daily-data-btn" class="btn btn-info w-100">
                                        <i class="fas fa-download me-2"></i>
                                        تحميل بيانات اليوم
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- البيانات المحفوظة -->
                    <div class="card mt-4">
                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                البيانات المحفوظة
                            </h6>
                            <button type="button" id="refresh-saved-data-btn" class="btn btn-sm btn-outline-dark">
                                <i class="fas fa-sync-alt"></i>
                                تحديث
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="saved-data-container">
                                <!-- سيتم تحميل البيانات هنا -->
                            </div>
                        </div>
                    </div>

                    <!-- إجمالي التركيبات -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <button type="button" id="save-installations-btn" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i>
                                حفظ التركيبات (النظام القديم)
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

    <!-- قسم صور التركيبات -->
    <div class="card mt-4">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="fas fa-images me-2"></i>
                صور التركيبات
            </h6>
        </div>
        <div class="card-body">
            <!-- نموذج رفع الصور -->
            <form id="installation-images-form" class="mb-4">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="installation-images" class="form-label">اختر الصور</label>
                            <input type="file" 
                                   class="form-control" 
                                   id="installation-images" 
                                   name="images[]" 
                                   accept="image/*" 
                                   multiple>
                            <div class="form-text">يمكنك اختيار عدة صور في نفس الوقت</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100 mt-4">
                            <i class="fas fa-upload me-2"></i>
                            رفع الصور
                        </button>
                    </div>
                </div>
            </form>

            <!-- عرض الصور -->
            <div id="installation-images-preview" class="row g-3">
                @if(isset($workOrder->installations_images) && is_array($workOrder->installations_images))
                    @foreach($workOrder->installations_images as $index => $image)
                        <div class="col-md-3 col-sm-6 image-container">
                            <div class="card h-100">
                                <img src="{{ asset('storage/' . $image) }}" 
                                     class="card-img-top" 
                                     alt="صورة تركيب {{ $index + 1 }}"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body p-2 text-center">
                                    <button type="button" 
                                            class="btn btn-danger btn-sm delete-image"
                                            data-image-index="{{ $index }}">
                                        <i class="fas fa-trash-alt"></i>
                                        حذف
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
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
            
            if (result.status === 'success') {
                alert('تم حفظ البيانات بنجاح');
                
                // تحديث الملخص اليومي بعد الحفظ
                if (dailySummaryDate) {
                    updateDailySummary(dailySummaryDate.value);
                }
            } else {
                alert('فشل في حفظ البيانات: ' + (result.message || 'خطأ غير معروف'));
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

    // === وظائف البيانات اليومية الجديدة ===
    
    // حفظ البيانات اليومية
    async function saveDailyInstallations() {
        try {
            const saveDailyBtn = document.getElementById('save-daily-installations-btn');
            const workDate = document.getElementById('daily-save-date').value;
            const notes = document.getElementById('daily-notes').value;
            
            if (!workDate) {
                alert('يرجى اختيار تاريخ العمل');
                return;
            }
            
            saveDailyBtn.disabled = true;
            saveDailyBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
            
            // جمع البيانات من الجدول
            const installationData = {};
            let hasData = false;
            
            document.querySelectorAll('tr[data-installation]').forEach(row => {
                const installationType = row.dataset.installation;
                const priceField = row.querySelector('.installation-price');
                const numberField = row.querySelector('.installation-number');
                const totalField = row.querySelector('.installation-total');
                
                if (priceField && numberField && totalField) {
                    const price = parseFloat(priceField.value) || 0;
                    const quantity = parseFloat(numberField.value) || 0;
                    const total = parseFloat(totalField.value) || 0;
                    
                    if (quantity > 0) {
                        installationData[installationType] = {
                            price: price,
                            quantity: quantity,
                            total: total
                        };
                        hasData = true;
                    }
                }
            });
            
            if (!hasData) {
                alert('لا توجد بيانات لحفظها');
                return;
            }
            
            const response = await fetch(`/work-orders/{{ $workOrder->id }}/daily-installations/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    work_date: workDate,
                    installation_data: installationData,
                    notes: notes
                })
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                alert('تم حفظ البيانات اليومية بنجاح');
                
                // تحديث الملخص اليومي
                if (dailySummaryDate) {
                    updateDailySummary(dailySummaryDate.value);
                }
                
                // تحديث البيانات المحفوظة
                loadSavedData();
                
                // مسح الملاحظات
                document.getElementById('daily-notes').value = '';
            } else {
                alert('فشل في حفظ البيانات: ' + (result.message || 'خطأ غير معروف'));
            }
            
        } catch (error) {
            console.error('Daily save error:', error);
            alert('حدث خطأ أثناء حفظ البيانات: ' + error.message);
        } finally {
            const saveDailyBtn = document.getElementById('save-daily-installations-btn');
            saveDailyBtn.disabled = false;
            saveDailyBtn.innerHTML = '<i class="fas fa-save me-2"></i>حفظ البيانات اليومية';
        }
    }
    
    // تحميل بيانات يوم معين
    async function loadDailyData() {
        try {
            const workDate = document.getElementById('daily-save-date').value;
            
            if (!workDate) {
                alert('يرجى اختيار تاريخ العمل');
                return;
            }
            
            const response = await fetch(`/work-orders/{{ $workOrder->id }}/daily-installations/get?date=${workDate}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (result.status === 'success' && result.data) {
                const data = result.data;
                
                // مسح البيانات الحالية
                document.querySelectorAll('.installation-price, .installation-number, .installation-total').forEach(input => {
                    input.value = '';
                });
                
                // تحميل البيانات
                data.installation_data.forEach(item => {
                    const row = document.querySelector(`tr[data-installation="${item.installation_type}"]`);
                    if (row) {
                        const priceField = row.querySelector('.installation-price');
                        const numberField = row.querySelector('.installation-number');
                        const totalField = row.querySelector('.installation-total');
                        
                        if (priceField && numberField && totalField) {
                            priceField.value = item.price;
                            numberField.value = item.quantity;
                            totalField.value = item.total;
                        }
                    }
                });
                
                // تحديث الملاحظات
                document.getElementById('daily-notes').value = data.notes || '';
                
                // تحديث الإجماليات
                calculateTotals();
                refreshSummaryTable();
                
                alert('تم تحميل البيانات بنجاح');
            } else {
                alert('لا توجد بيانات محفوظة لهذا التاريخ');
            }
            
        } catch (error) {
            console.error('Load daily data error:', error);
            alert('حدث خطأ أثناء تحميل البيانات: ' + error.message);
        }
    }
    
    // تحميل البيانات المحفوظة
    async function loadSavedData() {
        try {
            const response = await fetch(`/work-orders/{{ $workOrder->id }}/daily-installations/all`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                const container = document.getElementById('saved-data-container');
                
                if (result.data.length === 0) {
                    container.innerHTML = `
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-info-circle mb-2"></i>
                            <p class="mb-0">لا توجد بيانات محفوظة</p>
                        </div>
                    `;
                    return;
                }
                
                let html = '<div class="table-responsive">';
                html += '<table class="table table-striped table-hover">';
                html += '<thead class="table-light">';
                html += '<tr>';
                html += '<th>التاريخ</th>';
                html += '<th class="text-center">عدد العناصر</th>';
                html += '<th class="text-center">الإجمالي</th>';
                html += '<th class="text-center">الملاحظات</th>';
                html += '<th class="text-center">الإجراءات</th>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                
                result.data.forEach(item => {
                    html += '<tr>';
                    html += `<td>${item.formatted_date}</td>`;
                    html += `<td class="text-center">${item.total_items}</td>`;
                    html += `<td class="text-center">${parseFloat(item.total_amount).toFixed(2)} ريال</td>`;
                    html += `<td class="text-center">${item.notes || '-'}</td>`;
                    html += '<td class="text-center">';
                    html += `<button type="button" class="btn btn-sm btn-info me-1" onclick="viewDailyData('${item.work_date}')">`;
                    html += '<i class="fas fa-eye"></i> عرض';
                    html += '</button>';
                    html += `<button type="button" class="btn btn-sm btn-success me-1" onclick="loadDailyDataByDate('${item.work_date}')">`;
                    html += '<i class="fas fa-download"></i> تحميل';
                    html += '</button>';
                    html += `<button type="button" class="btn btn-sm btn-danger" onclick="deleteDailyData(${item.id})">`;
                    html += '<i class="fas fa-trash"></i> حذف';
                    html += '</button>';
                    html += '</td>';
                    html += '</tr>';
                });
                
                html += '</tbody>';
                html += '</table>';
                html += '</div>';
                
                container.innerHTML = html;
            }
            
        } catch (error) {
            console.error('Load saved data error:', error);
            document.getElementById('saved-data-container').innerHTML = `
                <div class="text-center text-danger py-4">
                    <i class="fas fa-exclamation-circle mb-2"></i>
                    <p class="mb-0">حدث خطأ أثناء تحميل البيانات</p>
                </div>
            `;
        }
    }
    
    // تحميل بيانات يوم معين من القائمة المحفوظة
    async function loadDailyDataByDate(date) {
        document.getElementById('daily-save-date').value = date;
        await loadDailyData();
    }
    
    // حذف بيانات يومية
    async function deleteDailyData(id) {
        if (!confirm('هل أنت متأكد من حذف هذه البيانات؟')) {
            return;
        }
        
        try {
            const response = await fetch(`/daily-installations/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                alert('تم حذف البيانات بنجاح');
                loadSavedData();
            } else {
                alert('فشل في حذف البيانات: ' + (result.message || 'خطأ غير معروف'));
            }
            
        } catch (error) {
            console.error('Delete error:', error);
            alert('حدث خطأ أثناء حذف البيانات: ' + error.message);
        }
    }
    
    // عرض تفاصيل بيانات يوم معين
    async function viewDailyData(date) {
        try {
            const response = await fetch(`/work-orders/{{ $workOrder->id }}/daily-installations/get?date=${date}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const result = await response.json();
            
            if (result.status === 'success' && result.data) {
                const data = result.data;
                
                // إنشاء نافذة منبثقة بدلاً من alert
                let modalHtml = `
                    <div class="modal fade" id="viewDailyDataModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">تفاصيل يوم ${new Date(date).toLocaleDateString('ar-SA')}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>نوع التركيب</th>
                                                    <th class="text-center">الكمية</th>
                                                    <th class="text-center">السعر</th>
                                                    <th class="text-center">الإجمالي</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                `;
                
                data.installation_data.forEach(item => {
                    modalHtml += `
                        <tr>
                            <td>${getInstallationLabel(item.installation_type)}</td>
                            <td class="text-center">${item.quantity}</td>
                            <td class="text-center">${parseFloat(item.price).toFixed(2)} ريال</td>
                            <td class="text-center">${parseFloat(item.total).toFixed(2)} ريال</td>
                        </tr>
                    `;
                });
                
                modalHtml += `
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="3" class="text-end"><strong>الإجمالي:</strong></td>
                                                    <td class="text-center"><strong>${parseFloat(data.total_amount).toFixed(2)} ريال</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <strong>عدد العناصر الكلي:</strong> ${data.total_items}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>تاريخ الإنشاء:</strong> ${new Date(data.created_at).toLocaleDateString('ar-SA')} ${new Date(data.created_at).toLocaleTimeString('ar-SA')}
                                        </div>
                                    </div>
                                    
                                    ${data.notes ? `
                                        <div class="mt-3">
                                            <strong>الملاحظات:</strong>
                                            <div class="alert alert-info mt-2">
                                                ${data.notes}
                                            </div>
                                        </div>
                                    ` : ''}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                    <button type="button" class="btn btn-success" onclick="loadDailyDataByDate('${date}'); $('#viewDailyDataModal').modal('hide');">
                                        <i class="fas fa-download me-1"></i> تحميل البيانات
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // إزالة النافذة المنبثقة السابقة إن وجدت
                const existingModal = document.getElementById('viewDailyDataModal');
                if (existingModal) {
                    existingModal.remove();
                }
                
                // إضافة النافذة المنبثقة للصفحة
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                
                // عرض النافذة المنبثقة
                const modal = new bootstrap.Modal(document.getElementById('viewDailyDataModal'));
                modal.show();
                
                // إزالة النافذة المنبثقة عند الإغلاق
                document.getElementById('viewDailyDataModal').addEventListener('hidden.bs.modal', function () {
                    this.remove();
                });
                
            } else {
                alert('لا توجد بيانات لهذا التاريخ');
            }
            
        } catch (error) {
            console.error('View daily data error:', error);
            alert('حدث خطأ أثناء عرض البيانات: ' + error.message);
        }
    }
    
    // دالة مساعدة لتحويل نوع التركيب إلى نص عربي
    function getInstallationLabel(installationType) {
        const labels = {
            'installation_1600': 'تركيب لوحة منخفض 1600',
            'installation_3000': 'تركيب لوحة منخفض 3000',
            'ground_installation': 'تأريض عداد',
            'mini_pillar_installation': 'تأريض ميني بلر',
            'multiple_installation': 'تأريض متعدد',
            'aluminum_4x70_1kv': 'أطراف ألومنيوم 4x70 1 kv',
            'copper_1x50_13kv': 'نهاية نحاس 1x50 13.8 kv',
            'aluminum_3x70_13kv': 'نهاية ألومنيوم 3x70 13.8 kv',
            'aluminum_500_3_13kv': 'نهاية 3 × 500 ألومنيوم 13.8kv',
            'aluminum_500_3_connection_13kv': 'وصلة 3 × 500 ألومنيوم 13.8kv',
            'aluminum_70_4_1kv': 'نهاية 4 × 70 ألومنيوم 1kv',
            'aluminum_185_4_1kv': 'نهاية 4 × 185 ألومنيوم 1kv',
            'aluminum_300_4_1kv': 'نهاية 4 × 300 ألومنيوم 1kv',
            'connection_33kv': 'وصلة 33kv',
            'end_33kv': 'نهاية 33kv'
        };
        return labels[installationType] || installationType;
    }
    
    // إضافة مستمعي الأحداث للأزرار الجديدة
    document.getElementById('save-daily-installations-btn')?.addEventListener('click', saveDailyInstallations);
    document.getElementById('load-daily-data-btn')?.addEventListener('click', loadDailyData);
    document.getElementById('refresh-saved-data-btn')?.addEventListener('click', loadSavedData);

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
    
    // تحميل البيانات المحفوظة
    loadSavedData();
    
    console.log('Initialization complete');
});

// إضافة كود التعامل مع الصور
document.addEventListener('DOMContentLoaded', function() {
    const imageForm = document.getElementById('installation-images-form');
    const imageInput = document.getElementById('installation-images');
    const previewContainer = document.getElementById('installation-images-preview');
    const workOrderId = {{ $workOrder->id }}; // Get the work order ID

    // معالجة رفع الصور
    if (imageForm) {
        imageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            const files = imageInput.files;
            
            if (files.length === 0) {
                alert('الرجاء اختيار الصور أولاً');
                return;
            }
            
            for (let i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }
            
            try {
                const response = await fetch(`/admin/work-orders/${workOrderId}/upload-installation-images`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) throw new Error('فشل رفع الصور');

                const result = await response.json();
                
                if (result.success) {
                    // تحديث عرض الصور
                    loadInstallationImages();
                    // تفريغ حقل اختيار الملفات
                    imageInput.value = '';
                    // عرض رسالة نجاح
                    alert('تم رفع الصور بنجاح');
                } else {
                    alert('فشل في رفع الصور: ' + result.message);
                }
            } catch (error) {
                console.error('Error uploading images:', error);
                alert('حدث خطأ أثناء رفع الصور');
            }
        });
    }

    // حذف الصور
    document.addEventListener('click', async function(e) {
        if (e.target.closest('.delete-image')) {
            const button = e.target.closest('.delete-image');
            const imageIndex = button.dataset.imageIndex;
            
            if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                try {
                    const response = await fetch(`/admin/work-orders/${workOrderId}/delete-installation-image/${imageIndex}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error('فشل حذف الصورة');

                    const result = await response.json();
                    
                    if (result.success) {
                        // إزالة الصورة من العرض
                        button.closest('.image-container').remove();
                        alert('تم حذف الصورة بنجاح');
                        // إعادة تحميل الصور لإعادة ترقيم الفهارس
                        loadInstallationImages();
                    } else {
                        alert('فشل في حذف الصورة: ' + result.message);
                    }
                } catch (error) {
                    console.error('Error deleting image:', error);
                    alert('حدث خطأ أثناء حذف الصورة');
                }
            }
        }
    });

    // تحميل الصور
    async function loadInstallationImages() {
        try {
            const response = await fetch(`/admin/work-orders/${workOrderId}/installation-images`);
            if (!response.ok) throw new Error('فشل تحميل الصور');

            const result = await response.json();
            
            if (result.success && result.images) {
                previewContainer.innerHTML = result.images.map((image, index) => `
                    <div class="col-md-3 col-sm-6 image-container">
                        <div class="card h-100">
                            <img src="/storage/${image}" 
                                 class="card-img-top" 
                                 alt="صورة تركيب ${index + 1}"
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body p-2 text-center">
                                <button type="button" 
                                        class="btn btn-danger btn-sm delete-image"
                                        data-image-index="${index}">
                                    <i class="fas fa-trash-alt"></i>
                                    حذف
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        } catch (error) {
            console.error('Error loading images:', error);
        }
    }

    // تحميل الصور عند تحميل الصفحة
    loadInstallationImages();
});
</script>
@endpush 