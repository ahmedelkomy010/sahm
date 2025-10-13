@extends('layouts.app')

@section('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0" style="font-size:2.1rem; font-weight:800; color:#2c3e50; letter-spacing:1px; display:flex; align-items:center;">
            <i class="fas fa-calendar-day me-3" style="color:#ffc107;"></i>
            برنامج العمل اليومي
        </h2>
        <a href="{{ route('admin.work-orders.index', ['project' => $project]) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right"></i> عودة إلى قائمة أوامر العمل
        </a>
    </div>

    {{-- رسائل التنبيه --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- فلتر التاريخ -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-warning text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
            <form method="GET" class="d-flex align-items-center gap-3 flex-grow-1">
                <input type="hidden" name="project" value="{{ $project }}">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-calendar me-2"></i>
                    <label class="form-label mb-0 fw-bold">التاريخ:</label>
                    <input type="date" 
                           name="selected_date" 
                           class="form-control form-control-sm" 
                           value="{{ $selectedDate }}"
                           style="min-width: 180px; background: white; color: #000;">
                </div>
                <button type="submit" class="btn btn-light btn-sm">
                    <i class="fas fa-search me-1"></i>
                    عرض
                </button>
                <a href="{{ route('admin.work-orders.daily-program', ['project' => $project]) }}" class="btn btn-light btn-sm">
                    <i class="fas fa-sync me-1"></i>
                    اليوم
                </a>
            </form>
            <div class="d-flex gap-2">
                @if($programs->count() > 0)
                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#sendNotificationModal">
                    <i class="fas fa-bell me-1"></i>
                    إرسال البرنامج كإشعار
                </button>
                @endif
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addToProgramModal">
                    <i class="fas fa-plus me-1"></i>
                    إضافة أمر عمل للبرنامج
                </button>
            </div>
        </div>
    </div>

    <!-- الجدول -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-table me-2"></i>
            <strong>أوامر العمل المقررة اليوم ({{ $programs->count() }})</strong>
        </div>
        <div class="card-body p-0">
            @if($programs->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="min-width: 80px;">#</th>
                            <th style="min-width: 150px;">رقم أمر العمل</th>
                            <th style="min-width: 200px;">نوع العمل</th>
                            <th style="min-width: 200px;">الموقع</th>
                            <th style="min-width: 200px;">إحداثيات جوجل</th>
                            <th style="min-width: 150px;">اسم الاستشاري</th>
                            <th style="min-width: 150px;">مهندس الموقع</th>
                            <th style="min-width: 150px;">المراقب</th>
                            <th style="min-width: 150px;">المصدر</th>
                            <th style="min-width: 150px;">المستلم</th>
                            <th style="min-width: 150px;">مسئول السلامة</th>
                            <th style="min-width: 150px;">مراقب الجودة</th>
                            <th style="min-width: 250px;">وصف العمل</th>
                            <th style="min-width: 250px;">ملاحظات</th>
                            <th style="min-width: 150px;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($programs as $index => $program)
                        <tr data-start-time="{{ $program->start_time ? \Carbon\Carbon::parse($program->start_time)->format('H:i') : '' }}"
                            data-end-time="{{ $program->end_time ? \Carbon\Carbon::parse($program->end_time)->format('H:i') : '' }}">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.work-orders.show', $program->workOrder) }}" class="btn btn-link text-primary fw-bold">
                                    {{ $program->workOrder->order_number }}
                                </a>
                            </td>
                            <td>{{ $program->work_type ?? $program->workOrder->work_type }}</td>
                            <td>{{ $program->location }}</td>
                            <td>
                                @if($program->google_coordinates)
                                    <a href="https://www.google.com/maps?q={{ $program->google_coordinates }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-map-marker-alt"></i> فتح الخريطة
                                    </a>
                                @endif
                            </td>
                            <td>{{ $program->consultant_name }}</td>
                            <td>{{ $program->site_engineer }}</td>
                            <td>{{ $program->supervisor }}</td>
                            <td>{{ $program->issuer }}</td>
                            <td>{{ $program->receiver }}</td>
                            <td>{{ $program->safety_officer }}</td>
                            <td>{{ $program->quality_monitor }}</td>
                            <td>{{ $program->work_description }}</td>
                            <td>{{ $program->notes }}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#editProgramModal{{ $program->id }}">
                                    <i class="fas fa-edit"></i> تعديل
                                </button>
                                <form action="{{ route('admin.work-orders.daily-program.destroy', $program) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editProgramModal{{ $program->id }}" tabindex="-1">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">تعديل بيانات البرنامج اليومي - {{ $program->workOrder->order_number }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.work-orders.daily-program.update', $program) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">نوع العمل</label>
                                                    <input type="text" class="form-control" name="work_type" value="{{ $program->work_type }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">الموقع</label>
                                                    <input type="text" class="form-control" name="location" value="{{ $program->location }}">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">إحداثيات جوجل</label>
                                                    <input type="text" class="form-control" name="google_coordinates" value="{{ $program->google_coordinates }}" placeholder="25.123456, 45.654321">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">اسم الاستشاري</label>
                                                    <input type="text" class="form-control" name="consultant_name" value="{{ $program->consultant_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">مهندس الموقع</label>
                                                    <input type="text" class="form-control" name="site_engineer" value="{{ $program->site_engineer }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">المراقب</label>
                                                    <input type="text" class="form-control" name="supervisor" value="{{ $program->supervisor }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">المصدر</label>
                                                    <input type="text" class="form-control" name="issuer" value="{{ $program->issuer }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">المستلم</label>
                                                    <input type="text" class="form-control" name="receiver" value="{{ $program->receiver }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">مسئول السلامة</label>
                                                    <input type="text" class="form-control" name="safety_officer" value="{{ $program->safety_officer }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">مراقب الجودة</label>
                                                    <input type="text" class="form-control" name="quality_monitor" value="{{ $program->quality_monitor }}">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">وصف العمل</label>
                                                    <textarea class="form-control" name="work_description" rows="3">{{ $program->work_description }}</textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label fw-bold">ملاحظات</label>
                                                    <textarea class="form-control" name="notes" rows="3">{{ $program->notes }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                <p class="text-muted fs-5">لا توجد أوامر عمل مقررة لليوم</p>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addToProgramModal">
                    <i class="fas fa-plus me-1"></i>
                    إضافة أمر عمل للبرنامج
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add to Program Modal -->
<div class="modal fade" id="addToProgramModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">إضافة أمر عمل لبرنامج اليوم</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.work-orders.daily-program.store') }}" method="POST">
                @csrf
                <input type="hidden" name="program_date" value="{{ $selectedDate }}">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">اختر أمر العمل <span class="text-danger">*</span></label>
                            <select class="form-select work-order-select" id="workOrderSelect" name="work_order_id" required>
                                <option value="">-- ابحث عن رقم أمر العمل --</option>
                                @foreach($availableWorkOrders as $workOrder)
                                    @php
                                        $latestSurvey = $workOrder->surveys->first();
                                        $googleCoordinates = '';
                                        if ($latestSurvey) {
                                            if ($latestSurvey->start_coordinates) {
                                                $googleCoordinates = $latestSurvey->start_coordinates;
                                            } elseif ($latestSurvey->end_coordinates) {
                                                $googleCoordinates = $latestSurvey->end_coordinates;
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $workOrder->id }}"
                                        data-work-type="{{ $workOrder->work_type }}"
                                        data-district="{{ $workOrder->district }}"
                                        data-consultant="{{ $workOrder->consultant_name }}"
                                        data-address="{{ $workOrder->address }}"
                                        data-google-coordinates="{{ $googleCoordinates }}">
                                        {{ $workOrder->order_number }} - {{ $workOrder->work_type }} - {{ $workOrder->district }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">نوع العمل</label>
                            <input type="text" class="form-control" id="workTypeInput" name="work_type">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الموقع</label>
                            <input type="text" class="form-control" id="locationInput" name="location">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">إحداثيات جوجل</label>
                            <input type="text" class="form-control" id="googleCoordinatesInput" name="google_coordinates" placeholder="25.123456, 45.654321">
                            <div class="form-text">يمكنك الحصول على الإحداثيات من خرائط جوجل</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">اسم الاستشاري</label>
                            <input type="text" class="form-control" id="consultantInput" name="consultant_name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">مهندس الموقع</label>
                            <input type="text" class="form-control" id="siteEngineerInput" name="site_engineer">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">المراقب</label>
                            <input type="text" class="form-control" id="supervisorInput" name="supervisor">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">المصدر</label>
                            <input type="text" class="form-control" id="issuerInput" name="issuer">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">المستلم</label>
                            <input type="text" class="form-control" id="receiverInput" name="receiver">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">مسئول السلامة</label>
                            <input type="text" class="form-control" id="safetyOfficerInput" name="safety_officer">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">مراقب الجودة</label>
                            <input type="text" class="form-control" id="qualityMonitorInput" name="quality_monitor">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">وصف العمل</label>
                            <textarea class="form-control" name="work_description" rows="3"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">ملاحظات</label>
                            <textarea class="form-control" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>
                        إضافة للبرنامج
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send Notification Modal -->
<div class="modal fade" id="sendNotificationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-bell me-2"></i>
                    إرسال برنامج العمل كإشعار
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    اختر المستخدمين الذين تريد إرسال برنامج العمل اليومي لهم كإشعار
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-bold mb-0">المستخدمين المتاحين:</label>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary" onclick="selectAllUsers()">
                                <i class="fas fa-check-double me-1"></i>
                                تحديد الكل
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="deselectAllUsers()">
                                <i class="fas fa-times me-1"></i>
                                إلغاء التحديد
                            </button>
                        </div>
                    </div>
                    
                    <div id="usersList" class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                        <p class="text-center text-muted">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            جاري تحميل المستخدمين...
                        </p>
                    </div>
                </div>
                
                <div id="selectedCount" class="alert alert-success d-none">
                    <i class="fas fa-users me-2"></i>
                    تم تحديد <strong><span id="countNumber">0</span></strong> مستخدم
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-info" onclick="sendToSelectedUsers()">
                    <i class="fas fa-paper-plane me-1"></i>
                    إرسال الإشعار
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .table-responsive {
        overflow-x: auto;
    }
    
    .table th, .table td {
        vertical-align: middle;
        white-space: nowrap;
    }
    
    .btn-link {
        text-decoration: none;
    }
    
    .btn-link:hover {
        text-decoration: underline;
    }

    /* Fix modal input issues */
    .modal {
        z-index: 1055 !important;
    }
    
    .modal-backdrop {
        z-index: 1050 !important;
    }
    
    .modal input,
    .modal select,
    .modal textarea {
        pointer-events: auto !important;
        user-select: text !important;
        -webkit-user-select: text !important;
        -moz-user-select: text !important;
        -ms-user-select: text !important;
    }
    
    .modal-body {
        pointer-events: auto !important;
    }
    
    .modal-content {
        pointer-events: auto !important;
    }

    /* Select2 styling */
    .select2-container {
        z-index: 9999 !important;
    }
    
    .select2-dropdown {
        z-index: 9999 !important;
    }
    
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
        font-size: 1rem;
    }
    
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
        padding-right: 15px;
        padding-left: 15px;
    }
    
    .select2-results__option {
        text-align: right;
        direction: rtl;
    }
    
    .select2-search__field {
        text-align: right;
        direction: rtl;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
// إصلاح مشكلة الكتابة في الـ modal
document.addEventListener('DOMContentLoaded', function() {
    // تفعيل Select2 على حقل اختيار أمر العمل
    $('#workOrderSelect').select2({
        theme: 'bootstrap-5',
        language: 'ar',
        placeholder: 'ابحث عن رقم أمر العمل',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#addToProgramModal')
    });

    // عند تغيير اختيار أمر العمل - ملء البيانات تلقائياً
    $('#workOrderSelect').on('change', function() {
        const selectedOption = $(this).find(':selected');
        
        if (selectedOption.val()) {
            // ملء نوع العمل
            $('#workTypeInput').val(selectedOption.data('work-type') || '');
            
            // ملء الموقع (الحي + العنوان)
            const district = selectedOption.data('district') || '';
            const address = selectedOption.data('address') || '';
            const location = district + (address ? ' - ' + address : '');
            $('#locationInput').val(location);
            
            // ملء اسم الاستشاري
            $('#consultantInput').val(selectedOption.data('consultant') || '');
            
            // ملء إحداثيات جوجل من آخر مسح
            const googleCoordinates = selectedOption.data('google-coordinates') || '';
            $('#googleCoordinatesInput').val(googleCoordinates);
            
            // إظهار رسالة نجاح
            console.log('✅ تم ملء البيانات الأساسية تلقائياً');
            if (googleCoordinates) {
                console.log('✅ تم ملء إحداثيات جوجل من المسح: ' + googleCoordinates);
            }
            
            // يمكن للمستخدم ملء باقي الحقول يدوياً
        } else {
            // مسح الحقول الأساسية
            $('#workTypeInput, #locationInput, #consultantInput, #googleCoordinatesInput').val('');
        }
    });

    // إعادة تهيئة Select2 عند فتح الـ modal
    $('#addToProgramModal').on('shown.bs.modal', function() {
        $('#workOrderSelect').select2({
            theme: 'bootstrap-5',
            language: 'ar',
            placeholder: 'ابحث عن رقم أمر العمل',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#addToProgramModal')
        });
    });

    // مسح الحقول عند إغلاق الـ modal
    $('#addToProgramModal').on('hidden.bs.modal', function() {
        $('#workOrderSelect').val('').trigger('change');
        $('#addToProgramModal input[type="text"], #addToProgramModal textarea').val('');
    });
    
    // عند فتح أي modal
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        modal.addEventListener('shown.bs.modal', function() {
            // إلغاء أي events تمنع الكتابة
            const inputs = modal.querySelectorAll('input, select, textarea');
            inputs.forEach(function(input) {
                input.style.pointerEvents = 'auto';
                input.style.userSelect = 'text';
                input.removeAttribute('readonly');
                input.removeAttribute('disabled');
            });
        });
    });
});

// تصفية الجدول حسب الوقت
function filterByTime() {
    const startTime = document.getElementById('filterStartTime').value;
    const endTime = document.getElementById('filterEndTime').value;
    
    if (!startTime && !endTime) {
        alert('⚠️ من فضلك حدد على الأقل وقت البداية أو النهاية');
        return;
    }
    
    // حفظ القيم في localStorage
    if (startTime) {
        localStorage.setItem('dailyProgram_filterStartTime', startTime);
    }
    if (endTime) {
        localStorage.setItem('dailyProgram_filterEndTime', endTime);
    }
    
    const rows = document.querySelectorAll('tbody tr[data-start-time]');
    let visibleCount = 0;
    
    rows.forEach(function(row) {
        const rowStartTime = row.getAttribute('data-start-time');
        const rowEndTime = row.getAttribute('data-end-time');
        
        let shouldShow = true;
        
        // التصفية حسب وقت البداية
        if (startTime && rowStartTime) {
            shouldShow = shouldShow && (rowStartTime >= startTime);
        }
        
        // التصفية حسب وقت النهاية
        if (endTime && rowEndTime) {
            shouldShow = shouldShow && (rowEndTime <= endTime);
        }
        
        // إخفاء أو إظهار الصف
        if (shouldShow) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // تحديث العدد في الهيدر
    const headerText = document.querySelector('.card-header strong');
    if (headerText) {
        const totalCount = rows.length;
        headerText.innerHTML = `أوامر العمل المقررة اليوم (${visibleCount} من ${totalCount})`;
    }
    
    console.log(`✅ تم تصفية ${visibleCount} من ${rows.length} أوامر عمل`);
}

// إعادة تعيين التصفية
function resetTimeFilter() {
    // مسح القيم من الحقول
    document.getElementById('filterStartTime').value = '';
    document.getElementById('filterEndTime').value = '';
    
    // مسح القيم من localStorage
    localStorage.removeItem('dailyProgram_filterStartTime');
    localStorage.removeItem('dailyProgram_filterEndTime');
    
    // إظهار جميع الصفوف
    const rows = document.querySelectorAll('tbody tr[data-start-time]');
    rows.forEach(function(row) {
        row.style.display = '';
    });
    
    // تحديث العدد في الهيدر
    const headerText = document.querySelector('.card-header strong');
    if (headerText) {
        const totalCount = rows.length;
        headerText.innerHTML = `أوامر العمل المقررة اليوم (${totalCount})`;
    }
    
    console.log('✅ تم إعادة تعيين التصفية ومسح القيم المحفوظة');
}

// تحميل المستخدمين عند فتح modal الإشعارات
document.getElementById('sendNotificationModal').addEventListener('shown.bs.modal', function() {
    loadAvailableUsers();
});

// تحميل قائمة المستخدمين المتاحين
function loadAvailableUsers() {
    const project = '{{ $project }}';
    const usersList = document.getElementById('usersList');
    
    console.log('Loading users for project:', project);
    
    fetch('{{ route("admin.work-orders.daily-program.get-users") }}?project=' + project, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Users data:', data);
        
        if (data.success && data.users && data.users.length > 0) {
            let html = '<div class="row g-2">';
            data.users.forEach(user => {
                html += `
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input user-checkbox" type="checkbox" value="${user.id}" id="user_${user.id}" onchange="updateSelectedCount()">
                            <label class="form-check-label" for="user_${user.id}">
                                <i class="fas fa-user me-1 text-primary"></i>
                                ${user.name}
                            </label>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            usersList.innerHTML = html;
            console.log('Loaded', data.users.length, 'users');
        } else {
            const message = data.message || 'لا يوجد مستخدمون متاحون للمشروع المحدد';
            usersList.innerHTML = `<p class="text-center text-muted"><i class="fas fa-info-circle me-2"></i>${message}</p>`;
            console.log('No users found');
        }
    })
    .catch(error => {
        console.error('Error loading users:', error);
        usersList.innerHTML = `<p class="text-center text-danger"><i class="fas fa-exclamation-triangle me-2"></i>حدث خطأ أثناء تحميل المستخدمين: ${error.message}</p>`;
    });
}

// تحديد جميع المستخدمين
function selectAllUsers() {
    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateSelectedCount();
}

// إلغاء تحديد جميع المستخدمين
function deselectAllUsers() {
    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelectedCount();
}

// تحديث عدد المستخدمين المحددين
function updateSelectedCount() {
    const selectedCount = document.querySelectorAll('.user-checkbox:checked').length;
    const countElement = document.getElementById('selectedCount');
    const countNumber = document.getElementById('countNumber');
    
    countNumber.textContent = selectedCount;
    
    if (selectedCount > 0) {
        countElement.classList.remove('d-none');
    } else {
        countElement.classList.add('d-none');
    }
}

// إرسال الإشعار للمستخدمين المحددين
function sendToSelectedUsers() {
    const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
    
    if (selectedUsers.length === 0) {
        alert('⚠️ من فضلك اختر مستخدم واحد على الأقل');
        return;
    }
    
    if (!confirm(`هل أنت متأكد من إرسال برنامج العمل اليومي لـ ${selectedUsers.length} مستخدم؟`)) {
        return;
    }
    
    const selectedDate = '{{ $selectedDate }}';
    const project = '{{ $project }}';
    
    // إظهار رسالة تحميل
    const sendBtn = event.target;
    const originalHTML = sendBtn.innerHTML;
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري الإرسال...';
    
    fetch('{{ route("admin.work-orders.daily-program.send-notification") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            selected_date: selectedDate,
            project: project,
            user_ids: selectedUsers
        })
    })
    .then(response => response.json())
    .then(data => {
        sendBtn.disabled = false;
        sendBtn.innerHTML = originalHTML;
        
        if (data.success) {
            alert('✅ ' + data.message);
            // إغلاق الـ modal
            bootstrap.Modal.getInstance(document.getElementById('sendNotificationModal')).hide();
        } else {
            alert('❌ ' + (data.message || 'حدث خطأ أثناء إرسال الإشعار'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        sendBtn.disabled = false;
        sendBtn.innerHTML = originalHTML;
        alert('❌ حدث خطأ أثناء إرسال الإشعار');
    });
}
</script>
@endsection

