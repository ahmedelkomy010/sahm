@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">الرخص</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>رقم أمر العمل</th>
                                    <th>نوع العمل</th>
                                    <th>شهادات التنسيق</th>
                                    <th>حظر</th>
                                    <th>جهة الحظر</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workOrders as $workOrder)
                                <tr>
                                    <td>{{ $workOrder->order_number }}</td>
                                    <td>{{ $workOrder->work_type }}</td>
                                    <td>
                                        @if($workOrder->license && $workOrder->license->coordination_certificate_path)
                                            <a href="{{ asset('storage/' . $workOrder->license->coordination_certificate_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                عرض الشهادة
                                            </a>
                                        @else
                                            <span class="text-muted">لا يوجد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($workOrder->license && $workOrder->license->has_restriction)
                                            <span class="badge badge-danger">نعم</span>
                                        @else
                                            <span class="badge badge-success">لا</span>
                                        @endif
                                    </td>
                                    <td>{{ $workOrder->license ? $workOrder->license->restriction_authority : '-' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#licenseModal{{ $workOrder->id }}">
                                            تعديل
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal -->
                                <div class="modal fade" id="licenseModal{{ $workOrder->id }}" tabindex="-1" role="dialog" aria-labelledby="licenseModalLabel{{ $workOrder->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="licenseModalLabel{{ $workOrder->id }}">تعديل الرخص - {{ $workOrder->order_number }}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.work-orders.update-license', $workOrder->id) }}" method="POST" enctype="multipart/form-data" id="licenseForm{{ $workOrder->id }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <!-- شهادات التنسيق -->
                                                    <div class="form-group">
                                                        <label>شهادات التنسيق</label>
                                                        <input type="file" name="coordination_certificate" class="form-control-file">
                                                        @if($workOrder->license && $workOrder->license->coordination_certificate_path)
                                                            <small class="form-text text-muted">تم رفع شهادة مسبقاً</small>
                                                        @endif
                                                    </div>

                                                    <!-- الحظر -->
                                                    <div class="form-group">
                                                        <label>هل يوجد حظر؟</label>
                                                        <select name="has_restriction" class="form-control" id="hasRestriction{{ $workOrder->id }}">
                                                            <option value="0" {{ $workOrder->license && !$workOrder->license->has_restriction ? 'selected' : '' }}>لا</option>
                                                            <option value="1" {{ $workOrder->license && $workOrder->license->has_restriction ? 'selected' : '' }}>نعم</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group restriction-authority-group" id="restrictionAuthorityGroup{{ $workOrder->id }}" style="display: {{ $workOrder->license && $workOrder->license->has_restriction ? 'block' : 'none' }}">
                                                        <label>جهة الحظر</label>
                                                        <input type="text" name="restriction_authority" class="form-control" value="{{ $workOrder->license ? $workOrder->license->restriction_authority : '' }}">
                                                    </div>

                                                    <!-- تفعيل الرخصة -->
                                                    <div class="form-group">
                                                        <label>تفعيل الرخصة</label>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>تاريخ بداية الرخصة</label>
                                                                <input type="date" name="license_start_date" class="form-control" value="{{ $workOrder->license ? $workOrder->license->license_start_date : '' }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>تاريخ نهاية الرخصة</label>
                                                                <input type="date" name="license_end_date" class="form-control" value="{{ $workOrder->license ? $workOrder->license->license_end_date : '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="mt-2">
                                                            <label>أيام التنبيه قبل الانتهاء</label>
                                                            <input type="number" name="license_alert_days" class="form-control" value="{{ $workOrder->license ? $workOrder->license->license_alert_days : 30 }}" min="1">
                                                        </div>
                                                    </div>

                                                    <!-- تمديد الرخصة -->
                                                    <div class="form-group">
                                                        <label>تمديد الرخصة</label>
                                                        <input type="file" name="license_extension_file" class="form-control-file">
                                                        @if($workOrder->license && $workOrder->license->license_extension_file_path)
                                                            <small class="form-text text-muted">تم رفع ملف مسبقاً</small>
                                                        @endif
                                                        <div class="row mt-2">
                                                            <div class="col-md-4">
                                                                <label>تاريخ بداية التمديد</label>
                                                                <input type="date" name="license_extension_start_date" class="form-control" value="{{ $workOrder->license ? $workOrder->license->license_extension_start_date : '' }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>تاريخ نهاية التمديد</label>
                                                                <input type="date" name="license_extension_end_date" class="form-control" value="{{ $workOrder->license ? $workOrder->license->license_extension_end_date : '' }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>أيام التنبيه</label>
                                                                <input type="number" name="license_extension_alert_days" class="form-control" value="{{ $workOrder->license ? $workOrder->license->license_extension_alert_days : 30 }}" min="1">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- إخلاء وإغلاق الرخصة -->
                                                    <div class="form-group">
                                                        <label>إخلاء وإغلاق الرخصة</label>
                                                        <input type="file" name="license_closure_file" class="form-control-file">
                                                        @if($workOrder->license && $workOrder->license->license_closure_file_path)
                                                            <small class="form-text text-muted">تم رفع ملف مسبقاً</small>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                                    <button type="submit" class="btn btn-primary" id="saveLicense{{ $workOrder->id }}">حفظ التغييرات</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $workOrders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إضافة مستمعي الأحداث لجميع حقول has_restriction
    document.querySelectorAll('[id^="hasRestriction"]').forEach(function(select) {
        select.addEventListener('change', function() {
            const workOrderId = this.id.replace('hasRestriction', '');
            const authorityGroup = document.getElementById('restrictionAuthorityGroup' + workOrderId);
            authorityGroup.style.display = this.value === '1' ? 'block' : 'none';
        });
    });

    // التحقق من صحة النموذج قبل الإرسال
    document.querySelectorAll('form[id^="licenseForm"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const workOrderId = this.id.replace('licenseForm', '');
            
            // إضافة مؤشر التحميل
            const submitButton = document.getElementById('saveLicense' + workOrderId);
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = 'جاري الحفظ...';
            submitButton.disabled = true;

            // طباعة البيانات للتحقق
            console.log('Form Data:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            // إرسال البيانات
            $.ajax({
                url: this.action,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Success response:', response);
                    if (response.success) {
                        alert('تم حفظ البيانات بنجاح');
                        location.reload();
                    } else {
                        alert(response.message || 'حدث خطأ أثناء حفظ البيانات');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                    alert('حدث خطأ أثناء حفظ البيانات: ' + error);
                },
                complete: function() {
                    // إعادة تفعيل الزر
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }
            });
        });
    });
});
</script>
@endpush
@endsection 