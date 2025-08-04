@extends('layouts.app')

@push('scripts')
<script>
// تحديث العداد التنازلي لكل أوامر العمل
function updateCountdowns() {
    document.querySelectorAll('.countdown-badge').forEach(badge => {
        let startDays = parseInt(badge.dataset.start);
        let workOrderId = badge.dataset.workOrder;
        let approvalDate = new Date(badge.dataset.approvalDate);
        let now = new Date();
        
        // حساب الفرق بالأيام
        let diffTime = approvalDate - now;
        let currentDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        // تحديث العرض
        const daysCount = badge.querySelector('.days-count');
        if (currentDays > 0) {
            daysCount.textContent = currentDays;
            daysCount.className = 'days-count text-success';
            badge.innerHTML = `${daysCount.outerHTML}  متبقي`;
        } else if (currentDays < 0) {
            daysCount.textContent = Math.abs(currentDays);
            daysCount.className = 'days-count text-danger';
            badge.innerHTML = `${daysCount.outerHTML}  متأخر`;
            
            // إظهار تنبيه عند الوصول للصفر
            if (startDays > 0 && currentDays <= 0) {
                Swal.fire({
                    title: 'تنبيه!',
                    text: 'انتهت المدة المحددة لأمر العمل',
                    icon: 'warning',
                    confirmButtonText: 'حسناً'
                });
            }
        } else {
            daysCount.textContent = '0';
            daysCount.className = 'days-count';
            badge.innerHTML = `${daysCount.outerHTML} يوم`;
        }
    });
}

// تحديث العداد كل دقيقة
setInterval(updateCountdowns, 60000);

// تحديث العداد عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', updateCountdowns);
</script>
@endpush

@section('content')
<div class="container">
    <!-- Project Selection Banner -->
    @if(isset($project))
        @if($project == 'riyadh')
        
        @elseif($project == 'madinah')
        
        @endif
    @else
        <!-- إذا لم يتم اختيار مشروع، وجه المستخدم لاختيار مشروع -->
        <div class="alert alert-warning mb-4 d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div>
                <strong>يرجى اختيار المشروع:</strong> يجب اختيار مشروع محدد للمتابعة
                <br>
                <a href="{{ route('project.selection') }}" class="btn btn-primary btn-sm mt-2">
                    <i class="fas fa-city me-1"></i> اختيار المشروع
                </a>
            </div>
        </div>
    @endif

    @if(isset($project))
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <span class="fs-5">أوامر العمل</span>
                        @if($project == 'riyadh')
                        <span class="badge bg-light text-dark ms-2">
                            <i class="fas fa-city me-1"></i>
                            الرياض
                        </span>
                        @elseif($project == 'madinah')
                        <span class="badge bg-light text-dark ms-2">
                            <i class="fas fa-mosque me-1"></i>
                            المدينة المنورة
                        </span>
                        @endif
                    </div>
                    <a href="{{ route('project.selection') }}" class="btn btn-outline-light btn-sm ms-3">
                        <i class="fas fa-exchange-alt me-1"></i> تغيير المشروع
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-end align-items-center mb-4">
                        <!-- <a href="{{ route('general-productivity', ['project' => $project]) }}" class="btn btn-success me-2 px-4">
                            <i class="fas fa-chart-bar me-1"></i> تقرير الإنتاجية العام
                        </a>  -->
                        <a href="{{ route('admin.work-orders.create', ['project' => $project]) }}" class="btn btn-primary px-4" style="min-width: 150px;">
                            <i class="fas fa-plus me-1"></i> إنشاء أمر عمل جديد
                        </a>
                    </div>

                    فلاتر البحث
                     <div class="mb-4 bg-light p-4 rounded shadow-sm border">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-filter me-2"></i>
                                بحث في أوامر العمل
                            </h5>
                           
                        </div>
                        <form action="" method="GET" class="row g-3">
                            <input type="hidden" name="project" value="{{ $project }}">
                            
                            <!-- الصف الأول -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="order_number" class="form-label fw-bold">
                                        <i class="fas fa-hashtag text-primary me-1"></i>
                                        رقم أمر العمل
                                    </label>
                                    <input type="text" class="form-control" id="order_number" name="order_number" 
                                        value="{{ request('order_number') }}" placeholder="ابحث برقم أمر العمل...">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="work_type" class="form-label fw-bold">
                                        <i class="fas fa-tools text-primary me-1"></i>
                                        نوع العمل
                                    </label>
                                    <select class="form-select" id="work_type" name="work_type">
                                        <option value="">جميع الأنواع</option>
                                        <option value="409" {{ request('work_type') == '409' ? 'selected' : '' }}>409 - ازالة-نقل شبكة على المشترك</option>
                                        <option value="408" {{ request('work_type') == '408' ? 'selected' : '' }}>408 - ازاله عداد على المشترك</option>
                                        <option value="460" {{ request('work_type') == '460' ? 'selected' : '' }}>460 - استبدال شبكات</option>
                                        <option value="901" {{ request('work_type') == '901' ? 'selected' : '' }}>901 - اضافة عداد طاقة شمسية</option>
                                        <option value="440" {{ request('work_type') == '440' ? 'selected' : '' }}>440 - الرفع المساحي</option>
                                        <option value="410" {{ request('work_type') == '410' ? 'selected' : '' }}>410 - انشاء محطة/محول لمشترك/مشتركين</option>
                                        <option value="801" {{ request('work_type') == '801' ? 'selected' : '' }}>801 - تركيب عداد ايصال سريع</option>
                                        <option value="804" {{ request('work_type') == '804' ? 'selected' : '' }}>804 - تركيب محطة ش ارضية VM ايصال سريع</option>
                                        <option value="805" {{ request('work_type') == '805' ? 'selected' : '' }}>805 - تركيب محطة ش هوائية VM ايصال سريع</option>
                                        <option value="480" {{ request('work_type') == '480' ? 'selected' : '' }}>480 - (تسليم مفتاح) تمويل خارجي</option>
                                        <option value="441" {{ request('work_type') == '441' ? 'selected' : '' }}>441 - تعزيز شبكة ارضية ومحطات</option>
                                        <option value="442" {{ request('work_type') == '442' ? 'selected' : '' }}>442 - تعزيز شبكة هوائية ومحطات</option>
                                        <option value="802" {{ request('work_type') == '802' ? 'selected' : '' }}>802 - شبكة ارضية VL ايصال سريع</option>
                                        <option value="803" {{ request('work_type') == '803' ? 'selected' : '' }}>803 - شبكة هوائية VL ايصال سريع</option>
                                        <option value="402" {{ request('work_type') == '402' ? 'selected' : '' }}>402 - توصيل عداد بحفرية شبكة ارضيه</option>
                                        <option value="401" {{ request('work_type') == '401' ? 'selected' : '' }}>401 - (عداد بدون حفرية) أرضي/هوائي</option>
                                        <option value="404" {{ request('work_type') == '404' ? 'selected' : '' }}>404 - عداد بمحطة شبكة ارضية VM</option>
                                        <option value="405" {{ request('work_type') == '405' ? 'selected' : '' }}>405 - توصيل عداد بمحطة شبكة هوائية VM</option>
                                        <option value="430" {{ request('work_type') == '430' ? 'selected' : '' }}>430 - مخططات منح وزارة البلدية</option>
                                        <option value="450" {{ request('work_type') == '450' ? 'selected' : '' }}>450 - مشاريع ربط محطات التحويل</option>
                                        <option value="403" {{ request('work_type') == '403' ? 'selected' : '' }}>403 - توصيل عداد شبكة هوائية VL</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subscriber_name" class="form-label fw-bold">
                                        <i class="fas fa-user text-primary me-1"></i>
                                        اسم المشترك
                                    </label>
                                    <input type="text" class="form-control" id="subscriber_name" name="subscriber_name" 
                                        value="{{ request('subscriber_name') }}" placeholder="ابحث باسم المشترك...">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="district" class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                        الحي
                                    </label>
                                    <input type="text" class="form-control" id="district" name="district" 
                                        value="{{ request('district') }}" placeholder="ابحث بالحي...">
                                </div>
                            </div>
                            
                            <!-- الصف الثاني -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="office" class="form-label fw-bold">
                                        <i class="fas fa-building text-primary me-1"></i>
                                        المكتب
                                    </label>
                                    <select class="form-select" id="office" name="office">
                                        <option value="">جميع المكاتب</option>
                                        <option value="خريص" {{ request('office') == 'خريص' ? 'selected' : '' }}>خريص</option>
                                        <option value="الشرق" {{ request('office') == 'الشرق' ? 'selected' : '' }}>الشرق</option>
                                        <option value="الشمال" {{ request('office') == 'الشمال' ? 'selected' : '' }}>الشمال</option>
                                        <option value="الجنوب" {{ request('office') == 'الجنوب' ? 'selected' : '' }}>الجنوب</option>
                                        <option value="الدرعية" {{ request('office') == 'الدرعية' ? 'selected' : '' }}>الدرعية</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="consultant_name" class="form-label fw-bold">
                                        <i class="fas fa-user-tie text-primary me-1"></i>
                                        اسم الاستشاري
                                    </label>
                                    <input type="text" class="form-control" id="consultant_name" name="consultant_name" 
                                        value="{{ request('consultant_name') }}" placeholder="ابحث باسم الاستشاري...">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="station_number" class="form-label fw-bold">
                                        <i class="fas fa-broadcast-tower text-primary me-1"></i>
                                        رقم المحطة
                                    </label>
                                    <input type="text" class="form-control" id="station_number" name="station_number" 
                                        value="{{ request('station_number') }}" placeholder="ابحث برقم المحطة...">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="execution_status" class="form-label fw-bold">
                                        <i class="fas fa-tasks text-primary me-1"></i>
                                        حالة التنفيذ
                                    </label>
                                    <select class="form-select" id="execution_status" name="execution_status">
                                        <option value="">كل الحالات</option>
                                        <option value="1" {{ request('execution_status') == '1' ? 'selected' : '' }}>جاري العمل ....</option>
                                        <option value="2" {{ request('execution_status') == '2' ? 'selected' : '' }}>تم تسليم 155 ولم تصدر شهادة الانجاز</option>
                                        <option value="3" {{ request('execution_status') == '3' ? 'selected' : '' }}>صدرت شهادة ولم تعتمد</option>
                                        <option value="4" {{ request('execution_status') == '4' ? 'selected' : '' }}>تم اعتماد شهادة الانجاز</option>
                                        <option value="5" {{ request('execution_status') == '5' ? 'selected' : '' }}>مؤكد ولم تدخل مستخلص</option>
                                        <option value="6" {{ request('execution_status') == '6' ? 'selected' : '' }}>دخلت مستخلص ولم تصرف</option>
                                        <option value="7" {{ request('execution_status') == '7' ? 'selected' : '' }}>منتهي تم الصرف</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- الصف الثالث - فلاتر التاريخ والقيمة -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="approval_date_from" class="form-label fw-bold">
                                        <i class="fas fa-calendar-alt text-primary me-1"></i>
                                        تاريخ الاعتماد من
                                    </label>
                                    <input type="date" class="form-control" id="approval_date_from" name="approval_date_from" 
                                        value="{{ request('approval_date_from') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="approval_date_to" class="form-label fw-bold">
                                        <i class="fas fa-calendar-alt text-primary me-1"></i>
                                        تاريخ الاعتماد إلى
                                    </label>
                                    <input type="date" class="form-control" id="approval_date_to" name="approval_date_to" 
                                        value="{{ request('approval_date_to') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="min_value" class="form-label fw-bold">
                                        <i class="fas fa-money-bill text-primary me-1"></i>
                                        قيمة أمر العمل من
                                    </label>
                                    <input type="number" class="form-control" id="min_value" name="min_value" 
                                        value="{{ request('min_value') }}" placeholder="أقل قيمة..." step="0.01">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="max_value" class="form-label fw-bold">
                                        <i class="fas fa-money-bill text-primary me-1"></i>
                                        قيمة أمر العمل إلى
                                    </label>
                                    <input type="number" class="form-control" id="max_value" name="max_value" 
                                        value="{{ request('max_value') }}" placeholder="أعلى قيمة..." step="0.01">
                                </div>
                            </div>
                            
                            <!-- أزرار البحث -->
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-center gap-3">
                                    <button type="submit" class="btn btn-primary px-5" id="searchBtn">
                                        <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                                        <i class="fas fa-search me-2"></i> بحث
                                    </button>
                                    
                                    <button type="button" class="btn btn-outline-primary px-4" onclick="toggleAdvancedFilters()">
                                        <i class="fas fa-sliders-h me-2"></i> فلاتر متقدمة
                                    </button>
                                    <button type="button" class="btn btn-outline-warning px-4" onclick="clearAllFilters()" id="clearAllBtn" style="display: none;">
                                        <i class="fas fa-eraser me-2"></i> مسح الكل
                                    </button>
                                </div>

                                <!-- عرض الفلاتر النشطة كـ badges -->
                                <div class="mt-3 text-center" id="activeFilters">
                                    @if(request()->hasAny(['order_number', 'work_type', 'subscriber_name', 'district', 'office', 'consultant_name', 'station_number', 'execution_status', 'approval_date_from', 'approval_date_to', 'min_value', 'max_value']))
                                        <small class="text-muted">الفلاتر النشطة:</small><br>
                                        @if(request('order_number'))
                                            <span class="filter-badge">
                                                رقم أمر العمل: {{ request('order_number') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('order_number')"></i>
                                            </span>
                                        @endif
                                        @if(request('work_type'))
                                            <span class="filter-badge">
                                                نوع العمل: {{ request('work_type') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('work_type')"></i>
                                            </span>
                                        @endif
                                        @if(request('subscriber_name'))
                                            <span class="filter-badge">
                                                اسم المشترك: {{ request('subscriber_name') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('subscriber_name')"></i>
                                            </span>
                                        @endif
                                        @if(request('district'))
                                            <span class="filter-badge">
                                                الحي: {{ request('district') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('district')"></i>
                                            </span>
                                        @endif
                                        @if(request('office'))
                                            <span class="filter-badge">
                                                المكتب: {{ request('office') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('office')"></i>
                                            </span>
                                        @endif
                                        @if(request('consultant_name'))
                                            <span class="filter-badge">
                                                الاستشاري: {{ request('consultant_name') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('consultant_name')"></i>
                                            </span>
                                        @endif
                                        @if(request('station_number'))
                                            <span class="filter-badge">
                                                رقم المحطة: {{ request('station_number') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('station_number')"></i>
                                            </span>
                                        @endif
                                        @if(request('execution_status'))
                                            <span class="filter-badge">
                                                حالة التنفيذ: @switch(request('execution_status'))
                                                    @case('1') جاري العمل @break
                                                    @case('2') تم تسليم 155 ولم تصدر شهادة الانجاز @break
                                                    @case('3') صدرت شهادة ولم تعتمد @break
                                                    @case('4') تم اعتماد شهادة الانجاز @break
                                                    @case('5') مؤكد ولم تدخل مستخلص @break
                                                    @case('6') دخلت مستخلص ولم تصرف @break
                                                    @case('7') منتهي تم الصرف @break
                                                    @default {{ request('execution_status') }}
                                                @endswitch
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('execution_status')"></i>
                                            </span>
                                        @endif
                                        @if(request('approval_date_from'))
                                            <span class="filter-badge">
                                                من تاريخ: {{ request('approval_date_from') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('approval_date_from')"></i>
                                            </span>
                                        @endif
                                        @if(request('approval_date_to'))
                                            <span class="filter-badge">
                                                إلى تاريخ: {{ request('approval_date_to') }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('approval_date_to')"></i>
                                            </span>
                                        @endif
                                        @if(request('min_value'))
                                            <span class="filter-badge">
                                                قيمة أدنى: {{ number_format(request('min_value'), 2) }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('min_value')"></i>
                                            </span>
                                        @endif
                                        @if(request('max_value'))
                                            <span class="filter-badge">
                                                قيمة أعلى: {{ number_format(request('max_value'), 2) }}
                                                <i class="fas fa-times clear-filter" onclick="clearFilter('max_value')"></i>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                   عدد النتائج وعناصر التحكم 
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <div class="text-muted mb-2 mb-md-0">
                            <i class="fas fa-list me-1"></i>
                            عدد النتائج: {{ $workOrders->total() }} | عرض {{ $workOrders->firstItem() ?? 0 }} - {{ $workOrders->lastItem() ?? 0 }} من {{ $workOrders->total() }}
                        </div>
                        
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            @if(request('order_number') || request('work_type') || request('execution_status') || request('subscriber_name') || request('district') || request('office') || request('consultant_name') || request('station_number') || request('approval_date_from') || request('approval_date_to') || request('min_value') || request('max_value'))
                                <div class="text-muted">
                                    <i class="fas fa-filter me-1"></i>
                                    تم تطبيق الفلتر
                                    <small class="text-primary ms-2">
                                        ({{ collect([request('order_number'), request('work_type'), request('execution_status'), request('subscriber_name'), request('district'), request('office'), request('consultant_name'), request('station_number'), request('approval_date_from'), request('approval_date_to'), request('min_value'), request('max_value')])->filter()->count() }} فلتر نشط)
                                    </small>
                                </div>
                            @endif
                            
                            <!-- اختيار عدد النتائج في الصفحة -->
                            <div class="d-flex align-items-center">
                                <label for="per_page" class="form-label me-2 mb-0 text-muted">عرض:</label>
                                <select class="form-select form-select-sm" id="per_page" onchange="changePerPage(this.value)" style="width: auto;">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span class="text-muted ms-2">نتيجة</span>
                            </div>
                        </div>
                    </div>

                    <!-- مؤشر البحث -->
                    <div id="searchLoader" class="text-center py-4 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">جاري البحث...</span>
                        </div>
                        <p class="mt-2 text-muted">جاري البحث في أوامر العمل...</p>
                    </div>

                    <!-- عرض النتائج -->
                    <div class="table-responsive" id="resultsTable">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>رقم أمر العمل</th>
                                    <th>رقم نوع العمل</th>
                                    <th>وصف العمل</th>
                                    <th>اسم المشترك</th>
                                    <th>الحي</th>
                                    <th>المكتب</th>
                                    <th>اسم الاستشاري</th>
                                    <th>رقم المحطة</th>
                                    <th>تاريخ الاعتماد</th>
                                    <th>حالة التنفيذ</th>
                                    <th>قيمة أمر العمل المبدئية غير شامل الاستشاري</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="workOrdersTableBody">
                                @forelse ($workOrders as $workOrder)
                                    <tr class="work-order-row" data-id="{{ $workOrder->id }}" data-execution-status="{{ $workOrder->execution_status }}">
                                        <td>{{ $loop->iteration + ($workOrders->currentPage() - 1) * $workOrders->perPage() }}</td>
                                        <td>{{ $workOrder->order_number }}</td>
                                        <td>{{ $workOrder->work_type }}</td>
                                        <td>
                                            @switch($workOrder->work_type)
                                                @case('409')
                                                    1 -ازالة-نقل شبكة على المشترك
                                                    @break
                                                @case('408')
                                                    2 - ازاله عداد على المشترك
                                                    @break
                                                @case('460')
                                                    3 - استبدال شبكات
                                                    @break
                                                @case('901')
                                                    4 - اضافة عداد طاقة شمسية
                                                    @break
                                                @case('440')
                                                    5 - الرفع المساحي
                                                    @break
                                                @case('410')
                                                    6 - انشاء محطة/محول لمشترك/مشتركين
                                                    @break
                                                @case('801')
                                                    7 - تركيب عداد ايصال سريع
                                                    @break
                                                @case('804')
                                                    8 - تركيب محطة ش ارضية VM ايصال سريع
                                                    @break
                                                @case('805')
                                                    9 - تركيب محطة ش هوائية VM ايصال سريع
                                                    @break
                                                @case('480')
                                                    10 - (تسليم مفتاح) تمويل خارجي
                                                    @break
                                                @case('441')
                                                    11 - تعزيز شبكة ارضية ومحطات
                                                    @break
                                                @case('442')
                                                    12 - تعزيز شبكة هوائية ومحطات
                                                    @break
                                                @case('802')
                                                    13 - شبكة ارضية VL ايصال سريع
                                                    @break
                                                @case('803')
                                                    14 - شبكة هوائية VL ايصال سريع
                                                    @break
                                                @case('402')
                                                    15 - توصيل عداد بحفرية شبكة ارضيه
                                                    @break
                                                @case('401')
                                                    16 - (عداد بدون حفرية) أرضي/هوائي
                                                    @break
                                                @case('404')
                                                    17 - عداد بمحطة شبكة ارضية VM
                                                    @break
                                                @case('405')
                                                    18 - توصيل عداد بمحطة شبكة هوائية VM
                                                    @break
                                                @case('430')
                                                    19 - مخططات منح وزارة البلدية
                                                    @break
                                                @case('450')
                                                    20 - مشاريع ربط محطات التحويل
                                                    @break
                                                @case('403')
                                                    21 - توصيل عداد شبكة هوائية VL
                                                    @break
                                                @default
                                                    {{ $workOrder->work_type }}
                                            @endswitch
                                        </td>
                                        <td>{{ $workOrder->subscriber_name }}</td>
                                        <td>{{ $workOrder->district }}</td>
                                        <td>
                                            @switch($workOrder->office)
                                                @case('خريص')
                                                    <span class="badge bg-primary">خريص</span>
                                                    @break
                                                @case('الشرق')
                                                    <span class="badge bg-success">الشرق</span>
                                                    @break
                                                @case('الشمال')
                                                    <span class="badge bg-info">الشمال</span>
                                                    @break
                                                @case('الجنوب')
                                                    <span class="badge bg-warning">الجنوب</span>
                                                    @break
                                                @case('الدرعية')
                                                    <span class="badge bg-secondary">الدرعية</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-light text-dark">{{ $workOrder->office ?? 'غير محدد' }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $workOrder->consultant_name }}</td>
                                        <td>{{ $workOrder->station_number }}</td>
                                        <td>
                                            <div class="approval-date-container">
                                                <div class="date-section">
                                                   
                                                    <span class="date-value">{{ date('Y-m-d', strtotime($workOrder->approval_date)) }}</span>
                                                </div>
                                                @php
                                                    $remainingDays = $workOrder->manual_days ?? 0;
                                                @endphp
                                                <div class="countdown-section">
                                                    <span class="badge countdown-badge {{ $remainingDays > 0 ? 'bg-success' : ($remainingDays < 0 ? 'bg-danger' : 'bg-primary') }}" 
                                                          data-start="{{ $remainingDays }}" 
                                                          data-work-order="{{ $workOrder->id }}"
                                                          data-approval-date="{{ $workOrder->approval_date }}">
                                                        <i class="fas fa-clock me-1"></i>
                                                        @if($remainingDays > 0)
                                                            <span class="days-count">{{ $remainingDays }}</span>  متبقي
                                                        @elseif($remainingDays < 0)
                                                            <span class="days-count">{{ abs($remainingDays) }}</span>  متأخر
                                                        @else
                                                            <span class="days-count">0</span> يوم
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @switch($workOrder->execution_status)
                                                @case('1')
                                                    <span class="badge bg-info">جاري العمل ....</span>
                                                    @break
                                                @case('2')
                                                    <span class="badge bg-info">تم تسليم 155 ولم تصدر شهادة الانجاز</span>
                                                    @break
                                                @case('3')
                                                    <span class="badge bg-primary">صدرت شهادة ولم تعتمد</span>
                                                    @break
                                                @case('4')
                                                    <span class="badge bg-secondary">تم اعتماد شهادة الانجاز</span>
                                                    @break
                                                @case('5')
                                                    <span class="badge bg-success">مؤكد ولم تدخل مستخلص</span>
                                                    @break
                                                @case('6')
                                                    <span class="badge bg-dark">دخلت مستخلص ولم تصرف</span>
                                                    @break
                                                @case('7')
                                                    <span class="badge bg-success">منتهي تم الصرف</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $workOrder->execution_status }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ number_format($workOrder->order_value_without_consultant ?? 0, 2) }} ريال</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-sm btn-info">عرض</a>
                                                <a href="{{ route('admin.work-orders.edit', $workOrder) }}" class="btn btn-sm btn-primary">تعديل</a>
                                                
                                                @if(auth()->user()->is_admin)
                                                    <form action="{{ route('admin.work-orders.destroy', $workOrder) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العنصر؟');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="noResultsRow">
                                        <td colspan="13" class="text-center">لا توجد أوامر عمل لهذا المشروع</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $workOrders->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    /* تنسيق عمود تاريخ الاعتماد */
    .approval-date-container {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .date-section {
        display: flex;
        align-items: center;
    }

    .date-value {
        color: #666;
        font-size: 0.9rem;
    }

    .countdown-section {
        display: flex;
    }

    .countdown-badge {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
        border-radius: 4px;
    }

    .countdown-badge .days-count {
        font-weight: 600;
        background-color: rgba(130, 192, 211, 0.84);
        padding: 0.1rem 0.3rem;
        border-radius: 3px;
        margin: 0 0.2rem;
    }

    .countdown-badge.bg-success {
        background-color:rgb(8, 223, 58);
    }

    .countdown-badge.bg-danger {
        background-color:rgb(201, 0, 20);
    }

    .countdown-badge.bg-primary {
        background-color: #6c757d;
    }

    /* تنسيق عام للجدول */
    .table th, .table td {
        text-align: right;
    }
    
    .btn-group {
        display: flex;
    }
    
    .btn-group .btn {
        margin-left: 5px;
    }
    
    /* تحسين فلاتر البحث */
    .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 8px 12px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .advanced-filters {
        display: none;
        animation: slideDown 0.3s ease;
    }
    
    .advanced-filters.show {
        display: block;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .filter-badge {
        background: linear-gradient(45deg, #007bff, #0056b3);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
        display: inline-block;
        margin: 2px;
    }
    
    .clear-filter {
        cursor: pointer;
        margin-left: 5px;
        opacity: 0.7;
    }
    
    .clear-filter:hover {
        opacity: 1;
    }
    
    /* تحسين تجربة التفاعل مع الحقول */
    .form-group.focused .form-label {
        color: #0d6efd;
        font-weight: 600;
    }
    
    .form-group.focused .form-control,
    .form-group.focused .form-select {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    /* تحسين مظهر الأزرار */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* تحسين مظهر البحث النشط */
    .search-active {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(13, 110, 253, 0); }
        100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
    }
    
    /* تحسين مظهر الجدول */
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transform: scale(1.001);
        transition: all 0.2s ease;
    }
    
    /* تأثيرات البحث المباشر */
    .search-result {
        background-color: rgba(40, 167, 69, 0.1) !important;
        border-left: 4px solid #28a745;
        animation: highlight 0.5s ease-in-out;
    }
    
    @keyframes highlight {
        0% { 
            background-color: rgba(255, 193, 7, 0.3);
            transform: scale(1.01);
        }
        100% { 
            background-color: rgba(40, 167, 69, 0.1);
            transform: scale(1);
        }
    }
    
    /* مؤشر التحميل */
    #searchLoader {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        border: 2px dashed #007bff;
    }
    
    .opacity-50 {
        opacity: 0.5;
        transition: opacity 0.3s ease;
    }
    
    /* تحسين الفلاتر النشطة */
    .form-control:not(:placeholder-shown),
    .form-select:not([value=""]) {
        border-left: 3px solid #28a745;
        background-color: rgba(40, 167, 69, 0.05);
    }
    
    /* تأثيرات النتائج المخفية */
    .work-order-row[style*="display: none"] {
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
    }
    
    /* تحسين مظهر النتائج الظاهرة */
    .work-order-row {
        transition: all 0.3s ease;
    }
    
    .work-order-row:not([style*="display: none"]) {
        opacity: 1;
        transform: translateX(0);
    }
    
    /* تحسين مظهر الجدول عند البحث */
    .table-responsive.searching {
        position: relative;
    }
    
    .table-responsive.searching::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 40%, rgba(0,123,255,0.1) 50%, transparent 60%);
        animation: searching 1.5s infinite;
        pointer-events: none;
        z-index: 1;
    }
    
    @keyframes searching {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    /* تحسين مظهر عداد النتائج */
    .results-counter {
        font-weight: 600;
        color: #495057;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 8px 16px;
        border-radius: 20px;
        border: 1px solid #dee2e6;
    }
    
    /* تحسين أزرار الفلاتر المتقدمة */
    .advanced-filters {
        border-top: 2px dashed #007bff;
        padding-top: 1rem;
        margin-top: 1rem;
    }
    
    .advanced-filters.show {
        animation: slideDown 0.5s ease;
        border-top-color: #28a745;
    }
    
    /* تحسين زر مسح الكل */
    #clearAllBtn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    #clearAllBtn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(255, 193, 7, 0.3);
    }
    
    #clearAllBtn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }
    
    #clearAllBtn:hover::before {
        left: 100%;
    }
</style>

<script>
function toggleAdvancedFilters() {
    const advancedFilters = document.querySelectorAll('.advanced-filters');
    advancedFilters.forEach(filter => {
        filter.classList.toggle('show');
    });
    
    const button = event.target;
    const icon = button.querySelector('i');
    
    if (icon.classList.contains('fa-sliders-h')) {
        icon.classList.remove('fa-sliders-h');
        icon.classList.add('fa-times');
        button.innerHTML = '<i class="fas fa-times me-2"></i> إخفاء الفلاتر المتقدمة';
    } else {
        icon.classList.remove('fa-times');
        icon.classList.add('fa-sliders-h');
        button.innerHTML = '<i class="fas fa-sliders-h me-2"></i> فلاتر متقدمة';
    }
}

// تنظيف الفلاتر الفردية
function clearFilter(filterName) {
    const url = new URL(window.location);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

// تغيير عدد النتائج في الصفحة
function changePerPage(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    url.searchParams.delete('page'); // إعادة تعيين رقم الصفحة
    window.location.href = url.toString();
}

// متغيرات البحث المباشر
let searchTimeout;
let allWorkOrders = [];

// تخزين جميع أوامر العمل في الذاكرة للبحث السريع
function storeWorkOrders() {
    const rows = document.querySelectorAll('.work-order-row');
    allWorkOrders = Array.from(rows).map(row => ({
        element: row,
        id: row.dataset.id,
        orderNumber: row.cells[1].textContent.trim(),
        workType: row.cells[2].textContent.trim(),
        workDescription: row.cells[3].textContent.trim(),
        subscriberName: row.cells[4].textContent.trim(),
        district: row.cells[5].textContent.trim(),
        office: row.cells[6].textContent.trim(),
        consultantName: row.cells[7].textContent.trim(),
        stationNumber: row.cells[8].textContent.trim(),
        approvalDate: row.cells[9].textContent.trim(),
        executionStatus: row.dataset.executionStatus,
        orderValue: row.cells[11].textContent.trim()
    }));
}

// البحث المباشر في أوامر العمل
function liveSearch() {
    const filters = {
        orderNumber: document.getElementById('order_number').value.toLowerCase(),
        workType: document.getElementById('work_type').value,
        subscriberName: document.getElementById('subscriber_name').value.toLowerCase(),
        district: document.getElementById('district').value.toLowerCase(),
        office: document.getElementById('office').value,
        consultantName: document.getElementById('consultant_name').value.toLowerCase(),
        stationNumber: document.getElementById('station_number').value.toLowerCase(),
        executionStatus: document.getElementById('execution_status').value,
        approvalDateFrom: document.getElementById('approval_date_from').value,
        approvalDateTo: document.getElementById('approval_date_to').value,
        minValue: parseFloat(document.getElementById('min_value').value) || 0,
        maxValue: parseFloat(document.getElementById('max_value').value) || Infinity
    };
    
    // إظهار مؤشر التحميل
    document.getElementById('searchLoader').classList.remove('d-none');
    document.getElementById('resultsTable').classList.add('opacity-50');
    document.getElementById('resultsTable').classList.add('searching');
    
    // تأخير البحث لتحسين الأداء
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        performSearch(filters);
    }, 300);
}

// تنفيذ البحث وإظهار النتائج
function performSearch(filters) {
    let visibleCount = 0;
    
    allWorkOrders.forEach(workOrder => {
        let isVisible = true;
        
        // فلتر رقم أمر العمل
        if (filters.orderNumber && !workOrder.orderNumber.toLowerCase().includes(filters.orderNumber)) {
            isVisible = false;
        }
        
        // فلتر نوع العمل
        if (filters.workType && workOrder.workType !== filters.workType) {
            isVisible = false;
        }
        
        // فلتر اسم المشترك
        if (filters.subscriberName && !workOrder.subscriberName.toLowerCase().includes(filters.subscriberName)) {
            isVisible = false;
        }
        
        // فلتر الحي
        if (filters.district && !workOrder.district.toLowerCase().includes(filters.district)) {
            isVisible = false;
        }
        
        // فلتر المكتب
        if (filters.office && !workOrder.office.includes(filters.office)) {
            isVisible = false;
        }
        
        // فلتر اسم الاستشاري
        if (filters.consultantName && !workOrder.consultantName.toLowerCase().includes(filters.consultantName)) {
            isVisible = false;
        }
        
        // فلتر رقم المحطة
        if (filters.stationNumber && !workOrder.stationNumber.toLowerCase().includes(filters.stationNumber)) {
            isVisible = false;
        }
        
        // فلتر حالة التنفيذ
        if (filters.executionStatus && workOrder.executionStatus !== filters.executionStatus) {
            isVisible = false;
        }
        
        // فلتر التاريخ
        if (filters.approvalDateFrom || filters.approvalDateTo) {
            const approvalDate = new Date(workOrder.approvalDate);
            
            if (filters.approvalDateFrom) {
                const fromDate = new Date(filters.approvalDateFrom);
                if (approvalDate < fromDate) {
                    isVisible = false;
                }
            }
            
            if (filters.approvalDateTo) {
                const toDate = new Date(filters.approvalDateTo);
                if (approvalDate > toDate) {
                    isVisible = false;
                }
            }
        }
        
        // فلتر القيمة
        const orderValueMatch = workOrder.orderValue.match(/[\d,]+\.?\d*/);
        if (orderValueMatch) {
            const orderValue = parseFloat(orderValueMatch[0].replace(/,/g, ''));
            if (orderValue < filters.minValue || orderValue > filters.maxValue) {
                isVisible = false;
            }
        }
        
        // إظهار/إخفاء الصف
        if (isVisible) {
            workOrder.element.style.display = '';
            workOrder.element.classList.add('search-result');
            visibleCount++;
        } else {
            workOrder.element.style.display = 'none';
            workOrder.element.classList.remove('search-result');
        }
    });
    
    // إخفاء مؤشر التحميل
    document.getElementById('searchLoader').classList.add('d-none');
    document.getElementById('resultsTable').classList.remove('opacity-50');
    document.getElementById('resultsTable').classList.remove('searching');
    
    // إظهار/إخفاء رسالة "لا توجد نتائج"
    const noResultsRow = document.getElementById('noResultsRow');
    if (visibleCount === 0) {
        if (!noResultsRow) {
            const tbody = document.getElementById('workOrdersTableBody');
            const newRow = document.createElement('tr');
            newRow.id = 'noResultsRow';
            newRow.innerHTML = '<td colspan="13" class="text-center">لا توجد نتائج مطابقة للبحث</td>';
            tbody.appendChild(newRow);
        } else {
            noResultsRow.style.display = '';
            noResultsRow.innerHTML = '<td colspan="13" class="text-center">لا توجد نتائج مطابقة للبحث</td>';
        }
    } else {
        if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
    }
    
    // تحديث عداد النتائج
    updateResultsCounter(visibleCount);
    
    // إظهار/إخفاء زر "مسح الكل"
    const clearAllBtn = document.getElementById('clearAllBtn');
    if (hasActiveFilters()) {
        clearAllBtn.style.display = 'inline-block';
    } else {
        clearAllBtn.style.display = 'none';
    }
}

// تحديث عداد النتائج
function updateResultsCounter(visibleCount) {
    const counterElement = document.querySelector('.text-muted');
    if (counterElement) {
        const totalCount = allWorkOrders.length;
        counterElement.innerHTML = `<i class="fas fa-list me-1"></i>عدد النتائج: ${visibleCount} من ${totalCount}`;
        counterElement.classList.add('results-counter');
        
        // إضافة تأثير بصري عند تحديث العداد
        counterElement.style.animation = 'none';
        setTimeout(() => {
            counterElement.style.animation = 'pulse 0.5s ease-in-out';
        }, 10);
    }
}

// إعادة تعيين البحث
function resetSearch() {
    // مسح جميع حقول البحث
    document.querySelectorAll('input, select').forEach(input => {
        if (input.name !== 'project') {
            input.value = '';
        }
    });
    
    // إظهار جميع الصفوف
    allWorkOrders.forEach(workOrder => {
        workOrder.element.style.display = '';
        workOrder.element.classList.remove('search-result');
    });
    
    // إخفاء رسالة "لا توجد نتائج"
    const noResultsRow = document.getElementById('noResultsRow');
    if (noResultsRow) {
        noResultsRow.style.display = 'none';
    }
    
    // إخفاء زر "مسح الكل"
    document.getElementById('clearAllBtn').style.display = 'none';
    
    // تحديث عداد النتائج
    updateResultsCounter(allWorkOrders.length);
}

// مسح جميع الفلاتر (دالة منفصلة لزر "مسح الكل")
function clearAllFilters() {
    resetSearch();
}

// فحص إذا كان هناك فلاتر نشطة
function hasActiveFilters() {
    const inputs = document.querySelectorAll('#order_number, #work_type, #subscriber_name, #district, #office, #consultant_name, #station_number, #execution_status, #approval_date_from, #approval_date_to, #min_value, #max_value');
    
    return Array.from(inputs).some(input => {
        return input.value && input.value.trim() !== '';
    });
}

// التعامل مع مؤشر التحميل ووظائف الفلاتر
document.addEventListener('DOMContentLoaded', function() {
    // تخزين أوامر العمل في الذاكرة
    storeWorkOrders();
    
    // إضافة حدث لإظهار/إخفاء الفلاتر المتقدمة عند التحميل
    const dateFilters = document.querySelectorAll('[name="approval_date_from"], [name="approval_date_to"], [name="min_value"], [name="max_value"]');
    dateFilters.forEach(filter => {
        filter.closest('.col-md-3').classList.add('advanced-filters');
    });
    
    // إضافة أحداث البحث المباشر لجميع حقول الفلتر
    const searchInputs = document.querySelectorAll('#order_number, #work_type, #subscriber_name, #district, #office, #consultant_name, #station_number, #execution_status, #approval_date_from, #approval_date_to, #min_value, #max_value');
    searchInputs.forEach(input => {
        input.addEventListener('input', liveSearch);
        input.addEventListener('change', liveSearch);
    });
    
    // منع إرسال النموذج إذا كان البحث المباشر نشطاً
    const searchForm = document.querySelector('form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            liveSearch();
        });
    }
    
    // تحسين تجربة المستخدم مع الأحداث
    const inputs = document.querySelectorAll('input[type="text"], input[type="date"], input[type="number"]');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
    
    // إضافة tooltip للفلاتر
    const labels = document.querySelectorAll('.form-label');
    labels.forEach(label => {
        const icon = label.querySelector('i');
        if (icon) {
            label.setAttribute('title', 'اضغط للتركيز على هذا الحقل');
            label.style.cursor = 'pointer';
            label.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input, select');
                if (input) input.focus();
            });
        }
    });
});

// تحسين تجربة المستخدم مع Select2 للقوائم المنسدلة الطويلة
$(document).ready(function() {
    if (typeof $.fn.select2 !== 'undefined') {
        $('#work_type').select2({
            placeholder: 'اختر نوع العمل...',
            allowClear: true,
            width: '100%',
            language: {
                noResults: function() {
                    return 'لا توجد نتائج';
                },
                searching: function() {
                    return 'جاري البحث...';
                }
            }
        });
    }
});
</script>
@endsection 