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
                    <!-- قسم اختيار التاريخ -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary" id="prev-date">
                                            <i class="fas fa-chevron-right"></i>
                                            اليوم السابق
                                        </button>
                                        <div class="mx-2 flex-grow-1" style="max-width: 200px;">
                                            <input type="date" 
                                                id="installation_date" 
                                                class="form-control text-center" 
                                                value="{{ date('Y-m-d') }}"
                                                required>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary" id="next-date">
                                            اليوم التالي
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary ms-2" id="today-date">
                                            <i class="fas fa-calendar-day"></i>
                                            اليوم
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                            placeholder="0.00 ريال"
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
                @csrf
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
                        <div class="col-md-3 col-sm-6 image-container" id="image-container-{{ $index }}">
                            <div class="card h-100">
                                <img src="{{ asset('storage/' . $image) }}" 
                                     class="card-img-top" 
                                     alt="صورة تركيب {{ $index + 1 }}"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body p-2 text-center">
                                    <button type="button" 
                                            class="btn btn-danger btn-sm delete-image"
                                            onclick="deleteInstallationImage({{ $index }})">
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
document.addEventListener('DOMContentLoaded', function() {
    const workOrderId = {{ $workOrder->id }};
    const saveButton = document.getElementById('save-installations-btn');
    const saveIndicator = document.getElementById('save-indicator');
    const dateInput = document.getElementById('installation_date');
    const prevDateBtn = document.getElementById('prev-date');
    const nextDateBtn = document.getElementById('next-date');
    const todayDateBtn = document.getElementById('today-date');

    // تحميل البيانات حسب التاريخ
    async function loadDateData(date) {
        try {
            // تنظيف البيانات الحالية
            clearFormData();
            
            // جلب البيانات للتاريخ المحدد من جدول work_order_installations
            const response = await fetch(`/admin/work-orders/${workOrderId}/installations/get-by-date?date=${date}`);
            if (!response.ok) throw new Error('فشل في جلب البيانات');
            
            const data = await response.json();
            if (data.success && data.installations && data.installations.length > 0) {
                console.log('Loaded installations data:', data.installations);
                data.installations.forEach(installation => {
                    const row = document.querySelector(`tr[data-installation="${installation.installation_type}"]`);
                    if (row) {
                        const priceInput = row.querySelector('.installation-price');
                        const numberInput = row.querySelector('.installation-number');
                        if (priceInput && numberInput) {
                            priceInput.value = installation.price || 0;
                            numberInput.value = installation.quantity || 0;
                            console.log(`Setting data for ${installation.installation_type}: price=${installation.price}, quantity=${installation.quantity}`);
                            calculateRowTotal(priceInput);
                        }
                    }
                });
            } else {
                console.log('No installations data found for date:', date);
            }
        } catch (error) {
            console.error('Error loading data:', error);
            // لا نعرض رسالة خطأ للمستخدم في حالة عدم وجود بيانات
        }
    }

    // تنظيف بيانات النموذج
    function clearFormData() {
        document.querySelectorAll('tr[data-installation]').forEach(row => {
            const priceInput = row.querySelector('.installation-price');
            const numberInput = row.querySelector('.installation-number');
            const totalInput = row.querySelector('.installation-total');
            if (priceInput) priceInput.value = '';
            if (numberInput) numberInput.value = '';
            if (totalInput) totalInput.value = '';
        });
        calculateTotals();
        refreshSummaryTable();
    }

    // معالجة تغيير التاريخ
    function handleDateChange(newDate) {
        dateInput.value = newDate;
        loadDateData(newDate);
    }

    // أزرار التنقل بين التواريخ
    if (prevDateBtn) {
        prevDateBtn.addEventListener('click', () => {
            const currentDate = new Date(dateInput.value);
            currentDate.setDate(currentDate.getDate() - 1);
            handleDateChange(currentDate.toISOString().split('T')[0]);
        });
    }

    if (nextDateBtn) {
        nextDateBtn.addEventListener('click', () => {
            const currentDate = new Date(dateInput.value);
            currentDate.setDate(currentDate.getDate() + 1);
            handleDateChange(currentDate.toISOString().split('T')[0]);
        });
    }

    if (todayDateBtn) {
        todayDateBtn.addEventListener('click', () => {
            const today = new Date().toISOString().split('T')[0];
            handleDateChange(today);
        });
    }

    // تحميل البيانات عند تغيير التاريخ
    if (dateInput) {
        dateInput.addEventListener('change', function() {
            loadDateData(this.value);
        });
    }

    // تحديث إجمالي الصف والإجمالي الكلي
    function calculateRowTotal(inputElement) {
        if (!inputElement) return;
        
        const currentRow = inputElement.closest('tr');
        if (!currentRow) return;
        
        const priceField = currentRow.querySelector('.installation-price');
        const numberField = currentRow.querySelector('.installation-number');
        const totalField = currentRow.querySelector('.installation-total');
        
        if (!priceField || !numberField || !totalField) return;
        
        const priceValue = parseFloat(priceField.value) || 0;
        const numberValue = parseFloat(numberField.value) || 0;
        const totalValue = priceValue * numberValue;
        
        // Format total with 2 decimal places and add ريال
        totalField.value = totalValue.toFixed(2) + ' ريال';
        
        calculateTotals();
        refreshSummaryTable();
    }

    // تحديث الإجمالي الكلي
    function calculateTotals() {
        let grandTotal = 0;
        
        document.querySelectorAll('tr[data-installation]').forEach(dataRow => {
            const totalField = dataRow.querySelector('.installation-total');
            if (totalField && totalField.value) {
                // Remove ريال and parse the number
                const totalValue = parseFloat(totalField.value.replace(' ريال', '')) || 0;
                grandTotal += totalValue;
            }
        });
        
        const totalElement = document.getElementById('total-installations-amount');
        if (totalElement) {
            totalElement.textContent = grandTotal.toFixed(2) + ' ريال';
        }
    }

    // تحديث جدول الملخص
    function refreshSummaryTable() {
        const summaryTbody = document.getElementById('summary-tbody');
        if (!summaryTbody) return;
        
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
                // Remove ريال and parse the total
                const totalValue = parseFloat(totalField.value.replace(' ريال', '')) || 0;
                
                if (numberValue > 0) {
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
            summaryTbody.innerHTML = `
                <tr>
                <td colspan="4" class="text-center text-muted">
                    <i class="fas fa-info-circle me-2"></i>
                        لا توجد بيانات للعرض
                </td>
                </tr>
            `;
        }
    }

    // إضافة مستمعي الأحداث للحقول
        document.querySelectorAll('.installation-price, .installation-number').forEach(input => {
            input.addEventListener('input', function() {
            calculateRowTotal(this);
        });
    });

    // حفظ البيانات
    if (saveButton) {
        saveButton.addEventListener('click', async function() {
        try {
            const installationsData = {};
            let hasValidData = false;
                const selectedDate = dateInput.value;

                if (!selectedDate) {
                alert('الرجاء تحديد تاريخ التركيب');
                return;
            }

                // جمع البيانات من الجدول
            document.querySelectorAll('tr[data-installation]').forEach(row => {
                const installationType = row.dataset.installation;
                const priceField = row.querySelector('.installation-price');
                const numberField = row.querySelector('.installation-number');
                const totalField = row.querySelector('.installation-total');

                if (priceField && numberField && totalField) {
                    const price = parseFloat(priceField.value) || 0;
                    const quantity = parseFloat(numberField.value) || 0;
                    // Remove ريال and parse the total
                    const total = parseFloat(totalField.value.replace(' ريال', '')) || 0;

                    if (quantity > 0) {
                        installationsData[installationType] = {
                            price: price,
                            quantity: quantity,
                            total: total
                        };
                        hasValidData = true;
                    }
                }
            });

            if (!hasValidData) {
                alert('الرجاء إدخال بيانات التركيبات أولاً');
                return;
            }

            // إظهار مؤشر الحفظ
                if (saveIndicator) saveIndicator.style.display = 'block';

                // إرسال البيانات للخادم
            const response = await fetch(`/admin/work-orders/${workOrderId}/installations/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    installations: installationsData,
                        installation_date: selectedDate
                })
            });

                if (!response.ok) {
                    throw new Error('فشل في حفظ البيانات');
            }

                const result = await response.json();
                
                if (result.success) {
                    alert('تم حفظ البيانات بنجاح');
                    // إعادة تحميل البيانات بعد الحفظ
                    await loadDateData(selectedDate);
                } else {
                    throw new Error(result.message || 'فشل في حفظ البيانات');
                }

            } catch (error) {
                console.error('Error saving installations:', error);
                alert('حدث خطأ أثناء حفظ البيانات: ' + error.message);
            } finally {
                if (saveIndicator) saveIndicator.style.display = 'none';
            }
        });
    }

    // تحميل البيانات الأولية للتاريخ الحالي
    loadDateData(dateInput.value);
    
    // تحديث الحسابات عند تحميل الصفحة
    document.querySelectorAll('.installation-price, .installation-number').forEach(input => {
        if (input.value) {
            calculateRowTotal(input);
        }
    });
    
    // تحديث الإجمالي الكلي عند بدء التشغيل
    calculateTotals();
    refreshSummaryTable();

    // تهيئة نموذج رفع الصور
    const imageForm = document.getElementById('installation-images-form');
    if (imageForm) {
        imageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // التحقق من اختيار الصور
            const imagesInput = document.getElementById('installation-images');
            if (!imagesInput.files.length) {
                toastr.warning('الرجاء اختيار الصور أولاً');
                return;
            }

            // التحقق من عدد وحجم الصور
            if (imagesInput.files.length > 50) {
                toastr.error('لا يمكن رفع أكثر من 50 صورة في المرة الواحدة');
                return;
            }

            let totalSize = 0;
            for (let file of imagesInput.files) {
                totalSize += file.size;
                if (!file.type.startsWith('image/')) {
                    toastr.error(`الملف ${file.name} ليس صورة`);
                    return;
                }
            }

            if (totalSize > 30 * 1024 * 1024) { // 30MB
                toastr.error('الحجم الإجمالي للصور يتجاوز 30 ميجابايت');
                return;
            }

            // تعطيل زر الرفع وإظهار حالة التحميل
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الرفع...';

            try {
                const response = await fetch(`/admin/work-orders/${workOrderId}/upload-installation-images`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    toastr.success('تم رفع الصور بنجاح');
                    // تحديث عرض الصور
                    location.reload();
                } else {
                    toastr.error(result.message || 'حدث خطأ أثناء رفع الصور');
                }
            } catch (error) {
                console.error('Error uploading images:', error);
                toastr.error('حدث خطأ أثناء رفع الصور');
            } finally {
                // إعادة تفعيل زر الرفع
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    }

    // دالة حذف الصورة
    window.deleteInstallationImage = async function(imageIndex) {
        if (!confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
            return;
        }

        try {
            const response = await fetch(`/admin/work-orders/${workOrderId}/delete-installation-image/${imageIndex}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                // إزالة عنصر الصورة من DOM
                const imageContainer = document.getElementById(`image-container-${imageIndex}`);
                if (imageContainer) {
                    imageContainer.remove();
                    toastr.success('تم حذف الصورة بنجاح');
                } else {
                    console.error('Image container not found:', imageIndex);
                    toastr.error('حدث خطأ في تحديث الصفحة');
                }
            } else {
                toastr.error(result.message || 'حدث خطأ أثناء حذف الصورة');
            }
        } catch (error) {
            console.error('Error deleting image:', error);
            toastr.error('حدث خطأ أثناء حذف الصورة');
        }
    };
});
</script>
@endpush 