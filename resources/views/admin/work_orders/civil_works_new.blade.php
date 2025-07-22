@extends('layouts.admin')

@section('title', 'الأعمال المدنية - ' . $workOrder->order_number)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-hard-hat me-2"></i>
                    الأعمال المدنية - {{ $workOrder->order_number }}
                </h1>
                <a href="{{ route('admin.work-orders.execution', $workOrder) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    العودة للتنفيذ
                </a>
            </div>
        </div>
    </div>

    <!-- معلومات أمر العمل -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>رقم الأمر:</strong> {{ $workOrder->order_number }}
                        </div>
                        <div class="col-md-3">
                            <strong>نوع العمل:</strong> {{ $workOrder->work_type }}
                        </div>
                        <div class="col-md-3">
                            <strong>اسم المشترك:</strong> {{ $workOrder->subscriber_name }}
                        </div>
                        <div class="col-md-3">
                            <strong>حالة التنفيذ:</strong> 
                            @switch($workOrder->execution_status)
                                @case(1) جاري العمل @break
                                @case(2) تم تسليم 155 @break
                                @case(3) صدرت شهادة @break
                                @case(4) تم اعتماد الشهادة @break
                                @case(5) مؤكد @break
                                @case(6) دخل مستخلص @break
                                @case(7) منتهي @break
                                @default غير محدد
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- تحديد التاريخ -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-calendar-alt me-2"></i>
                    اختيار تاريخ العمل
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label for="work-date" class="form-label">تاريخ العمل:</label>
                            <input type="date" 
                                   id="work-date" 
                                   class="form-control"
                                   value="{{ $workDate }}"
                                   max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="button" 
                                    class="btn btn-primary" 
                                    id="load-date-btn"
                                    style="margin-top: 32px;">
                                <i class="fas fa-search me-2"></i>
                                تحميل بيانات هذا التاريخ
                            </button>
                        </div>
                        <div class="col-md-4">
                            @if($availableDates->count() > 0)
                                <label class="form-label">التواريخ المحفوظة:</label>
                                <select class="form-select" id="saved-dates">
                                    <option value="">اختر تاريخ محفوظ...</option>
                                    @foreach($availableDates as $date)
                                        <option value="{{ $date }}" {{ $date == $workDate ? 'selected' : '' }}>
                                            {{ $date }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إضافة عنصر جديد -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-plus-circle me-2"></i>
                    إضافة عنصر جديد
                </div>
                <div class="card-body">
                    <form id="add-item-form">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="work-type" class="form-label">نوع العمل <span class="text-danger">*</span></label>
                                <select id="work-type" class="form-select" required>
                                    <option value="">اختر نوع العمل...</option>
                                    <option value="حفر تربة ترابية غير مسفلتة">حفر تربة ترابية غير مسفلتة</option>
                                    <option value="حفر تربة ترابية مسفلتة">حفر تربة ترابية مسفلتة</option>
                                    <option value="حفر تربة صخرية غير مسفلتة">حفر تربة صخرية غير مسفلتة</option>
                                    <option value="حفر تربة صخرية مسفلتة">حفر تربة صخرية مسفلتة</option>
                                                     
                                    <option value="تمديدات كيبلات">تمديدات كيبلات</option>
                                    <option value="الاغلاقات">الاغلاقات</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="cable-type" class="form-label">نوع الكابل <span class="text-danger">*</span></label>
                                <select id="cable-type" class="form-select" required>
                                    <option value="">اختر نوع الكابل...</option>
                                    <option value="1 كابل منخفض">1 كابل منخفض</option>
                                    <option value="2 كابل منخفض">2 كابل منخفض</option>
                                    <option value="3 كابل منخفض">3 كابل منخفض</option>
                                    <option value="4 كابل منخفض">4 كابل منخفض</option>
                                    <option value="1 كابل متوسط">1 كابل متوسط</option>
                                    <option value="2 كابل متوسط">2 كابل متوسط</option>
                                    <option value="3 كابل متوسط">3 كابل متوسط</option>
                                    <option value="4 كابل متوسط">4 كابل متوسط</option>
                                    <option value="حفر مفتوح اكبر من 4 كابلات">حفر مفتوح اكبر من 4 كابلات</option>
                                    <option value="تمديد كيبل 4x70 منخفض">تمديد كيبل 4x70 منخفض</option>
                                    <option value="تمديد كيبل 4x185 منخفض">تمديد كيبل 4x185 منخفض</option>
                                    <option value="تمديد كيبل 4x300 منخفض">تمديد كيبل 4x300 منخفض</option>
                                    <option value="تمديد كيبل 3x500 متوسط">تمديد كيبل 3x500 متوسط</option>
                                    <option value="تمديد كيبل 3x400 متوسط">تمديد كيبل 3x400 متوسط</option>
                                    <option value="اغلاق أسفلت طبقة أولى">اغلاق أسفلت طبقة أولى</option>
                                    <option value="اغلاق كشط واعادة السفلتة">اغلاق كشط واعادة السفلتة</option>
                                    <option value="بلاط وانترلوك">بلاط وانترلوك</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="length" class="form-label">الطول (متر) <span class="text-danger">*</span></label>
                                <input type="number" id="length" class="form-control" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-2">
                                <label for="unit-price" class="form-label">سعر الوحدة (ريال) <span class="text-danger">*</span></label>
                                <input type="number" id="unit-price" class="form-control" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-2"></i>
                                    إضافة
                                </button>
                            </div>
                        </div>
                        
                        <!-- خيارات إضافية للحفريات الحجمية -->
                        <div class="row mt-3" id="volume-inputs" style="display: none;">
                            <div class="col-md-3">
                                <label for="width" class="form-label">العرض (متر)</label>
                                <input type="number" id="width" class="form-control" step="0.01" min="0">
                            </div>
                            <div class="col-md-3">
                                <label for="depth" class="form-label">العمق (متر)</label>
                                <input type="number" id="depth" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- الإحصائيات اليومية -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <div class="display-6"><i class="fas fa-ruler"></i></div>
                    <h4 class="mb-1" id="total-length">{{ number_format($dailyStats['total_length'], 2) }}</h4>
                    <small>إجمالي الطول (متر)</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <div class="display-6"><i class="fas fa-money-bill-wave"></i></div>
                    <h4 class="mb-1" id="total-cost">{{ number_format($dailyStats['total_cost'], 2) }}</h4>
                    <small>إجمالي التكلفة (ريال)</small>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول البيانات -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-table me-2"></i>
                        بيانات الأعمال المدنية - {{ $workDate }}
                    </div>
                    <div>
                        <button type="button" class="btn btn-light btn-sm me-2" id="clear-date-btn">
                            <i class="fas fa-trash me-2"></i>
                            مسح بيانات هذا التاريخ
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="civil-works-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>نوع العمل</th>
                                    <th>نوع الكابل</th>
                                    <th>الأبعاد (طول×عرض×عمق) متر</th>
                                    <th>الحجم (م³)</th>
                                    <th>سعر الوحدة</th>
                                    <th>التكلفة الإجمالية</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="civil-works-tbody">
                                @forelse($savedDailyData as $index => $item)
                                    <tr data-id="{{ $item->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->work_type }}</td>
                                        <td>{{ $item->cable_type }}</td>
                                        <td>
                                            {{ number_format($item->length, 2) }}
                                            @if($item->width && $item->depth)
                                                × {{ number_format($item->width, 2) }} × {{ number_format($item->depth, 2) }}
                                            @endif
                                        </td>
                                        <td>{{ $item->volume ? number_format($item->volume, 2) : '-' }}</td>
                                        <td>{{ number_format($item->unit_price, 2) }}</td>
                                        <td>{{ number_format($item->total_cost, 2) }} ريال</td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-danger btn-sm delete-item" 
                                                    data-id="{{ $item->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="no-data-row">
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="fas fa-clipboard-list fa-2x mb-2"></i><br>
                                            لا توجد بيانات لهذا التاريخ<br>
                                            <small>ابدأ بإضافة عناصر باستخدام النموذج أعلاه</small>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.btn {
    border-radius: 6px;
}
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    border-top: none;
}
</style>
@endpush

@push('scripts')
<script>
// متغيرات عامة
const workOrderId = {{ $workOrder->id }};
let currentDate = '{{ $workDate }}';

// تحميل البيانات عند تغيير التاريخ
document.getElementById('work-date').addEventListener('change', function() {
    currentDate = this.value;
    loadDateData();
});

document.getElementById('load-date-btn').addEventListener('click', function() {
    currentDate = document.getElementById('work-date').value;
    loadDateData();
});

// تحميل التاريخ المحفوظ المحدد
document.getElementById('saved-dates')?.addEventListener('change', function() {
    if (this.value) {
        currentDate = this.value;
        document.getElementById('work-date').value = this.value;
        loadDateData();
    }
});

// إضافة عنصر جديد
document.getElementById('add-item-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        work_date: currentDate,
        work_type: document.getElementById('work-type').value,
        cable_type: document.getElementById('cable-type').value,
        length: document.getElementById('length').value,
        width: document.getElementById('width').value || null,
        depth: document.getElementById('depth').value || null,
        unit_price: document.getElementById('unit-price').value
    };
    
    // التحقق من صحة البيانات
    if (!formData.work_type || !formData.cable_type || !formData.length || !formData.unit_price) {
        alert('يرجى ملء جميع الحقول المطلوبة');
        return;
    }
    
    saveItem(formData);
});

// إظهار/إخفاء حقول الحجم
document.getElementById('work-type').addEventListener('change', function() {
    const volumeInputs = document.getElementById('volume-inputs');
    const selectedType = this.value;
    const cableTypeSelect = document.getElementById('cable-type');
    
    // إظهار حقول الحجم للحفريات المفتوحة
    if (selectedType.includes('مفتوح')) {
        volumeInputs.style.display = 'block';
        // جعل حقول العرض والعمق مطلوبة
        document.getElementById('width').required = true;
        document.getElementById('depth').required = true;
    } else {
        volumeInputs.style.display = 'none';
        // إلغاء إلزامية حقول العرض والعمق
        document.getElementById('width').required = false;
        document.getElementById('depth').required = false;
        // مسح القيم
        document.getElementById('width').value = '';
        document.getElementById('depth').value = '';
    }

    // تحديث خيارات نوع الكابل بناءً على نوع العمل
    if (selectedType === 'تمديدات كيبلات') {
        // عرض فقط خيارات التمديد
        Array.from(cableTypeSelect.options).forEach(option => {
            if (!option.value.includes('تمديد') && option.value !== '') {
                option.style.display = 'none';
            } else {
                option.style.display = '';
            }
        });
    } else if (selectedType === 'الاغلاقات') {
        // عرض فقط خيارات الإغلاق والأسفلت
        Array.from(cableTypeSelect.options).forEach(option => {
            const value = option.value;
            if (value === '' || 
                value === 'اغلاق أسفلت طبقة أولى' || 
                value === 'اغلاق كشط واعادة السفلتة' || 
                value === 'بلاط وانترلوك') {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
    } else if (selectedType.includes('حفر تربة')) {
        // عرض خيارات الكابلات الأساسية وحفر مفتوح
        Array.from(cableTypeSelect.options).forEach(option => {
            const value = option.value;
            if (value === '' ||
                value.startsWith('1 كابل') || 
                value.startsWith('2 كابل') || 
                value.startsWith('3 كابل') || 
                value.startsWith('4 كابل') ||
                value === 'حفر مفتوح اكبر من 4 كابلات') {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
    } else {
        // عرض جميع الخيارات ما عدا خيارات التمديد والإغلاق
        Array.from(cableTypeSelect.options).forEach(option => {
            if (option.value.includes('تمديد') || option.value.includes('اغلاق')) {
                option.style.display = 'none';
            } else {
                option.style.display = '';
            }
        });
    }

    // إعادة تعيين اختيار نوع الكابل
    cableTypeSelect.value = '';
});

// تحديث سعر الوحدة وإظهار حقول الحجم عند اختيار نوع الكابل
document.getElementById('cable-type').addEventListener('change', function() {
    const selectedType = this.value;
    const unitPriceInput = document.getElementById('unit-price');
    const volumeInputs = document.getElementById('volume-inputs');
    
    // تعيين الأسعار الافتراضية للتمديدات
    const defaultPrices = {
        'تمديد كيبل 4x70 منخفض': 50,
        'تمديد كيبل 4x185 منخفض': 75,
        'تمديد كيبل 4x300 منخفض': 100,
        'تمديد كيبل 3x500 متوسط': 150,
        'تمديد كيبل 3x400 متوسط': 125
    };

    // إظهار حقول الحجم لحفر مفتوح اكبر من 4 كابلات
    if (selectedType === 'حفر مفتوح اكبر من 4 كابلات') {
        volumeInputs.style.display = 'block';
        document.getElementById('width').required = true;
        document.getElementById('depth').required = true;
    } else if (!document.getElementById('work-type').value.includes('مفتوح')) {
        volumeInputs.style.display = 'none';
        document.getElementById('width').required = false;
        document.getElementById('depth').required = false;
        document.getElementById('width').value = '';
        document.getElementById('depth').value = '';
    }

    if (defaultPrices[selectedType]) {
        unitPriceInput.value = defaultPrices[selectedType];
        unitPriceInput.readOnly = true;
    } else {
        unitPriceInput.readOnly = false;
        unitPriceInput.value = '';
    }
});

// حذف عنصر
document.addEventListener('click', function(e) {
    if (e.target.closest('.delete-item')) {
        const itemId = e.target.closest('.delete-item').dataset.id;
        if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
            deleteItem(itemId);
        }
    }
});

// مسح بيانات التاريخ
document.getElementById('clear-date-btn').addEventListener('click', function() {
    if (confirm('هل أنت متأكد من حذف جميع بيانات تاريخ ' + currentDate + '؟')) {
        clearDateData();
    }
});

// دالة تحميل البيانات
async function loadDateData() {
    
    try {
        const response = await fetch(`/admin/work-orders/${workOrderId}/civil-works/data?date=${currentDate}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            updateTable(result.data);
            updateStats(result.stats);
        } else {
            alert('خطأ في تحميل البيانات: ' + result.message);
        }
    } catch (error) {
        console.error('خطأ:', error);
        alert('حدث خطأ في تحميل البيانات');
    }
    
}

// دالة حفظ عنصر جديد
async function saveItem(data) {
    
    try {
        const response = await fetch(`/admin/work-orders/${workOrderId}/civil-works/save`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // مسح النموذج
            document.getElementById('add-item-form').reset();
            document.getElementById('volume-inputs').style.display = 'none';
            
            // إعادة تحميل البيانات
            loadDateData();
            
            alert('تم حفظ البيانات بنجاح');
        } else {
            alert('خطأ في حفظ البيانات: ' + result.message);
        }
    } catch (error) {
        console.error('خطأ:', error);
        alert('حدث خطأ في حفظ البيانات');
    }
    
}

// دالة حذف عنصر
async function deleteItem(itemId) {
    
    try {
        const response = await fetch(`/admin/work-orders/${workOrderId}/civil-works/delete/${itemId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            loadDateData();
            alert('تم حذف العنصر بنجاح');
        } else {
            alert('خطأ في حذف العنصر: ' + result.message);
        }
    } catch (error) {
        console.error('خطأ:', error);
        alert('حدث خطأ في حذف العنصر');
    }
    
}

// دالة مسح بيانات التاريخ
async function clearDateData() {
    
    try {
        const response = await fetch(`/admin/work-orders/${workOrderId}/civil-works/clear?date=${currentDate}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            loadDateData();
            alert(result.message);
        } else {
            alert('خطأ: ' + result.message);
        }
    } catch (error) {
        console.error('خطأ:', error);
        alert('حدث خطأ في حذف البيانات');
    }
    
}

// دالة تحديث الجدول
function updateTable(data) {
    const tbody = document.getElementById('civil-works-tbody');
    
    if (!data || data.length === 0) {
        tbody.innerHTML = `
            <tr id="no-data-row">
                <td colspan="8" class="text-center text-muted py-4">
                    <i class="fas fa-clipboard-list fa-2x mb-2"></i><br>
                    لا توجد بيانات لهذا التاريخ<br>
                    <small>ابدأ بإضافة عناصر باستخدام النموذج أعلاه</small>
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = data.map((item, index) => `
        <tr data-id="${item.id}">
            <td>${index + 1}</td>
            <td>${item.work_type}</td>
            <td>${item.cable_type}</td>
            <td>${parseFloat(item.length).toFixed(2)}${
                item.width && item.depth ? 
                ` × ${parseFloat(item.width).toFixed(2)} × ${parseFloat(item.depth).toFixed(2)}` : 
                ''
            }</td>
            <td>${item.volume ? parseFloat(item.volume).toFixed(2) : '-'}</td>
            <td>${parseFloat(item.unit_price).toFixed(2)}</td>
            <td>${parseFloat(item.total_cost).toFixed(2)} ريال</td>
            <td>
                <button type="button" 
                        class="btn btn-danger btn-sm delete-item" 
                        data-id="${item.id}">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

// دالة تحديث الإحصائيات
function updateStats(stats) {
    document.getElementById('total-length').textContent = parseFloat(stats.total_length || 0).toFixed(2);
    document.getElementById('total-cost').textContent = parseFloat(stats.total_cost || 0).toFixed(2);
}
</script>
@endpush 