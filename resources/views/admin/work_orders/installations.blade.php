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
    @if(isset($currentInstallations) && $currentInstallations->count() > 0)
        @foreach($currentInstallations as $installation)
            const row = document.querySelector(`tr[data-installation="{{ $installation->installation_type }}"]`);
            if (row) {
                const priceInput = row.querySelector('.installation-price');
                const numberInput = row.querySelector('.installation-number');
                if (priceInput && numberInput) {
                    priceInput.value = {{ $installation->price }};
                    numberInput.value = {{ $installation->quantity }};
                    updateRowTotal(priceInput);
                }
            }
        @endforeach
    @endif

    // تحديث الملخص اليومي عند تغيير التاريخ
    dailySummaryDate.addEventListener('change', function() {
        updateDailySummary(this.value);
    });

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
            tbody.innerHTML = '';
            
            let totalCount = 0;
            let totalAmount = 0;
            
            if (data.installations && data.installations.length > 0) {
                data.installations.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${item.installation_type}</td>
                        <td class="text-center">${item.quantity}</td>
                        <td class="text-center">${parseFloat(item.total).toFixed(2)} ريال</td>
                        <td class="text-center">${new Date(item.created_at).toLocaleTimeString('ar')}</td>
                    `;
                    tbody.appendChild(tr);
                    
                    totalCount += parseFloat(item.quantity);
                    totalAmount += parseFloat(item.total);
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            لا توجد تركيبات لهذا اليوم
                        </td>
                    </tr>
                `;
            }
            
            // تحديث الإجماليات اليومية
            document.getElementById('daily-total-count').textContent = totalCount.toFixed(0);
            document.getElementById('daily-total-amount').textContent = totalAmount.toFixed(2) + ' ريال';
            
        } catch (error) {
            console.error('Error fetching daily summary:', error);
            toastr.error('حدث خطأ أثناء تحديث الملخص اليومي');
        }
    }

    // تحديث إجمالي الصف والإجمالي الكلي
    function updateRowTotal(input) {
        console.log('updateRowTotal called');
        const row = input.closest('tr');
        const priceInput = row.querySelector('.installation-price');
        const numberInput = row.querySelector('.installation-number');
        const totalInput = row.querySelector('.installation-total');
        
        const price = parseFloat(priceInput.value) || 0;
        const number = parseFloat(numberInput.value) || 0;
        const total = price * number;
        
        console.log('Price:', price, 'Number:', number, 'Total:', total);
        
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
        
        console.log('Grand Total:', grandTotal);
        
        document.getElementById('total-installations-amount').textContent = grandTotal.toFixed(2) + ' ريال';
        updateSummaryTable();
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
                saveButton.disabled = false;
                saveIndicator.style.display = 'none';
                return;
            }
            
            const response = await fetch(`/admin/work-orders/{{ $workOrder->id }}/installations/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) throw new Error('فشل في حفظ البيانات');
            
            const result = await response.json();
            
            toastr.success('تم حفظ البيانات بنجاح');
            
            // تحديث الملخص اليومي بعد الحفظ
            updateDailySummary(dailySummaryDate.value);
            
        } catch (error) {
            console.error('Save error:', error);
            toastr.error('حدث خطأ أثناء حفظ البيانات');
        } finally {
            saveButton.disabled = false;
            saveIndicator.style.display = 'none';
        }
    }

    // إضافة مستمعي الأحداث
    document.querySelectorAll('.installation-price, .installation-number').forEach(input => {
        input.addEventListener('input', () => updateRowTotal(input));
    });

    saveButton.addEventListener('click', saveInstallations);

    // تحديث الملخص اليومي عند تحميل الصفحة
    updateDailySummary(dailySummaryDate.value);
});
</script>
@endpush 