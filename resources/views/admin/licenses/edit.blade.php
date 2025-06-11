@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-warning text-dark py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 fs-4">
                            <i class="fas fa-edit me-2"></i>
                            تعديل الرخصة - {{ $license->license_number ?? 'غير محدد' }}
                        </h3>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.licenses.show', $license) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye me-1"></i> عرض
                            </a>
                            <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-right me-1"></i> عودة
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('admin.licenses.update', $license) }}" method="POST" enctype="multipart/form-data" id="editLicenseForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- معلومات أساسية -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    المعلومات الأساسية
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label for="license_number" class="form-label">رقم الرخصة</label>
                                <input type="text" class="form-control" id="license_number" name="license_number" 
                                       value="{{ old('license_number', $license->license_number) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="license_date" class="form-label">تاريخ الرخصة</label>
                                <input type="date" class="form-control" id="license_date" name="license_date" 
                                       value="{{ old('license_date', $license->license_date ? $license->license_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="license_type" class="form-label">نوع الرخصة</label>
                                <select class="form-select" id="license_type" name="license_type">
                                    <option value="">اختر نوع الرخصة</option>
                                    <option value="مشروع" {{ old('license_type', $license->license_type) == 'مشروع' ? 'selected' : '' }}>مشروع</option>
                                    <option value="طوارئ" {{ old('license_type', $license->license_type) == 'طوارئ' ? 'selected' : '' }}>طوارئ</option>
                                    <option value="عادي" {{ old('license_type', $license->license_type) == 'عادي' ? 'selected' : '' }}>عادي</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="license_value" class="form-label">قيمة الرخصة</label>
                                <input type="number" step="0.01" class="form-control" id="license_value" name="license_value" 
                                       value="{{ old('license_value', $license->license_value) }}">
                            </div>
                            <div class="col-md-6">
                                <label for="coordination_certificate_number" class="form-label">رقم شهادة التنسيق</label>
                                <input type="text" class="form-control" id="coordination_certificate_number" name="coordination_certificate_number" 
                                       value="{{ old('coordination_certificate_number', $license->coordination_certificate_number) }}"
                                       placeholder="أدخل رقم شهادة التنسيق">
                            </div>
                            <div class="col-md-6">
                                <label for="extension_value" class="form-label">قيمة التمديدات</label>
                                <input type="number" step="0.01" class="form-control" id="extension_value" name="extension_value" 
                                       value="{{ old('extension_value', $license->extension_value) }}">
                            </div>
                        </div>

                        <!-- التواريخ -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-info mb-3">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    التواريخ
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label for="license_start_date" class="form-label">تاريخ بداية الرخصة</label>
                                <input type="date" class="form-control" id="license_start_date" name="license_start_date" 
                                       value="{{ old('license_start_date', $license->license_start_date ? $license->license_start_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="license_end_date" class="form-label">تاريخ نهاية الرخصة</label>
                                <input type="date" class="form-control" id="license_end_date" name="license_end_date" 
                                       value="{{ old('license_end_date', $license->license_end_date ? $license->license_end_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="extension_start_date" class="form-label">تاريخ بداية التمديد</label>
                                <input type="date" class="form-control" id="extension_start_date" name="extension_start_date" 
                                       value="{{ old('extension_start_date', $license->license_extension_start_date ? $license->license_extension_start_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="extension_end_date" class="form-label">تاريخ نهاية التمديد</label>
                                <input type="date" class="form-control" id="extension_end_date" name="extension_end_date" 
                                       value="{{ old('extension_end_date', $license->license_extension_end_date ? $license->license_extension_end_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <!-- أبعاد الحفر -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-warning mb-3">
                                    <i class="fas fa-ruler-combined me-2"></i>
                                    أبعاد الحفر
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">طول الحفر</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" name="excavation_length"
                                           value="{{ old('excavation_length', $license->excavation_length) }}">
                                    <span class="input-group-text">متر</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">عرض الحفر</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" name="excavation_width"
                                           value="{{ old('excavation_width', $license->excavation_width) }}">
                                    <span class="input-group-text">متر</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">عمق الحفر</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" name="excavation_depth"
                                           value="{{ old('excavation_depth', $license->excavation_depth) }}">
                                    <span class="input-group-text">متر</span>
                                </div>
                            </div>
                        </div>

                        <!-- حالة الحظر -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-danger mb-3">
                                    <i class="fas fa-ban me-2"></i>
                                    حالة الحظر
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label for="has_restriction" class="form-label">يوجد حظر؟</label>
                                <select class="form-select" name="has_restriction" id="has_restriction">
                                    <option value="0" {{ old('has_restriction', $license->has_restriction) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_restriction', $license->has_restriction) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="restriction_authority_field" style="{{ $license->has_restriction ? 'display: block;' : 'display: none;' }}">
                                <label for="restriction_authority" class="form-label">جهة الحظر</label>
                                <input type="text" class="form-control" id="restriction_authority" name="restriction_authority" 
                                       value="{{ old('restriction_authority', $license->restriction_authority) }}" 
                                       placeholder="اسم الجهة المسؤولة عن الحظر">
                            </div>
                        </div>

                        <!-- الاختبارات المطلوبة -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-flask me-2"></i>
                                    الاختبارات المطلوبة
                                </h5>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">اختبار العمق</label>
                                <select class="form-select" name="has_depth_test">
                                    <option value="0" {{ old('has_depth_test', $license->has_depth_test) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_depth_test', $license->has_depth_test) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">اختبار التربة</label>
                                <select class="form-select" name="has_soil_test">
                                    <option value="0" {{ old('has_soil_test', $license->has_soil_test) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_soil_test', $license->has_soil_test) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">اختبار الأسفلت</label>
                                <select class="form-select" name="has_asphalt_test">
                                    <option value="0" {{ old('has_asphalt_test', $license->has_asphalt_test) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_asphalt_test', $license->has_asphalt_test) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">اختبار دك التربة</label>
                                <select class="form-select" name="has_soil_compaction_test">
                                    <option value="0" {{ old('has_soil_compaction_test', $license->has_soil_compaction_test) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_soil_compaction_test', $license->has_soil_compaction_test) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">اختبار RC1/MC1</label>
                                <select class="form-select" name="has_rc1_mc1_test">
                                    <option value="0" {{ old('has_rc1_mc1_test', $license->has_rc1_mc1_test) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_rc1_mc1_test', $license->has_rc1_mc1_test) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">اختبار انترلوك</label>
                                <select class="form-select" name="has_interlock_test">
                                    <option value="0" {{ old('has_interlock_test', $license->has_interlock_test) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('has_interlock_test', $license->has_interlock_test) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                        </div>

                        <!-- الإخلاءات -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-warning mb-3">
                                    <i class="fas fa-truck-moving me-2"></i>
                                    معلومات الإخلاءات
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">تم الإخلاء؟</label>
                                <select class="form-select" name="is_evacuated">
                                    <option value="0" {{ old('is_evacuated', $license->is_evacuated) == 0 ? 'selected' : '' }}>لا</option>
                                    <option value="1" {{ old('is_evacuated', $license->is_evacuated) == 1 ? 'selected' : '' }}>نعم</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">رقم رخصة الإخلاء</label>
                                <input type="text" class="form-control" name="evac_license_number"
                                       value="{{ old('evac_license_number', $license->evac_license_number) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">قيمة رخصة الإخلاء</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" name="evac_license_value"
                                           value="{{ old('evac_license_value', $license->evac_license_value) }}">
                                    <span class="input-group-text">ريال</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">رقم سداد الإخلاء</label>
                                <input type="text" class="form-control" name="evac_payment_number"
                                       value="{{ old('evac_payment_number', $license->evac_payment_number) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">تاريخ الإخلاء</label>
                                <input type="date" class="form-control" name="evac_date"
                                       value="{{ old('evac_date', $license->evac_date ? $license->evac_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <!-- جداول الإخلاءات -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-info mb-3">
                                    <i class="fas fa-table me-2"></i>
                                    جداول الإخلاءات
                                </h5>
                            </div>
                            
                            <!-- جدول الفسح ونوع الشارع -->
                            <div class="col-12 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-table me-2"></i>
                                            جدول الفسح ونوع الشارع
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="evacTable1Edit">
                                                <thead class="bg-primary text-white">
                                                    <tr>
                                                        <th rowspan="2" class="align-middle">رقم الفسح</th>
                                                        <th rowspan="2" class="align-middle">تاريخ الفسح</th>
                                                        <th rowspan="2" class="align-middle">طول الفسح</th>
                                                        <th rowspan="2" class="align-middle">طول المختبر</th>
                                                        <th rowspan="2" class="align-middle">نوع الشارع</th>
                                                        <th colspan="3" class="text-center">كمية المواد (متر مكعب)</th>
                                                        <th colspan="3" class="text-center">تدقيق المختبر</th>
                                                        <th rowspan="2" class="align-middle">ملاحظات</th>
                                                        <th rowspan="2" class="align-middle">حذف</th>
                                                    </tr>
                                                    <tr>
                                                        <th>ترابي</th>
                                                        <th>أسفلت</th>
                                                        <th>بلاط</th>
                                                        <th>التربة</th>
                                                        <th>MC1</th>
                                                        <th>أسفلت</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="evacTable1EditBody">
                                                    @php
                                                        $evacTable1Data = $license->evac_table1_data ? json_decode($license->evac_table1_data, true) : [];
                                                    @endphp
                                                    @foreach($evacTable1Data as $index => $row)
                                                    <tr>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table1[{{ $index }}][clearance_number]" value="{{ $row['clearance_number'] ?? '' }}" placeholder="رقم الفسح"></td>
                                                        <td><input type="date" class="form-control form-control-sm" name="evac_table1[{{ $index }}][clearance_date]" value="{{ $row['clearance_date'] ?? '' }}"></td>
                                                        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[{{ $index }}][length]" value="{{ $row['length'] ?? '' }}" placeholder="الطول بالمتر"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table1[{{ $index }}][clearance_lab_number]" value="{{ $row['clearance_lab_number'] ?? '' }}" placeholder="طول المختبر"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table1[{{ $index }}][street_type]" value="{{ $row['street_type'] ?? '' }}" placeholder="نوع الشارع"></td>
                                                        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[{{ $index }}][dirt_quantity]" value="{{ $row['dirt_quantity'] ?? '' }}" placeholder="كمية ترابي"></td>
                                                        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[{{ $index }}][asphalt_quantity]" value="{{ $row['asphalt_quantity'] ?? '' }}" placeholder="كمية أسفلت"></td>
                                                        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[{{ $index }}][tile_quantity]" value="{{ $row['tile_quantity'] ?? '' }}" placeholder="كمية بلاط"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table1[{{ $index }}][soil_check]" value="{{ $row['soil_check'] ?? '' }}" placeholder="تدقيق التربة"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table1[{{ $index }}][mc1_check]" value="{{ $row['mc1_check'] ?? '' }}" placeholder="تدقيق MC1"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table1[{{ $index }}][asphalt_check]" value="{{ $row['asphalt_check'] ?? '' }}" placeholder="تدقيق أسفلت"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table1[{{ $index }}][notes]" value="{{ $row['notes'] ?? '' }}" placeholder="ملاحظات"></td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeRowEdit(this)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm mt-2" onclick="addRowToEvacTable1Edit()">
                                            <i class="fas fa-plus"></i> إضافة صف جديد
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- جدول التفاصيل الفنية -->
                            <div class="col-12 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-cogs me-2"></i>
                                            جدول التفاصيل الفنية للمختبر
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="evacTable2Edit">
                                                <thead class="bg-dark text-white">
                                                    <tr>
                                                        <th>السنة</th>
                                                        <th>نوع العمل</th>
                                                        <th>العمق</th>
                                                        <th>دك التربة</th>
                                                        <th>MC1-RC2</th>
                                                        <th>دك أسفلت</th>
                                                        <th>ترابي</th>
                                                        <th>الكثافة القصوى للأسفلت</th>
                                                        <th>نسبة الأسفلت / التدرج الحبيبي</th>
                                                        <th>تجربة مارشال</th>
                                                        <th>تقييم البلاط / البرودة</th>
                                                        <th>تصنيف التربة</th>
                                                        <th>تجربة بروكتور</th>
                                                        <th>الخرسانة</th>
                                                        <th>ملاحظات</th>
                                                        <th>حذف</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="evacTable2EditBody">
                                                    @php
                                                        $evacTable2Data = $license->evac_table2_data ? json_decode($license->evac_table2_data, true) : [];
                                                    @endphp
                                                    @foreach($evacTable2Data as $index => $row)
                                                    <tr>
                                                        <td><input type="number" class="form-control form-control-sm" name="evac_table2[{{ $index }}][year]" value="{{ $row['year'] ?? date('Y') }}"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table2[{{ $index }}][work_type]" value="{{ $row['work_type'] ?? '' }}" placeholder="نوع العمل"></td>
                                                        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[{{ $index }}][depth]" value="{{ $row['depth'] ?? '' }}" placeholder="العمق"></td>
                                                        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[{{ $index }}][soil_compaction]" value="{{ is_numeric($row['soil_compaction'] ?? '') ? $row['soil_compaction'] : '' }}" placeholder="دك التربة"></td>
                                                        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[{{ $index }}][mc1rc2]" value="{{ is_numeric($row['mc1rc2'] ?? '') ? $row['mc1rc2'] : '' }}" placeholder="MC1-RC2"></td>
                                                        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[{{ $index }}][asphalt_compaction]" value="{{ is_numeric($row['asphalt_compaction'] ?? '') ? $row['asphalt_compaction'] : '' }}" placeholder="دك أسفلت"></td>
                                                        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[{{ $index }}][is_dirt]" value="{{ is_numeric($row['is_dirt'] ?? '') ? $row['is_dirt'] : '' }}" placeholder="ترابي"></td>
                                                        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[{{ $index }}][max_asphalt_density]" value="{{ $row['max_asphalt_density'] ?? '' }}" placeholder="الكثافة القصوى"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table2[{{ $index }}][asphalt_percentage_gradient]" value="{{ ($row['asphalt_percentage'] ?? '') . (isset($row['granular_gradient']) ? ' / ' . $row['granular_gradient'] : '') }}" placeholder="نسبة الأسفلت / التدرج"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table2[{{ $index }}][marshall_test]" value="{{ $row['marshall_test'] ?? '' }}" placeholder="تجربة مارشال"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table2[{{ $index }}][tile_evaluation_coldness]" value="{{ ($row['tile_evaluation'] ?? '') . (isset($row['coldness']) ? ' / ' . $row['coldness'] : '') }}" placeholder="تقييم البلاط / البرودة"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table2[{{ $index }}][soil_classification]" value="{{ $row['soil_classification'] ?? '' }}" placeholder="تصنيف التربة"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table2[{{ $index }}][proctor_test]" value="{{ $row['proctor_test'] ?? '' }}" placeholder="تجربة بروكتور"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table2[{{ $index }}][concrete]" value="{{ $row['concrete'] ?? '' }}" placeholder="الخرسانة"></td>
                                                        <td><input type="text" class="form-control form-control-sm" name="evac_table2[{{ $index }}][notes]" value="{{ $row['notes'] ?? '' }}" placeholder="ملاحظات"></td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeRowEdit(this)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm mt-2" onclick="addRowToEvacTable2Edit()">
                                            <i class="fas fa-plus"></i> إضافة صف جديد
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- المخالفات -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-danger mb-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    معلومات المخالفات
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">رقم المخالفة</label>
                                <input type="text" class="form-control" name="violation_number"
                                       value="{{ old('violation_number', $license->violation_number) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">قيمة رخصة المخالفة</label>
                                <input type="number" step="0.01" class="form-control" name="violation_license_value"
                                       value="{{ old('violation_license_value', $license->violation_license_value) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">تاريخ رخصة المخالفة</label>
                                <input type="date" class="form-control" name="violation_license_date"
                                       value="{{ old('violation_license_date', $license->violation_license_date ? $license->violation_license_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">آخر موعد سداد المخالفة</label>
                                <input type="date" class="form-control" name="violation_due_date"
                                       value="{{ old('violation_due_date', $license->violation_due_date ? $license->violation_due_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">مسبب المخالفة</label>
                                <input type="text" class="form-control" name="violation_cause"
                                       value="{{ old('violation_cause', $license->violation_cause) }}">
                            </div>
                        </div>

                        <!-- الملاحظات -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-secondary mb-3">
                                    <i class="fas fa-sticky-note me-2"></i>
                                    الملاحظات
                                </h5>
                            </div>
                            <div class="col-12">
                                <label class="form-label">ملاحظات</label>
                                <textarea class="form-control" name="notes" rows="4" 
                                          placeholder="أدخل أي ملاحظات إضافية هنا...">{{ old('notes', $license->notes) }}</textarea>
                            </div>
                        </div>

                        <!-- أزرار الحفظ والإلغاء -->
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success btn-lg px-5 me-3">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ التعديلات
                                </button>
                                <a href="{{ route('admin.licenses.show', $license) }}" class="btn btn-secondary btn-lg px-5">
                                    <i class="fas fa-times me-2"></i>
                                    إلغاء
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // إظهار/إخفاء حقول الحظر
    const hasRestrictionSelect = document.getElementById('has_restriction');
    const restrictionAuthorityField = document.getElementById('restriction_authority_field');
    
    function toggleRestrictionFields() {
        if (hasRestrictionSelect && restrictionAuthorityField) {
            if (hasRestrictionSelect.value == '1') {
                restrictionAuthorityField.style.display = 'block';
            } else {
                restrictionAuthorityField.style.display = 'none';
            }
        }
    }
    
    if (hasRestrictionSelect) {
        hasRestrictionSelect.addEventListener('change', toggleRestrictionFields);
        toggleRestrictionFields(); // تشغيل عند التحميل
    }
});

// وظائف إدارة جداول الإخلاءات في صفحة التعديل
function addRowToEvacTable1Edit() {
    const tbody = document.getElementById('evacTable1EditBody');
    const rowCount = tbody.rows.length;
    const row = document.createElement('tr');
    
    row.innerHTML = `
        <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][clearance_number]" placeholder="رقم الفسح"></td>
        <td><input type="date" class="form-control form-control-sm" name="evac_table1[${rowCount}][clearance_date]"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][length]" placeholder="الطول بالمتر"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][clearance_lab_number]" placeholder="طول المختبر"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][street_type]" placeholder="نوع الشارع"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][dirt_quantity]" placeholder="كمية ترابي"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][asphalt_quantity]" placeholder="كمية أسفلت"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table1[${rowCount}][tile_quantity]" placeholder="كمية بلاط"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][soil_check]" placeholder="تدقيق التربة"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][mc1_check]" placeholder="تدقيق MC1"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][asphalt_check]" placeholder="تدقيق أسفلت"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table1[${rowCount}][notes]" placeholder="ملاحظات"></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRowEdit(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
}

function addRowToEvacTable2Edit() {
    const tbody = document.getElementById('evacTable2EditBody');
    const rowCount = tbody.rows.length;
    const row = document.createElement('tr');
    
    row.innerHTML = `
        <td><input type="number" class="form-control form-control-sm" name="evac_table2[${rowCount}][year]" value="${new Date().getFullYear()}"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][work_type]" placeholder="نوع العمل"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][depth]" placeholder="العمق"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][soil_compaction]" placeholder="دك التربة"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][mc1rc2]" placeholder="MC1-RC2"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][asphalt_compaction]" placeholder="دك أسفلت"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][is_dirt]" placeholder="ترابي"></td>
        <td><input type="number" step="0.01" class="form-control form-control-sm" name="evac_table2[${rowCount}][max_asphalt_density]" placeholder="الكثافة القصوى"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][asphalt_percentage_gradient]" placeholder="نسبة الأسفلت / التدرج"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][marshall_test]" placeholder="تجربة مارشال"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][tile_evaluation_coldness]" placeholder="تقييم البلاط / البرودة"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][soil_classification]" placeholder="تصنيف التربة"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][proctor_test]" placeholder="تجربة بروكتور"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][concrete]" placeholder="الخرسانة"></td>
        <td><input type="text" class="form-control form-control-sm" name="evac_table2[${rowCount}][notes]" placeholder="ملاحظات"></td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRowEdit(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
}

function removeRowEdit(button) {
    button.closest('tr').remove();
}
</script>
@endpush

<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #FF9800, #FFB74D, #FFCC02);
}

.card {
    border-radius: 1rem;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
}

h5 {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 0.5rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #2196F3;
    box-shadow: 0 0 0 0.2rem rgba(33, 150, 243, 0.25);
}

.btn {
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
</style> 