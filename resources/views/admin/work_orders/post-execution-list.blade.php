@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h3 class="mb-0 fs-4">إجراءات ما بعد التنفيذ</h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>رقم أمر العمل</th>
                                    <th>نوع العمل</th>
                                    <th>حالة التنفيذ</th>
                                    <th>حالة ما بعد التنفيذ</th>
                                    <th>قيمة ما بعد التنفيذ</th>
                                    <th>الملف المرفق</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($workOrders as $workOrder)
                                    <tr>
                                        <td>{{ $workOrder->order_number }}</td>
                                        <td>{{ $workOrder->work_type }}</td>
                                        <td>
                                            @switch($workOrder->execution_status)
                                                @case('1')
                                                    تم الاستلام من المقاول ولم تصدر شهادة الانجاز
                                                    @break
                                                @case('2')
                                                    تم تسليم المقاول ولم يتم الاستلام
                                                    @break
                                                @case('3')
                                                    دخلت مستخلص ولم تصرف
                                                    @break
                                                @case('4')
                                                    صدرت شهادة الانجاز ولم تعتمد
                                                    @break
                                                @case('5')
                                                    منتهي
                                                    @break
                                                @case('6')
                                                    مؤكد ولم تدخل مستخلص
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @switch($workOrder->post_execution_status)
                                                @case('1')
                                                    قيد المراجعة
                                                    @break
                                                @case('2')
                                                    تم المراجعة
                                                    @break
                                                @case('3')
                                                    تم الرفض
                                                    @break
                                                @case('4')
                                                    تم القبول
                                                    @break
                                                @default
                                                    -
                                            @endswitch
                                        </td>
                                        <td>{{ $workOrder->post_execution_value ? number_format($workOrder->post_execution_value, 2) . ' ₪' : '-' }}</td>
                                        <td>
                                            @if($workOrder->post_execution_file)
                                                <a href="{{ Storage::url($workOrder->post_execution_file) }}" target="_blank" class="btn btn-sm btn-info">
                                                    <i class="fas fa-file"></i> عرض الملف
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.work-orders.post-execution') . '?id=' . $workOrder->id }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> تعديل
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">لا توجد أوامر عمل</td>
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
@endsection 