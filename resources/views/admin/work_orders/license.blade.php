@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- قسم المسح (نسخة للعرض فقط) -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fs-4">المسوحات</h3>
                    <a href="{{ route('admin.work-orders.survey', $workOrder) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-external-link-alt me-1"></i> الانتقال إلى صفحة المسح
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>إحداثيات البداية</th>
                                    <th>إحداثيات النهاية</th>
                                    <th>المعوقات</th>
                                    <th>الملاحظات</th>
                                    <th>عدد الصور</th>
                                    <th>تاريخ المسح</th>
                                    <th>عرض الصور</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($workOrder->surveys as $index => $survey)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($survey->start_coordinates)
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ $survey->start_coordinates }}" target="_blank" class="text-primary me-2">
                                                        <i class="fas fa-map-marker-alt"></i> عرض الإحداثيات
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-secondary" onclick="copyCoordinates('{{ $survey->start_coordinates }}')">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-muted">غير متوفر</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($survey->end_coordinates)
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ $survey->end_coordinates }}" target="_blank" class="text-primary me-2">
                                                        <i class="fas fa-map-marker-alt"></i> عرض الإحداثيات
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-secondary" onclick="copyCoordinates('{{ $survey->end_coordinates }}')">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-muted">غير متوفر</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($survey->has_obstacles)
                                                <span class="badge bg-danger">نعم</span>
                                            @else
                                                <span class="badge bg-success">لا</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($survey->obstacles_notes)
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $survey->obstacles_notes }}">
                                                    {{ $survey->obstacles_notes }}
                                                </span>
                                            @else
                                                <span class="text-muted">لا توجد ملاحظات</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $survey->files->count() }}
                                            </span>
                                        </td>
                                        <td>{{ $survey->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewImagesModal{{ $survey->id }}">
                                                <i class="fas fa-images"></i> عرض الصور
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal for viewing images -->
                                    <div class="modal fade" id="viewImagesModal{{ $survey->id }}" tabindex="-1" aria-labelledby="viewImagesModalLabel{{ $survey->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="viewImagesModalLabel{{ $survey->id }}">صور الموقع</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        @forelse($survey->files as $file)
                                                            <div class="col-md-4 mb-3">
                                                                <div class="card">
                                                                    <img src="{{ asset('storage/' . $file->file_path) }}" class="card-img-top" alt="{{ $file->original_filename }}">
                                                                    <div class="card-body">
                                                                        <p class="card-text small">{{ $file->original_filename }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="col-12">
                                                                <div class="alert alert-info">
                                                                    لا توجد صور مرفوعة
                                                                </div>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">لا توجد عمليات مسح مسجلة</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- قسم الرخص -->
            <div class="card license-form">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">الرخص - أمر العمل #{{ $workOrder->id }}</h3>
                        <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-right"></i> عودة
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.work-orders.update-license', $workOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- شهادات التنسيق -->
                        <div class="form-section mb-4">
                            <h4 class="section-title">شهادات التنسيق</h4>
                            <div class="file-upload-container">
                                <label for="coordination_certificate" class="form-label">رفع شهادات التنسيق</label>
                                <input type="file" class="form-control" id="coordination_certificate" name="coordination_certificate">
                                @if($workOrder->licenses->first()?->coordination_certificate_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $workOrder->licenses->first()->coordination_certificate_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض الشهادة
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-4">
                            <!-- الحظر -->
                            <div class="col-md-6">
                                <div class="form-section h-100">
                                    <h4 class="section-title">الحظر</h4>
                                    <div class="compact-section">
                                        <label class="form-label d-flex align-items-center mb-2">
                                            <i class="fas fa-ban ml-2 text-danger"></i>
                                            <span>هل يوجد حظر؟</span>
                                        </label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="has_restriction" value="1" id="has_restriction_yes" {{ $workOrder->licenses->first()?->has_restriction ? 'checked' : '' }} onchange="toggleRestrictionDetails()">
                                            <label class="form-check-label" for="has_restriction_yes">نعم</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="has_restriction" value="0" id="has_restriction_no" {{ !$workOrder->licenses->first()?->has_restriction ? 'checked' : '' }} onchange="toggleRestrictionDetails()">
                                            <label class="form-check-label" for="has_restriction_no">لا</label>
                                        </div>
                                        <div class="restriction-details mt-3" id="restrictionDetails" style="display: {{ $workOrder->licenses->first()?->has_restriction ? 'block' : 'none' }};">
                                            <div class="form-group">
                                                <label for="restriction_reason" class="form-label">سبب الحظر</label>
                                                <textarea class="form-control" id="restriction_reason" name="restriction_reason" rows="3" placeholder="أدخل سبب الحظر هنا...">{{ $workOrder->licenses->first()?->restriction_reason }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- الخطابات والتعهدات -->
                            <div class="col-md-6">
                                <div class="form-section h-100">
                                    <h4 class="section-title">الخطابات والتعهدات</h4>
                                    <div class="file-upload-container h-100">
                                        <label for="letters_and_commitments" class="form-label">رفع الخطابات والتعهدات</label>
                                        <input type="file" class="form-control" id="letters_and_commitments" name="letters_and_commitments" accept=".pdf,.jpg,.jpeg,.png">
                                        @if($workOrder->licenses->first()?->letters_and_commitments_path)
                                            <div class="mt-3">
                                                <a href="{{ asset('storage/' . $workOrder->licenses->first()->letters_and_commitments_path) }}" target="_blank" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> عرض المرفق
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- تفعيل الرخصة -->
                        <div class="form-section mb-4">
                            <h4 class="section-title mb-4">تفعيل الرخصة</h4>
                            <div class="license-files-section mb-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="file-upload-container h-100">
                                            <label for="license_1" class="form-label d-flex align-items-center mb-2">
                                                <i class="fas fa-file-alt ml-2 text-primary"></i>
                                                <span>الرخصة</span>
                                            </label>
                                            <input type="file" class="form-control" id="license_1" name="license_1" accept=".pdf,.jpg,.jpeg,.png">
                                            @if($workOrder->licenses->first()?->license_1_path)
                                                <div class="mt-2">
                                                    <a href="{{ asset('storage/' . $workOrder->licenses->first()->license_1_path) }}" target="_blank" class="btn btn-info w-100">
                                                        <i class="fas fa-eye ml-1"></i> عرض المرفق
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group h-100">
                                            <label for="license_length" class="form-label d-flex align-items-center mb-2">
                                                <i class="fas fa-ruler ml-2 text-primary"></i>
                                                <span>طول الرخصة (متر)</span>
                                            </label>
                                            <input type="number" class="form-control" id="license_length" name="license_length" value="{{ $workOrder->licenses->first()?->license_length }}" step="0.01" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="license-dates-section">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <h5 class="card-title mb-4 d-flex align-items-center">
                                            <i class="fas fa-calendar-alt ml-2 text-primary"></i>
                                            <span>تواريخ الرخصة</span>
                                        </h5>
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="license_start_date" class="form-label d-flex align-items-center mb-2">
                                                        <i class="fas fa-calendar-plus ml-2 text-success"></i>
                                                        <span>تاريخ بداية الرخصة</span>
                                                    </label>
                                                    <input type="date" class="form-control" id="license_start_date" name="license_start_date" value="{{ $workOrder->licenses->first()?->license_start_date }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="license_end_date" class="form-label d-flex align-items-center mb-2">
                                                        <i class="fas fa-calendar-minus ml-2 text-danger"></i>
                                                        <span>تاريخ نهاية الرخصة</span>
                                                    </label>
                                                    <input type="date" class="form-control" id="license_end_date" name="license_end_date" value="{{ $workOrder->licenses->first()?->license_end_date }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($workOrder->licenses->first()?->license_end_date)
                                <div class="license-status-section mt-4">
                                    @php
                                        $startDate = \Carbon\Carbon::parse($workOrder->licenses->first()->license_start_date);
                                        $endDate = \Carbon\Carbon::parse($workOrder->licenses->first()->license_end_date);
                                        $daysUntilExpiry = (int)round($endDate->floatDiffInDays(now()));
                                        $isExpired = $endDate->isPast();
                                        $isNearExpiry = !$isExpired && $daysUntilExpiry <= 30;
                                        $totalDuration = (int)round($startDate->floatDiffInDays($endDate));
                                    @endphp
                                    
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            @if($isExpired)
                                                <div class="alert alert-danger d-flex align-items-center p-3">
                                                    <i class="fas fa-exclamation-triangle ml-2"></i>
                                                    <div>
                                                        <strong>الرخصة منتهية!</strong>
                                                        <p class="mb-0">منذ {{ $endDate->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            @elseif($isNearExpiry)
                                                <div class="alert alert-warning d-flex align-items-center p-3">
                                                    <i class="fas fa-clock ml-2"></i>
                                                    <div>
                                                        <strong>تنبيه!</strong>
                                                        <p class="mb-0">الرخصة ستنتهي خلال {{ $daysUntilExpiry }} يوم</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-success d-flex align-items-center p-3">
                                                    <i class="fas fa-check-circle ml-2"></i>
                                                    <div>
                                                        <strong>الرخصة صالحة</strong>
                                                        <p class="mb-0">متبقي {{ $daysUntilExpiry }} يوم</p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <div class="alert alert-info d-flex align-items-center p-3 h-100">
                                                <i class="fas fa-calendar-alt ml-2"></i>
                                                <div>
                                                    <strong>مدة الرخصة</strong>
                                                    <p class="mb-0">{{ $totalDuration }} يوم</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- الاختبارات -->
                        <div class="form-section">
                            <h4 class="section-title">الاختبارات</h4>
                            <div class="tests-container">
                                <!-- اختبار العمق -->
                                <div class="test-item">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-ruler-vertical ml-2 text-primary"></i>
                                            <span class="test-name">اختبار العمق</span>
                                        </div>
                                        <div class="test-options">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_depth_test" value="1" id="has_depth_test_yes" {{ $workOrder->licenses->first()?->has_depth_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_depth_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_depth_test" value="0" id="has_depth_test_no" {{ !$workOrder->licenses->first()?->has_depth_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_depth_test_no">لا</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- اختبار دك التربة -->
                                <div class="test-item">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-compress-alt ml-2 text-primary"></i>
                                            <span class="test-name">اختبار دك التربة</span>
                                        </div>
                                        <div class="test-options">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_soil_compaction_test" value="1" id="has_soil_compaction_test_yes" {{ $workOrder->licenses->first()?->has_soil_compaction_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_soil_compaction_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_soil_compaction_test" value="0" id="has_soil_compaction_test_no" {{ !$workOrder->licenses->first()?->has_soil_compaction_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_soil_compaction_test_no">لا</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- اختبار RC1-MC1 -->
                                <div class="test-item">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-vial ml-2 text-primary"></i>
                                            <span class="test-name">اختبار RC1-MC1</span>
                                        </div>
                                        <div class="test-options">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_rc1_mc1_test" value="1" id="has_rc1_mc1_test_yes" {{ $workOrder->licenses->first()?->has_rc1_mc1_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_rc1_mc1_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_rc1_mc1_test" value="0" id="has_rc1_mc1_test_no" {{ !$workOrder->licenses->first()?->has_rc1_mc1_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_rc1_mc1_test_no">لا</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- اختبار الأسفلت -->
                                <div class="test-item">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-road ml-2 text-primary"></i>
                                            <span class="test-name">اختبار الأسفلت</span>
                                        </div>
                                        <div class="test-options">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_asphalt_test" value="1" id="has_asphalt_test_yes" {{ $workOrder->licenses->first()?->has_asphalt_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_asphalt_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_asphalt_test" value="0" id="has_asphalt_test_no" {{ !$workOrder->licenses->first()?->has_asphalt_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_asphalt_test_no">لا</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- اختبار التربة -->
                                <div class="test-item">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-mountain ml-2 text-primary"></i>
                                            <span class="test-name">اختبار التربة</span>
                                        </div>
                                        <div class="test-options">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_soil_test" value="1" id="has_soil_test_yes" {{ $workOrder->licenses->first()?->has_soil_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_soil_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_soil_test" value="0" id="has_soil_test_no" {{ !$workOrder->licenses->first()?->has_soil_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_soil_test_no">لا</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- اختبار البلاط والانترلوك -->
                                <div class="test-item">
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-th ml-2 text-primary"></i>
                                            <span class="test-name">اختبار البلاط والانترلوك</span>
                                        </div>
                                        <div class="test-options">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_interlock_test" value="1" id="has_interlock_test_yes" {{ $workOrder->licenses->first()?->has_interlock_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_interlock_test_yes">نعم</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="has_interlock_test" value="0" id="has_interlock_test_no" {{ !$workOrder->licenses->first()?->has_interlock_test ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_interlock_test_no">لا</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="multi-section">
                        <!-- تمديد الرخصة -->
                        <div class="form-section">
                            <h4 class="section-title">تمديد الرخصة</h4>
                            <div class="file-upload-container">
                                <label class="form-label">رفع مرفق تمديد الرخصة</label>
                                <input type="file" name="license_extension_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                @if($workOrder->licenses->first()?->license_extension_file_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $workOrder->licenses->first()->license_extension_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض المرفق
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="license_extension_start_date" class="form-label">تاريخ بداية التمديد</label>
                                        <input type="date" name="license_extension_start_date" class="form-control date-input" id="license_extension_start_date" value="{{ $workOrder->licenses->first()?->license_extension_start_date }}" onchange="calculateExtensionDays()">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="license_extension_end_date" class="form-label">تاريخ نهاية التمديد</label>
                                        <input type="date" name="license_extension_end_date" class="form-control date-input" id="license_extension_end_date" value="{{ $workOrder->licenses->first()?->license_extension_end_date }}" onchange="calculateExtensionDays()">
                                    </div>
                                </div>
                            </div>
                            @if($workOrder->licenses->first()?->license_extension_end_date)
                                <div class="mt-2">
                                    @php
                                        $extensionEndDate = \Carbon\Carbon::parse($workOrder->licenses->first()->license_extension_end_date);
                                        $daysUntilExtensionExpiry = (int)round($extensionEndDate->floatDiffInDays(now()));
                                        $isExtensionExpired = $extensionEndDate->isPast();
                                        $isNearExtensionExpiry = !$isExtensionExpired && $daysUntilExtensionExpiry <= 30;
                                    @endphp
                                    @if($isExtensionExpired)
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            تمديد الرخصة منتهي منذ {{ $extensionEndDate->diffForHumans() }}
                                        </div>
                                    @elseif($isNearExtensionExpiry)
                                        <div class="alert alert-warning">
                                            <i class="fas fa-clock"></i>
                                            تمديد الرخصة سينتهي خلال {{ $daysUntilExtensionExpiry }} يوم
                                        </div>
                                    @else
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            تمديد الرخصة صالح لمدة {{ $daysUntilExtensionExpiry }} يوم
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- تمديد الفاتورة -->
                        <div class="form-section">
                            <h4 class="section-title">تمديد الفاتورة</h4>
                            <div class="file-upload-container">
                                <label class="form-label">رفع مرفق تمديد الفاتورة</label>
                                <input type="file" name="invoice_extension_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                @if($workOrder->licenses->first()?->invoice_extension_file_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $workOrder->licenses->first()->invoice_extension_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض المرفق
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="invoice_extension_start_date" class="form-label">تاريخ بداية التمديد</label>
                                        <input type="date" name="invoice_extension_start_date" class="form-control date-input" id="invoice_extension_start_date" value="{{ $workOrder->licenses->first()?->invoice_extension_start_date }}" onchange="calculateInvoiceExtensionDays()">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="invoice_extension_end_date" class="form-label">تاريخ نهاية التمديد</label>
                                        <input type="date" name="invoice_extension_end_date" class="form-control date-input" id="invoice_extension_end_date" value="{{ $workOrder->licenses->first()?->invoice_extension_end_date }}" onchange="calculateInvoiceExtensionDays()">
                                    </div>
                                </div>
                            </div>
                            @if($workOrder->licenses->first()?->invoice_extension_end_date)
                                <div class="mt-2">
                                    @php
                                        $invoiceExtensionEndDate = \Carbon\Carbon::parse($workOrder->licenses->first()->invoice_extension_end_date);
                                        $daysUntilInvoiceExtensionExpiry = (int)round($invoiceExtensionEndDate->floatDiffInDays(now()));
                                        $isInvoiceExtensionExpired = $invoiceExtensionEndDate->isPast();
                                        $isNearInvoiceExtensionExpiry = !$isInvoiceExtensionExpired && $daysUntilInvoiceExtensionExpiry <= 30;
                                    @endphp
                                    @if($isInvoiceExtensionExpired)
                                        <div class="alert alert-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            تمديد الفاتورة منتهي منذ {{ $invoiceExtensionEndDate->diffForHumans() }}
                                        </div>
                                    @elseif($isNearInvoiceExtensionExpiry)
                                        <div class="alert alert-warning">
                                            <i class="fas fa-clock"></i>
                                            تمديد الفاتورة سينتهي خلال {{ $daysUntilInvoiceExtensionExpiry }} يوم
                                        </div>
                                    @else
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            تمديد الفاتورة صالح لمدة {{ $daysUntilInvoiceExtensionExpiry }} يوم
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="multi-section">
                        <!-- نتائج الاختبار -->
                        <div class="form-section">
                            <h4 class="section-title">نتائج الاختبار</h4>
                            <div class="file-upload-container">
                                <label class="form-label">رفع مرفق نتائج الاختبار</label>
                                <input type="file" name="test_results_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                @if($workOrder->licenses->first()?->test_results_file_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $workOrder->licenses->first()->test_results_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض المرفق
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- الفحص النهائي -->
                        <div class="form-section">
                            <h4 class="section-title">الفحص النهائي</h4>
                            <div class="file-upload-container">
                                <label class="form-label">رفع مرفق الفحص النهائي</label>
                                <input type="file" name="final_inspection_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                @if($workOrder->licenses->first()?->final_inspection_file_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $workOrder->licenses->first()->final_inspection_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض المرفق
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- إخلاء وإغلاق الرخصة -->
                        <div class="form-section">
                            <h4 class="section-title">إخلاء وإغلاق الرخصة</h4>
                            <div class="file-upload-container">
                                <label class="form-label">رفع مرفق إخلاء وإغلاق الرخصة</label>
                                <input type="file" name="license_closure_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                @if($workOrder->licenses->first()?->license_closure_file_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $workOrder->licenses->first()->license_closure_file_path) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> عرض المرفق
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="submit-section mt-4">
                        <button type="submit" class="btn btn-primary" onclick="submitAndRedirect(event)">
                            <i class="fas fa-save ml-1"></i> حفظ معلومات الرخصة
                        </button>
                        <a href="{{ route('admin.work-orders.licenses.data') }}" class="btn btn-info ms-2">
                            <i class="fas fa-eye ml-1"></i> عرض الرخص
                        </a>
                    </div>
                </form>

                <!-- عرض قسم البيانات المختصرة للرخصة -->
                <div class="alert alert-info mt-4 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-info-circle me-2"></i>
                        <span>يمكنك عرض جميع بيانات الرخص في صفحة منفصلة</span>
                    </div>
                    <a href="{{ route('admin.work-orders.licenses.data') }}" class="btn btn-primary">
                        <i class="fas fa-table me-1"></i> عرض بيانات الرخص
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyCoordinates(coordinates) {
    navigator.clipboard.writeText(coordinates).then(() => {
        alert('تم نسخ الإحداثيات بنجاح');
    }).catch(err => {
        console.error('فشل نسخ الإحداثيات:', err);
    });
}

function calculateExtensionDays() {
    const startDate = document.getElementById('license_extension_start_date').value;
    const endDate = document.getElementById('license_extension_end_date').value;
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (start > end) {
            alert('تاريخ البداية يجب أن يكون قبل تاريخ النهاية');
            document.getElementById('license_extension_end_date').value = '';
            return;
        }
    }
}

function calculateInvoiceExtensionDays() {
    const startDate = document.getElementById('invoice_extension_start_date').value;
    const endDate = document.getElementById('invoice_extension_end_date').value;
    
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (start > end) {
            alert('تاريخ البداية يجب أن يكون قبل تاريخ النهاية');
            document.getElementById('invoice_extension_end_date').value = '';
            return;
        }
    }
}

function toggleRestrictionDetails() {
    const hasRestriction = document.getElementById('has_restriction_yes').checked;
    const restrictionDetails = document.getElementById('restrictionDetails');
    restrictionDetails.style.display = hasRestriction ? 'block' : 'none';
    
    if (!hasRestriction) {
        document.getElementById('restriction_reason').value = '';
    }
}

// دالة للتحكم في إظهار/إخفاء حقول تواريخ الاختبارات
function toggleTestDates() {
    const selectedTests = Array.from(document.getElementById('tests').selectedOptions).map(option => option.value);
    
    // إخفاء جميع حقول التواريخ أولاً
    document.querySelectorAll('.test-date-input').forEach(container => {
        container.style.display = 'none';
    });
    
    // إظهار حقول التواريخ للاختبارات المحددة
    selectedTests.forEach(test => {
        const container = document.getElementById(`${test}_date_container`);
        if (container) {
            container.style.display = 'block';
        }
    });
}

// إضافة مستمع حدث لتغيير الاختيارات
document.getElementById('tests').addEventListener('change', toggleTestDates);

// حساب الأيام عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    calculateExtensionDays();
    calculateInvoiceExtensionDays();
    toggleRestrictionDetails();
    toggleTestDates();
});

// دالة لحفظ المعلومات والتوجيه إلى صفحة بيانات الرخص
function submitAndRedirect(event) {
    event.preventDefault();
    
    // إظهار مؤشر التحميل
    const submitButton = event.target;
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin ml-1"></i> جاري الحفظ...';
    
    // الحصول على النموذج
    const form = document.querySelector('form');
    const formData = new FormData(form);
    
    // إرسال البيانات
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('حدث خطأ في الاتصال بالخادم');
        }
        // التحقق من نوع المحتوى
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        }
        // إذا لم يكن JSON، نعيد توجيه المستخدم مباشرة
        window.location.href = '{{ route("admin.work-orders.licenses.data") }}';
        return null;
    })
    .then(data => {
        if (data) {
            if (data.success) {
                showAlert('success', 'تم حفظ معلومات الرخصة بنجاح');
                setTimeout(() => {
                    window.location.href = '{{ route("admin.work-orders.licenses.data") }}';
                }, 2000);
            } else {
                throw new Error(data.message || 'حدث خطأ أثناء حفظ المعلومات');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', error.message);
        // إعادة تفعيل الزر
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
}

// دالة مساعدة لعرض رسائل التنبيه
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} ml-1"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(alertDiv);
    
    // إخفاء الرسالة بعد 5 ثواني
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>

<style>
/* الألوان الأساسية المريحة للعين */
:root {
    --primary-color: #4a90e2;
    --secondary-color: #6c757d;
    --success-color: #2ecc71;
    --danger-color: #e74c3c;
    --warning-color: #f1c40f;
    --info-color: #3498db;
    --light-bg: #f8f9fa;
    --dark-bg: #2c3e50;
    --border-color: #e9ecef;
    --text-color: #34495e;
    --text-muted: #7f8c8d;
}

/* تنسيق عام للجداول */
.table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    margin-bottom: 0.75rem;
    background-color: #fff;
    border-radius: 0.375rem;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    font-size: 0.875rem;
}

.table th {
    background-color: var(--light-bg);
    color: var(--text-color);
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    padding: 0.75rem;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
}

.table td {
    padding: 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-muted);
    font-size: 0.875rem;
}

.table tbody tr {
    transition: all 0.15s ease-in-out;
}

.table tbody tr:hover {
    background-color: rgba(74, 144, 226, 0.03);
    transform: translateY(-1px);
}

/* تنسيق البطاقات */
.card {
    border: none;
    border-radius: 0.375rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    transition: all 0.2s ease;
    background-color: #fff;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid var(--border-color);
    padding: 1rem;
}

.card-header h3 {
    color: var(--text-color);
    font-weight: 600;
    margin: 0;
    font-size: 1.1rem;
}

/* تنسيق الأزرار */
.btn {
    padding: 0.375rem 0.75rem;
    font-weight: 500;
    border-radius: 0.25rem;
    transition: all 0.15s ease;
    font-size: 0.875rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: #357abd;
    border-color: #357abd;
}

.btn-light {
    background-color: var(--light-bg);
    border-color: var(--border-color);
    color: var(--text-color);
}

.btn-light:hover {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: var(--dark-bg);
}

/* تنسيق الشارات */
.badge {
    padding: 0.35em 0.65em;
    font-weight: 500;
    border-radius: 0.25rem;
    font-size: 0.75rem;
}

.badge.bg-success {
    background-color: var(--success-color) !important;
}

.badge.bg-danger {
    background-color: var(--danger-color) !important;
}

.badge.bg-info {
    background-color: var(--info-color) !important;
}

/* تنسيق النماذج */
.form-control {
    border-radius: 0.25rem;
    border: 1px solid var(--border-color);
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    transition: all 0.15s ease;
    background-color: #fff;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
}

.form-label {
    font-size: 0.875rem;
    color: var(--text-color);
    margin-bottom: 0.375rem;
    font-weight: 500;
}

/* تنسيق حقول الإدخال الصغيرة */
.form-control-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 0.2rem;
}

/* تنسيق مجموعات النماذج */
.form-group {
    margin-bottom: 1rem;
}

.form-group small {
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* تنسيق مناطق رفع الملفات */
.file-upload-container {
    background-color: var(--light-bg);
    padding: 1rem;
    border-radius: 0.375rem;
    border: 1px dashed var(--border-color);
    transition: all 0.15s ease;
}

.file-upload-container:hover {
    border-color: var(--primary-color);
    background-color: rgba(74, 144, 226, 0.03);
}

/* تنسيق التنبيهات */
.alert {
    border: none;
    border-radius: 0.375rem;
    padding: 0.75rem 1rem;
    margin-bottom: 0.75rem;
    font-size: 0.875rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.alert-success {
    background-color: rgba(46, 204, 113, 0.1);
    color: #27ae60;
}

.alert-danger {
    background-color: rgba(231, 76, 60, 0.1);
    color: #c0392b;
}

.alert-warning {
    background-color: rgba(241, 196, 15, 0.1);
    color: #f39c12;
}

.alert-info {
    background-color: rgba(52, 152, 219, 0.1);
    color: #2980b9;
}

/* تنسيق الأقسام */
.form-section {
    background-color: #fff;
    padding: 1.25rem;
    border-radius: 0.375rem;
    margin-bottom: 1.25rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.section-title {
    color: var(--text-color);
    font-weight: 600;
    margin-bottom: 1.25rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border-color);
    font-size: 1rem;
}

/* تنسيق التواريخ */
.date-time-display {
    font-family: 'Courier New', monospace;
    color: var(--text-muted);
    font-size: 0.875rem;
    background-color: var(--light-bg);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
}

/* تنسيق الأيقونات */
.fas {
    margin-right: 0.375rem;
    color: var(--text-muted);
    font-size: 0.875rem;
}

/* تنسيق التقدم */
.progress {
    height: 0.375rem;
    border-radius: 0.5rem;
    background-color: var(--light-bg);
    margin: 0.375rem 0;
}

.progress-bar {
    border-radius: 0.5rem;
    transition: width 0.4s ease;
}

/* تنسيق متجاوب */
@media (max-width: 768px) {
    .table-responsive {
        border-radius: 0.375rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    
    .table th, .table td {
        padding: 0.5rem;
        font-size: 0.75rem;
    }
    
    .btn-group {
        display: flex;
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin: 1px 0;
    }
    
    .form-section {
        padding: 1rem;
    }
    
    .card-header {
        padding: 0.75rem;
    }
}

/* تنسيق خاص للرخص */
.license-form {
    background-color: #fff;
    border-radius: 0.375rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.submit-section {
    padding: 1.25rem;
    background-color: var(--light-bg);
    border-radius: 0.375rem;
    margin-top: 1.5rem;
    text-align: center;
}

/* تنسيق التلميحات */
[data-bs-toggle="tooltip"] {
    cursor: help;
    border-bottom: 1px dotted var(--text-muted);
}

/* تنسيق التحديد */
::selection {
    background-color: rgba(74, 144, 226, 0.2);
    color: var(--text-color);
}

/* تنسيق النصوص المختصرة */
.text-truncate {
    max-width: 180px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* تنسيق الصور في البطاقات */
.card-img-top {
    height: 160px;
    object-fit: cover;
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
}

/* تنسيق النوافذ المنبثقة */
.modal-content {
    border: none;
    border-radius: 0.375rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.modal-header {
    background-color: var(--light-bg);
    border-bottom: 1px solid var(--border-color);
    padding: 1rem;
}

.modal-body {
    padding: 1.25rem;
}

.modal-xl {
    max-width: 90%;
}

/* تنسيق حقول الاختيار */
.form-check {
    margin-bottom: 0.375rem;
}

.form-check-input {
    width: 0.875rem;
    height: 0.875rem;
    margin-top: 0.25rem;
}

.form-check-label {
    font-size: 0.875rem;
    color: var(--text-color);
    padding-right: 0.25rem;
}

/* تنسيق حقول التاريخ */
input[type="date"] {
    font-family: inherit;
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
    border: 1px solid var(--border-color);
    background-color: #fff;
}

input[type="date"]:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
    outline: none;
}

/* تنسيق حقول الأرقام */
input[type="number"] {
    font-family: inherit;
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
    border: 1px solid var(--border-color);
    background-color: #fff;
}

input[type="number"]:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
    outline: none;
}

/* تنسيق حقول النصوص */
textarea.form-control {
    font-size: 0.875rem;
    min-height: 80px;
    resize: vertical;
}

/* تنسيق العناوين الفرعية */
h4, h5, h6 {
    color: var(--text-color);
    font-weight: 600;
    margin-bottom: 0.75rem;
}

h4 {
    font-size: 1.1rem;
}

h5 {
    font-size: 1rem;
}

h6 {
    font-size: 0.875rem;
}

/* تنسيق قائمة الاختبارات */
.form-select {
    border-radius: 0.375rem;
    border: 1px solid var(--border-color);
    padding: 0.5rem;
    font-size: 0.875rem;
    background-color: #fff;
    transition: all 0.15s ease;
}

.form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
}

.form-select option {
    padding: 0.5rem;
    font-size: 0.875rem;
}

.form-select option:checked {
    background-color: var(--primary-color);
    color: #fff;
}

.form-select option:hover {
    background-color: rgba(74, 144, 226, 0.1);
}

/* تنسيق حقول تواريخ الاختبارات */
.test-date-input {
    transition: all 0.3s ease;
}

.test-date-input .form-group {
    background-color: var(--light-bg);
    padding: 1rem;
    border-radius: 0.375rem;
    border: 1px solid var(--border-color);
}

.test-date-input .form-label {
    color: var(--text-color);
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.test-date-input input[type="date"] {
    width: 100%;
    padding: 0.375rem 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

.test-date-input input[type="date"]:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
    outline: none;
}

/* تنسيق قسم الاختبارات */
.tests-container {
    background-color: #fff;
    border-radius: 0.5rem;
    padding: 1rem;
}

.test-item {
    margin-bottom: 0.5rem;
}

.test-item:last-child {
    margin-bottom: 0;
}

.test-item .bg-light {
    background-color: var(--light-bg) !important;
    border: 1px solid var(--border-color);
    transition: all 0.2s ease;
}

.test-item .bg-light:hover {
    background-color: rgba(74, 144, 226, 0.05) !important;
    border-color: var(--primary-color);
}

.test-name {
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--text-color);
}

.test-options {
    display: flex;
    gap: 1rem;
}

.form-check-inline {
    margin-right: 0;
    margin-left: 1rem;
}

.form-check-input {
    cursor: pointer;
}

.form-check-label {
    cursor: pointer;
    user-select: none;
    font-size: 0.9rem;
    color: var(--text-color);
}

.form-check-input:checked + .form-check-label {
    color: var(--primary-color);
    font-weight: 500;
}

/* تنسيق الأيقونات */
.test-item .fas {
    font-size: 1rem;
    width: 1.5rem;
    text-align: center;
}

/* تنسيق متجاوب */
@media (max-width: 576px) {
    .test-item .d-flex {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .test-options {
        width: 100%;
        justify-content: flex-end;
    }
}

/* تنسيق رسائل التنبيه */
.alert {
    min-width: 300px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.alert-success {
    background-color: rgba(46, 204, 113, 0.95);
    color: #fff;
    border: none;
}

.alert-danger {
    background-color: rgba(231, 76, 60, 0.95);
    color: #fff;
    border: none;
}

.alert .btn-close {
    color: #fff;
    opacity: 0.8;
}

.alert .btn-close:hover {
    opacity: 1;
}

/* تنسيق زر الحفظ */
.submit-section .btn-primary {
    min-width: 200px;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.submit-section .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(74, 144, 226, 0.2);
}

.submit-section .btn-primary:disabled {
    opacity: 0.8;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.submit-section .btn-primary .fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection 