@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Project Selection Banner -->
    @if(isset($project) && $project == 'riyadh')
    <div class="alert alert-info mb-4 d-flex align-items-center" role="alert">
        <i class="fas fa-city me-2"></i>
        <div>
            <strong>مشروع الرياض:</strong> أنت تعمل حاليًا على أوامر العمل لمشروع الرياض
        </div>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <span class="fs-5">أوامر العمل</span>
                        @if(isset($project) && $project == 'riyadh')
                        <a href="{{ route('project.selection') }}" class="btn btn-outline-light btn-sm ms-3">
                            <i class="fas fa-exchange-alt me-1"></i> تغيير المشروع
                        </a>
                        @endif
                    </div>
                    <a href="{{ route('admin.work-orders.create') }}" class="btn btn-light btn-sm">إنشاء أمر عمل جديد</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-end align-items-center mb-4">
                        <a href="{{ route('admin.work-orders.create') }}" class="btn btn-primary px-4" style="min-width: 150px;">
                            <i class="fas fa-plus me-1"></i> إنشاء أمر عمل جديد
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>رقم الطلب</th>
                                    <th>نوع العمل</th>
                                    <th>اسم المشترك</th>
                                    <th>الحي</th>
                                    <th>المكتب</th>
                                    <th>اسم الاستشاري</th>
                                    <th>رقم المحطة</th>
                                    <th>تاريخ الاعتماد</th>
                                    <th>حالة التنفيذ</th>
                                    <th>قيمة أمر العمل</th>
                                    <th>المرفقات</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($workOrders as $workOrder)
                                    <tr>
                                        <td>{{ $workOrder->id }}</td>
                                        <td>{{ $workOrder->order_number }}</td>
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
                                                @case('2')
                                                    <span class="badge bg-info">جاري العمل ....</span>
                                                    @break
                                                @case('1')
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
                                        <td>{{ number_format($workOrder->order_value_with_consultant, 2) }} ₪</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $workOrder->files->count() }}</span>
                                        </td>
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
                                        <td colspan="13" class="text-center">لا توجد أوامر عمل</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $workOrders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
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