@extends('layouts.app')

@section('css')
<style>
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    
    .btn-group .btn {
        margin-left: 2px;
        margin-right: 2px;
    }
    
    /* تحسين عرض الجدول */
    #licensesTable {
        width: 100%;
        min-width: 1200px; /* يضمن أن الجدول سيحتوي جميع الأعمدة دون ضغط */
    }
    
    #licensesTable th, #licensesTable td {
        white-space: nowrap;
        padding: 0.5rem;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    /* تصميم أعمدة العرض والإخفاء */
    .form-check.form-switch {
        padding-right: 2.5em;
    }
    
    .column-toggle:checked + .form-check-label {
        font-weight: bold;
    }
    
    /* تحسين عرض نافذة الاختبارات */
    .test-details-btn {
        white-space: nowrap;
    }
    
    .modal-header, .modal-footer {
        padding: 0.75rem 1rem;
    }
    
    .modal-body {
        padding: 1.25rem;
    }
    
    .modal-lg {
        max-width: 800px;
    }
    
    .modal .table {
        margin-bottom: 0;
    }
    
    .modal .badge {
        padding: 0.4em 0.6em;
        font-size: 85%;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fs-4">بيانات الرخص</h3>
                    <div>
                        <button id="columnToggleBtn" class="btn btn-light btn-sm me-2">
                            <i class="fas fa-columns"></i> إدارة الأعمدة
                        </button>
                        @if(isset($licenses) && $licenses->count() > 0 && $licenses->first()->workOrder)
                        <a href="{{ route('admin.work-orders.license', $licenses->first()->workOrder) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit"></i> نموذج الرخص
                        </a>
                        @endif
                        <a href="{{ route('admin.work-orders.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-right"></i> عودة لأوامر العمل
                        </a>
                    </div>
                </div>

                <!-- قائمة إدارة الأعمدة -->
                <div id="columnTogglePanel" class="card-body p-3 border-bottom" style="display: none;">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">إدارة أعمدة العرض</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input column-toggle" type="checkbox" id="toggle-col-order-number" data-column="1" checked>
                                    <label class="form-check-label" for="toggle-col-order-number">رقم أمر العمل</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input column-toggle" type="checkbox" id="toggle-col-work-type" data-column="2" checked>
                                    <label class="form-check-label" for="toggle-col-work-type">نوع العمل</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input column-toggle" type="checkbox" id="toggle-col-certificates" data-column="3" checked>
                                    <label class="form-check-label" for="toggle-col-certificates">شهادات التنسيق</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input column-toggle" type="checkbox" id="toggle-col-start-date" data-column="4" checked>
                                    <label class="form-check-label" for="toggle-col-start-date">تاريخ البداية</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input column-toggle" type="checkbox" id="toggle-col-end-date" data-column="5" checked>
                                    <label class="form-check-label" for="toggle-col-end-date">تاريخ النهاية</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input column-toggle" type="checkbox" id="toggle-col-status" data-column="6" checked>
                                    <label class="form-check-label" for="toggle-col-status">حالة الرخصة</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input column-toggle" type="checkbox" id="toggle-col-days" data-column="7" checked>
                                    <label class="form-check-label" for="toggle-col-days">أيام متبقية</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input column-toggle" type="checkbox" id="toggle-col-length" data-column="8" checked>
                                    <label class="form-check-label" for="toggle-col-length">طول الرخصة</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input column-toggle" type="checkbox" id="toggle-col-tests" data-column="9" checked>
                                    <label class="form-check-label" for="toggle-col-tests">الاختبارات</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input column-toggle" type="checkbox" id="toggle-col-extension" data-column="10" checked>
                                    <label class="form-check-label" for="toggle-col-extension">تمديد الرخصة</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input column-toggle" type="checkbox" id="toggle-col-restriction" data-column="11" checked>
                                    <label class="form-check-label" for="toggle-col-restriction">يوجد حظر</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input column-toggle" type="checkbox" id="toggle-col-restriction-auth" data-column="12" checked>
                                    <label class="form-check-label" for="toggle-col-restriction-auth">جهة الحظر</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input column-toggle" type="checkbox" id="toggle-col-closure" data-column="13" checked>
                                    <label class="form-check-label" for="toggle-col-closure">إغلاق الرخصة</label>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button id="showAllColumns" class="btn btn-sm btn-outline-primary me-2">عرض جميع الأعمدة</button>
                                <button id="hideAllColumns" class="btn btn-sm btn-outline-secondary me-2">إخفاء جميع الأعمدة</button>
                                <button id="resetColumns" class="btn btn-sm btn-outline-dark">إعادة ضبط</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="licensesTable" class="table table-striped table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>رقم أمر العمل</th>
                                    <th>نوع العمل</th>
                                    <th>شهادات التنسيق</th>
                                    <th>تاريخ بداية الرخصة</th>
                                    <th>تاريخ نهاية الرخصة</th>
                                    <th>حالة الرخصة</th>
                                    <th>أيام متبقية</th>
                                    <th>طول الرخصة</th>
                                    <th>الاختبارات</th>
                                    <th>تمديد الرخصة</th>
                                    <th>يوجد حظر</th>
                                    <th>جهة الحظر</th>
                                    <th>إغلاق الرخصة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($licenses as $index => $license)
                                    @php
                                        $startDate = $license->license_start_date ? \Carbon\Carbon::parse($license->license_start_date) : null;
                                        $endDate = $license->license_end_date ? \Carbon\Carbon::parse($license->license_end_date) : null;
                                        $daysRemaining = $endDate ? $endDate->diffInDays(now(), false) : null;
                                        
                                        // حالة الرخصة
                                        $status = 'غير محدد';
                                        $statusClass = 'secondary';
                                        
                                        if ($startDate && $endDate) {
                                            if ($endDate->isPast()) {
                                                $status = 'منتهية';
                                                $statusClass = 'danger';
                                            } elseif ($startDate->isFuture()) {
                                                $status = 'لم تبدأ بعد';
                                                $statusClass = 'info';
                                            } else {
                                                $status = 'سارية';
                                                $statusClass = 'success';
                                                
                                                // تحذير قبل انتهاء الرخصة
                                                if ($daysRemaining !== null && $daysRemaining <= $license->license_alert_days) {
                                                    $status = 'قرب الانتهاء';
                                                    $statusClass = 'warning';
                                                }
                                            }
                                        }

                                        // تحديد حالة الاختبارات
                                        $testCount = 0;
                                        $completedTests = 0;
                                        
                                        if (isset($license->has_depth_test)) {
                                            $testCount++;
                                            if ($license->has_depth_test) $completedTests++;
                                        }
                                        if (isset($license->has_soil_compaction_test)) {
                                            $testCount++;
                                            if ($license->has_soil_compaction_test) $completedTests++;
                                        }
                                        if (isset($license->has_rc1_mc1_test)) {
                                            $testCount++;
                                            if ($license->has_rc1_mc1_test) $completedTests++;
                                        }
                                        if (isset($license->has_asphalt_test)) {
                                            $testCount++;
                                            if ($license->has_asphalt_test) $completedTests++;
                                        }
                                        if (isset($license->has_soil_test)) {
                                            $testCount++;
                                            if ($license->has_soil_test) $completedTests++;
                                        }
                                        if (isset($license->has_interlock_test)) {
                                            $testCount++;
                                            if ($license->has_interlock_test) $completedTests++;
                                        }
                                        
                                        $testProgress = $testCount > 0 ? round(($completedTests / $testCount) * 100) : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $license->workOrder->order_number ?? 'غير محدد' }}</td>
                                        <td>
                                            @if($license->workOrder)
                                                @switch($license->workOrder->work_type)
                                                    @case('1')
                                                        تمديد شبكات المياه
                                                        @break
                                                    @case('2')
                                                        تمديد شبكات الصرف الصحي
                                                        @break
                                                    @case('3')
                                                        صيانة شبكات المياه
                                                        @break
                                                    @case('4')
                                                        صيانة شبكات الصرف الصحي
                                                        @break
                                                    @case('5')
                                                        إنشاء محطات الضخ
                                                        @break
                                                    @default
                                                        {{ $license->workOrder->work_type }}
                                                @endswitch
                                            @else
                                                غير محدد
                                            @endif
                                        </td>
                                        <td>
                                            @if($license->coordination_certificate_path)
                                                <a href="{{ asset('storage/' . $license->coordination_certificate_path) }}" target="_blank" class="badge bg-info text-decoration-none">
                                                    <i class="fas fa-file-alt"></i> عرض
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">غير متوفر</span>
                                            @endif
                                        </td>
                                        <td>{{ $license->license_start_date ? $license->license_start_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>{{ $license->license_end_date ? $license->license_end_date->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td><span class="badge bg-{{ $statusClass }}">{{ $status }}</span></td>
                                        <td>
                                            @if($daysRemaining !== null)
                                                @if($daysRemaining < 0)
                                                    <span class="text-danger">منتهية منذ {{ abs($daysRemaining) }} يوم</span>
                                                @else
                                                    <span class="text-{{ $daysRemaining <= $license->license_alert_days ? 'warning' : 'success' }}">{{ $daysRemaining }} يوم</span>
                                                @endif
                                            @else
                                                غير محدد
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($license->license_length) && $license->license_length > 0)
                                                {{ $license->license_length }} متر
                                            @else
                                                <span class="text-muted">غير محدد</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($testCount > 0)
                                                <div class="mb-2">
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar bg-{{ $testProgress == 100 ? 'success' : ($testProgress > 50 ? 'info' : 'warning') }}" 
                                                            role="progressbar" 
                                                            style="width: {{ $testProgress }}%;" 
                                                            aria-valuenow="{{ $testProgress }}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                            {{ $completedTests }}/{{ $testCount }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-primary test-details-btn" data-bs-toggle="modal" data-bs-target="#testDetailsModal{{ $license->id }}">
                                                    <i class="fas fa-list"></i> تفاصيل الاختبارات
                                                </button>
                                                
                                                <!-- Modal for Test Details -->
                                                <div class="modal fade" id="testDetailsModal{{ $license->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title">
                                                                    <i class="fas fa-vial me-2"></i>
                                                                    تفاصيل الاختبارات - أمر عمل #{{ $license->workOrder->order_number ?? 'غير محدد' }}
                                                                </h5>
                                                                <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-striped">
                                                                        <thead class="table-light">
                                                                            <tr>
                                                                                <th>نوع الاختبار</th>
                                                                                <th>الحالة</th>
                                                                                <th>تاريخ الاختبار</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>اختبار العمق</td>
                                                                                <td>
                                                                                    @if(isset($license->has_depth_test))
                                                                                        @if($license->has_depth_test)
                                                                                            <span class="badge bg-success">تم إجراءه</span>
                                                                                        @else
                                                                                            <span class="badge bg-danger">لم يتم إجراءه</span>
                                                                                        @endif
                                                                                    @else
                                                                                        <span class="badge bg-secondary">غير محدد</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{ $license->depth_test_date ? \Carbon\Carbon::parse($license->depth_test_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>اختبار دك التربة</td>
                                                                                <td>
                                                                                    @if(isset($license->has_soil_compaction_test))
                                                                                        @if($license->has_soil_compaction_test)
                                                                                            <span class="badge bg-success">تم إجراءه</span>
                                                                                        @else
                                                                                            <span class="badge bg-danger">لم يتم إجراءه</span>
                                                                                        @endif
                                                                                    @else
                                                                                        <span class="badge bg-secondary">غير محدد</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{ $license->soil_compaction_test_date ? \Carbon\Carbon::parse($license->soil_compaction_test_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>اختبار RC1-MC1</td>
                                                                                <td>
                                                                                    @if(isset($license->has_rc1_mc1_test))
                                                                                        @if($license->has_rc1_mc1_test)
                                                                                            <span class="badge bg-success">تم إجراءه</span>
                                                                                        @else
                                                                                            <span class="badge bg-danger">لم يتم إجراءه</span>
                                                                                        @endif
                                                                                    @else
                                                                                        <span class="badge bg-secondary">غير محدد</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{ $license->rc1_mc1_test_date ? \Carbon\Carbon::parse($license->rc1_mc1_test_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>اختبار الأسفلت</td>
                                                                                <td>
                                                                                    @if(isset($license->has_asphalt_test))
                                                                                        @if($license->has_asphalt_test)
                                                                                            <span class="badge bg-success">تم إجراءه</span>
                                                                                        @else
                                                                                            <span class="badge bg-danger">لم يتم إجراءه</span>
                                                                                        @endif
                                                                                    @else
                                                                                        <span class="badge bg-secondary">غير محدد</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{ $license->asphalt_test_date ? \Carbon\Carbon::parse($license->asphalt_test_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>اختبار التربة</td>
                                                                                <td>
                                                                                    @if(isset($license->has_soil_test))
                                                                                        @if($license->has_soil_test)
                                                                                            <span class="badge bg-success">تم إجراءه</span>
                                                                                        @else
                                                                                            <span class="badge bg-danger">لم يتم إجراءه</span>
                                                                                        @endif
                                                                                    @else
                                                                                        <span class="badge bg-secondary">غير محدد</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{ $license->soil_test_date ? \Carbon\Carbon::parse($license->soil_test_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>اختبار البلاط والانترلوك</td>
                                                                                <td>
                                                                                    @if(isset($license->has_interlock_test))
                                                                                        @if($license->has_interlock_test)
                                                                                            <span class="badge bg-success">تم إجراءه</span>
                                                                                        @else
                                                                                            <span class="badge bg-danger">لم يتم إجراءه</span>
                                                                                        @endif
                                                                                    @else
                                                                                        <span class="badge bg-secondary">غير محدد</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{ $license->interlock_test_date ? \Carbon\Carbon::parse($license->interlock_test_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                
                                                                @if($license->test_results_file_path)
                                                                <div class="mt-3">
                                                                    <h6>ملفات نتائج الاختبارات:</h6>
                                                                    <a href="{{ asset('storage/' . $license->test_results_file_path) }}" target="_blank" class="btn btn-info">
                                                                        <i class="fas fa-file-pdf me-1"></i> عرض نتائج الاختبارات
                                                                    </a>
                                                                </div>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                                @if($license->workOrder)
                                                                <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-primary">
                                                                    <i class="fas fa-edit me-1"></i> تحرير بيانات الاختبارات
                                                                </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge bg-secondary">لا اختبارات</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($license->license_extension_file_path)
                                                <div class="d-flex flex-column">
                                                    <a href="{{ asset('storage/' . $license->license_extension_file_path) }}" target="_blank" class="badge bg-success text-decoration-none mb-1">
                                                        <i class="fas fa-file-alt"></i> عرض الملف
                                                    </a>
                                                    @if($license->license_extension_end_date)
                                                        <small class="text-muted">
                                                            تنتهي: {{ \Carbon\Carbon::parse($license->license_extension_end_date)->format('Y-m-d') }}
                                                        </small>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="badge bg-secondary">غير متوفر</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($license->has_restriction)
                                                <span class="badge bg-danger">نعم</span>
                                            @else
                                                <span class="badge bg-success">لا</span>
                                            @endif
                                        </td>
                                        <td>{{ $license->restriction_authority ?? '-' }}</td>
                                        <td>
                                            @if($license->license_closure_file_path)
                                                <a href="{{ asset('storage/' . $license->license_closure_file_path) }}" class="badge bg-secondary text-decoration-none" target="_blank">
                                                    <i class="fas fa-file-excel"></i> عرض
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">غير متوفر</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($license->workOrder)
                                                    <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i> تعديل الرخصة
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="15" class="text-center py-4">لا توجد بيانات رخص متاحة</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">إحصائيات الرخص</h5>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <h6>العدد الكلي للرخص</h6>
                                        <h3>{{ $licenses->count() }}</h3>
                                    </div>
                                    <div class="bg-light p-3 rounded">
                                        <i class="fas fa-id-card fa-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">الرخص سارية المفعول</h5>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <h6>العدد</h6>
                                        <h3>{{ $licenses->filter(function($license) {
                                            $startDate = $license->license_start_date ? \Carbon\Carbon::parse($license->license_start_date) : null;
                                            $endDate = $license->license_end_date ? \Carbon\Carbon::parse($license->license_end_date) : null;
                                            return $startDate && $endDate && !$startDate->isFuture() && !$endDate->isPast();
                                        })->count() }}</h3>
                                    </div>
                                    <div class="bg-light p-3 rounded">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">الرخص المنتهية</h5>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <h6>العدد</h6>
                                        <h3>{{ $licenses->filter(function($license) {
                                            return $license->license_end_date && \Carbon\Carbon::parse($license->license_end_date)->isPast();
                                        })->count() }}</h3>
                                    </div>
                                    <div class="bg-light p-3 rounded">
                                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // كود إدارة أعمدة الجدول
    const columnToggleBtn = document.getElementById('columnToggleBtn');
    const columnTogglePanel = document.getElementById('columnTogglePanel');
    const columnToggles = document.querySelectorAll('.column-toggle');
    const showAllColumnsBtn = document.getElementById('showAllColumns');
    const hideAllColumnsBtn = document.getElementById('hideAllColumns');
    const resetColumnsBtn = document.getElementById('resetColumns');
    const table = document.getElementById('licensesTable');
    
    // تبديل ظهور لوحة الإعدادات
    columnToggleBtn.addEventListener('click', function() {
        columnTogglePanel.style.display = columnTogglePanel.style.display === 'none' ? 'block' : 'none';
    });
    
    // حفظ حالة الأعمدة في التخزين المحلي
    function saveColumnState() {
        const columnState = {};
        columnToggles.forEach(toggle => {
            columnState[toggle.dataset.column] = toggle.checked;
        });
        localStorage.setItem('licenseTableColumns', JSON.stringify(columnState));
    }
    
    // استعادة حالة الأعمدة من التخزين المحلي
    function loadColumnState() {
        const savedState = localStorage.getItem('licenseTableColumns');
        if (savedState) {
            const columnState = JSON.parse(savedState);
            columnToggles.forEach(toggle => {
                const columnIndex = toggle.dataset.column;
                if (columnState[columnIndex] !== undefined) {
                    toggle.checked = columnState[columnIndex];
                    updateColumnVisibility(columnIndex, columnState[columnIndex]);
                }
            });
        }
    }
    
    // تحديث ظهور العمود
    function updateColumnVisibility(columnIndex, isVisible) {
        const headerCells = table.querySelectorAll('thead th');
        const rows = table.querySelectorAll('tbody tr');
        
        if (headerCells.length > columnIndex) {
            headerCells[columnIndex].style.display = isVisible ? '' : 'none';
        }
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > columnIndex) {
                cells[columnIndex].style.display = isVisible ? '' : 'none';
            }
        });
    }
    
    // مستمع الأحداث لكل زر تبديل
    columnToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const columnIndex = this.dataset.column;
            updateColumnVisibility(columnIndex, this.checked);
            saveColumnState();
        });
    });
    
    // عرض جميع الأعمدة
    showAllColumnsBtn.addEventListener('click', function() {
        columnToggles.forEach(toggle => {
            toggle.checked = true;
            updateColumnVisibility(toggle.dataset.column, true);
        });
        saveColumnState();
    });
    
    // إخفاء جميع الأعمدة
    hideAllColumnsBtn.addEventListener('click', function() {
        columnToggles.forEach(toggle => {
            // لا نخفي العمود الأول (الترقيم) والأخير (الإجراءات)
            if (toggle.dataset.column !== '0' && toggle.dataset.column !== '14') {
                toggle.checked = false;
                updateColumnVisibility(toggle.dataset.column, false);
            }
        });
        saveColumnState();
    });
    
    // إعادة ضبط الأعمدة للحالة الافتراضية
    resetColumnsBtn.addEventListener('click', function() {
        columnToggles.forEach(toggle => {
            toggle.checked = true;
            updateColumnVisibility(toggle.dataset.column, true);
        });
        saveColumnState();
    });
    
    // تحميل حالة الأعمدة عند تحميل الصفحة
    loadColumnState();

    // تجهيز النوافذ المنبثقة إذا كانت Bootstrap تحتاج إلى تهيئة يدوية
    if (typeof bootstrap !== 'undefined') {
        const testModals = document.querySelectorAll('.modal');
        testModals.forEach(modal => {
            new bootstrap.Modal(modal);
        });
    }
});
</script>
@endpush 