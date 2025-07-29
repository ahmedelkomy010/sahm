@extends('layouts.admin')

@section('title', 'تقرير الإنتاجية - الرياض')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        تقرير الإنتاجية - الرياض
                    </h5>
                </div>
                <div class="card-body">
                    <!-- فلاتر البحث -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date" class="form-label">من تاريخ</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date" class="form-label">إلى تاريخ</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-primary" id="search">
                                <i class="fas fa-search me-1"></i>
                                بحث
                            </button>
                        </div>
                    </div>

                    <!-- ملخص الإحصائيات -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6 class="card-title">إجمالي أوامر العمل</h6>
                                    <h3 class="card-text" id="total_work_orders">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6 class="card-title">إجمالي البنود</h6>
                                    <h3 class="card-text" id="total_items">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h6 class="card-title">الكمية المنفذة</h6>
                                    <h3 class="card-text" id="total_executed">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h6 class="card-title">القيمة الإجمالية</h6>
                                    <h3 class="card-text" id="total_value">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- جدول البيانات -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="productivity_table">
                            <thead class="table-light">
                                <tr>
                                    <th>رقم أمر العمل</th>
                                    <th>اسم المشترك</th>
                                    <th>نوع العمل</th>
                                    <th>الحي</th>
                                    <th>عدد البنود</th>
                                    <th>البنود المنفذة</th>
                                    <th>نسبة الإنجاز</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- سيتم ملء البيانات ديناميكياً -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal تفاصيل أمر العمل -->
<div class="modal fade" id="workOrderDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل أمر العمل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- سيتم ملء التفاصيل ديناميكياً -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // تهيئة DataTable
    var table = $('#productivity_table').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
        }
    });

    // دالة تحديث البيانات
    function updateData() {
        $.ajax({
            url: "{{ route('admin.work-orders.general-productivity-data') }}",
            data: {
                start_date: $('#start_date').val(),
                end_date: $('#end_date').val(),
                project: 'riyadh'
            },
            success: function(response) {
                if (response.success) {
                    // تحديث الإحصائيات
                    $('#total_work_orders').text(response.data.summary.total_work_orders);
                    $('#total_items').text(response.data.summary.total_items_count);
                    $('#total_executed').text(response.data.summary.total_executed_quantity);
                    $('#total_value').text(new Intl.NumberFormat('ar-SA', {
                        style: 'currency',
                        currency: 'SAR'
                    }).format(response.data.summary.total_amount));

                    // تحديث الجدول
                    table.clear();
                    response.data.work_orders.forEach(function(order) {
                        table.row.add([
                            order.order_number,
                            order.subscriber_name,
                            order.work_type,
                            order.district,
                            order.items_count,
                            order.executed_items_count,
                            order.completion_rate + '%',
                            order.created_at,
                            '<button class="btn btn-sm btn-info show-details" data-id="' + order.id + '">' +
                            '<i class="fas fa-eye"></i> التفاصيل</button>'
                        ]);
                    });
                    table.draw();
                }
            }
        });
    }

    // حدث البحث
    $('#search').click(updateData);

    // عرض التفاصيل
    $(document).on('click', '.show-details', function() {
        var orderId = $(this).data('id');
        // هنا يمكنك إضافة كود لعرض تفاصيل أمر العمل في Modal
        $('#workOrderDetailsModal').modal('show');
    });

    // تحديث البيانات عند تحميل الصفحة
    updateData();
});
</script>
@endpush 