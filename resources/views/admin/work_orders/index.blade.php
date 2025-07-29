@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Project Selection Banner -->
    @if(isset($project))
        @if($project == 'riyadh')
        <div class="alert alert-info mb-4 d-flex align-items-center" role="alert">
            <i class="fas fa-city me-2 text-blue-500"></i>
           
        </div>
        @elseif($project == 'madinah')
        <div class="alert alert-success mb-4 d-flex align-items-center" role="alert">
            <i class="fas fa-mosque me-2 text-green-500"></i>
        
        </div>
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

                    <!-- أزرار الإجراءات -->
                    <div class="d-flex justify-content-end align-items-center mb-4">
                        <!-- <a href="{{ route('general-productivity', ['project' => $project]) }}" class="btn btn-success me-2 px-4">
                            <i class="fas fa-chart-bar me-1"></i> تقرير الإنتاجية العام
                        </a> -->
                        <a href="{{ route('admin.work-orders.create', ['project' => $project]) }}" class="btn btn-primary px-4" style="min-width: 150px;">
                            <i class="fas fa-plus me-1"></i> إنشاء أمر عمل جديد
                        </a>
                    </div>

                    <!-- فلاتر البحث -->
                    <!-- <div class="mb-4 bg-light p-4 rounded shadow-sm border">
                        <h5 class="mb-3 text-primary">
                            <i class="fas fa-filter me-2"></i>
                            فلترة أوامر العمل
                        </h5>
                        <form action="" method="GET" class="row g-3">
                            <input type="hidden" name="project" value="{{ $project }}">
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="order_number" class="form-label fw-bold">
                                        <i class="fas fa-hashtag text-primary me-1"></i>
                                        رقم الطلب
                                    </label>
                                    <input type="text" class="form-control" id="order_number" name="order_number" 
                                        value="{{ request('order_number') }}" placeholder="ابحث برقم الطلب...">
                                </div>
                            </div>
                            
                            
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="execution_status" class="form-label fw-bold">
                                        <i class="fas fa-tasks text-primary me-1"></i>
                                        حالة التنفيذ
                                    </label>
                                    <select class="form-select" id="execution_status" name="execution_status">
                                        <option value="">كل الحالات</option>
                                        <option value="pending" {{ request('execution_status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                        <option value="in_progress" {{ request('execution_status') == 'in_progress' ? 'selected' : '' }}>جاري التنفيذ</option>
                                        <option value="completed" {{ request('execution_status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                        <option value="cancelled" {{ request('execution_status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4 text-center">
                                <button type="submit" class="btn btn-primary px-5 me-2">
                                    <i class="fas fa-search me-2"></i> بحث
                                </button>
                                <a href="?project={{ $project }}" class="btn btn-secondary px-5">
                                    <i class="fas fa-redo me-2"></i> إعادة تعيين
                                </a>
                            </div>
                        </form>
                    </div> -->

                    <!-- عدد النتائج -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-muted">
                            <i class="fas fa-list me-1"></i>
                            عدد النتائج: {{ $workOrders->total() }}
                        </div>
                        @if(request('order_number') || request('work_type') || request('execution_status'))
                            <div class="text-muted">
                                <i class="fas fa-filter me-1"></i>
                                تم تطبيق الفلتر
                            </div>
                        @endif
                    </div>

                    <!-- عرض النتائج -->
                    <div class="table-responsive">
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
                            <tbody>
                                @forelse ($workOrders as $workOrder)
                                    <tr>
                                        <td>{{ $workOrder->id }}</td>
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
                                        <td>{{ date('Y-m-d', strtotime($workOrder->approval_date)) }}</td>
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
                                                
                                                <form action="{{ route('admin.work-orders.destroy', $workOrder) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا العنصر؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center">لا توجد أوامر عمل لهذا المشروع</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $workOrders->appends(['project' => $project])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    .table th, .table td {
        text-align: right;
    }
    
    .btn-group {
        display: flex;
    }
    
    .btn-group .btn {
        margin-left: 5px;
    }
</style>
@endsection 