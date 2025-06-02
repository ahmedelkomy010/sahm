@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- عرض رسائل النجاح والخطأ -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 fs-4">
                            <i class="fas fa-database me-2"></i>
                            بيانات إدارة الجودة والرخص
                        </h3>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light btn-sm" onclick="exportToExcel()">
                                <i class="fas fa-file-excel"></i> تصدير Excel
                            </button>
                            <button type="button" class="btn btn-info btn-sm" onclick="printReport()">
                                <i class="fas fa-print"></i> طباعة التقرير
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- فلاتر البحث -->
                    <div class="row p-3 bg-light border-bottom">
                        <div class="col-md-3">
                            <label class="form-label">البحث في جميع الحقول</label>
                            <input type="text" class="form-control" id="searchInput" placeholder="ابحث هنا...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">نوع الرخصة</label>
                            <select class="form-select" id="licenseTypeFilter">
                                <option value="">الكل</option>
                                <option value="مشروع">مشروع</option>
                                <option value="طوارئ">طوارئ</option>
                                <option value="عادي">عادي</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">حالة الحظر</label>
                            <select class="form-select" id="restrictionFilter">
                                <option value="">الكل</option>
                                <option value="1">يوجد حظر</option>
                                <option value="0">لا يوجد حظر</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">حالة الإخلاء</label>
                            <select class="form-select" id="evacuationFilter">
                                <option value="">الكل</option>
                                <option value="1">تم الإخلاء</option>
                                <option value="0">لم يتم الإخلاء</option>
                            </select>
                        </div>
                    </div>

                    <!-- جدول البيانات -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0" id="licensesTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>رقم أمر العمل</th>
                                    <th>رقم الرخصة</th>
                                    <th>نوع الرخصة</th>
                                    <th>تاريخ الرخصة</th>
                                    <th>قيمة الرخصة</th>
                                    <th>قيمة التمديد</th>
                                    <th>حالة الحظر</th>
                                    <th>جهة الحظر</th>
                                    <th>تاريخ البداية</th>
                                    <th>تاريخ النهاية</th>
                                    <th>طول الحفر</th>
                                    <th>عرض الحفر</th>
                                    <th>عمق الحفر</th>
                                    <th>حالة الإخلاء</th>
                                    <th>رقم رخصة الإخلاء</th>
                                    <th>قيمة الإخلاء</th>
                                    <th>الاختبارات</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($licenses as $license)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($license->workOrder)
                                                <a href="{{ route('admin.work-orders.show', $license->workOrder) }}" class="text-primary">
                                                    {{ $license->workOrder->order_number }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $license->license_number ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $license->license_type ?? '-' }}</span>
                                        </td>
                                        <td>{{ $license->license_date ? date('Y-m-d', strtotime($license->license_date)) : '-' }}</td>
                                        <td>{{ number_format($license->license_value ?? 0, 2) }} ريال</td>
                                        <td>{{ number_format($license->extension_value ?? 0, 2) }} ريال</td>
                                        <td>
                                            @if($license->has_restriction)
                                                <span class="badge bg-danger">يوجد حظر</span>
                                            @else
                                                <span class="badge bg-success">لا يوجد حظر</span>
                                            @endif
                                        </td>
                                        <td>{{ $license->restriction_authority ?? '-' }}</td>
                                        <td>{{ $license->license_start_date ? date('Y-m-d', strtotime($license->license_start_date)) : '-' }}</td>
                                        <td>{{ $license->license_end_date ? date('Y-m-d', strtotime($license->license_end_date)) : '-' }}</td>
                                        <td>{{ number_format($license->excavation_length ?? 0, 2) }} م</td>
                                        <td>{{ number_format($license->excavation_width ?? 0, 2) }} م</td>
                                        <td>{{ number_format($license->excavation_depth ?? 0, 2) }} م</td>
                                        <td>
                                            @if($license->is_evacuated)
                                                <span class="badge bg-warning">تم الإخلاء</span>
                                            @else
                                                <span class="badge bg-secondary">لم يتم الإخلاء</span>
                                            @endif
                                        </td>
                                        <td>{{ $license->evac_license_number ?? '-' }}</td>
                                        <td>{{ number_format($license->evac_license_value ?? 0, 2) }} ريال</td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                @if($license->has_depth_test)
                                                    <span class="badge bg-success">اختبار العمق ✓</span>
                                                @endif
                                                @if($license->has_soil_compaction_test)
                                                    <span class="badge bg-success">اختبار دمك التربة ✓</span>
                                                @endif
                                                @if($license->has_rc1_mc1_test)
                                                    <span class="badge bg-success">اختبار RC1/MC1 ✓</span>
                                                @endif
                                                @if($license->has_asphalt_test)
                                                    <span class="badge bg-success">اختبار الأسفلت ✓</span>
                                                @endif
                                                @if($license->has_soil_test)
                                                    <span class="badge bg-success">اختبار التربة ✓</span>
                                                @endif
                                                @if($license->has_interlock_test)
                                                    <span class="badge bg-success">اختبار الانترلوك ✓</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.licenses.show', $license) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.licenses.edit', $license) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($license->workOrder)
                                                    <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" class="btn btn-sm btn-success">
                                                        <i class="fas fa-flask"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="19" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p>لا توجد بيانات رخص مسجلة</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- ملخص الإحصائيات -->
                    <div class="row p-3 bg-light border-top">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center p-3">
                                    <h5 class="mb-1">{{ $licenses->count() }}</h5>
                                    <small>إجمالي الرخص</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center p-3">
                                    <h5 class="mb-1">{{ $licenses->where('has_restriction', true)->count() }}</h5>
                                    <small>الرخص المحظورة</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body text-center p-3">
                                    <h5 class="mb-1">{{ $licenses->where('is_evacuated', true)->count() }}</h5>
                                    <small>الرخص المخلاة</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center p-3">
                                    <h5 class="mb-1">{{ number_format($licenses->sum('license_value'), 2) }}</h5>
                                    <small>إجمالي قيمة الرخص</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $licenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// تفعيل البحث والفلترة
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const licenseTypeFilter = document.getElementById('licenseTypeFilter');
    const restrictionFilter = document.getElementById('restrictionFilter');
    const evacuationFilter = document.getElementById('evacuationFilter');
    const table = document.getElementById('licensesTable');
    const rows = table.getElementsByTagName('tr');

    function filterTable() {
        const searchText = searchInput.value.toLowerCase();
        const licenseType = licenseTypeFilter.value;
        const restriction = restrictionFilter.value;
        const evacuation = evacuationFilter.value;

        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName('td');
            if (cells.length === 0) continue;

            const rowText = row.textContent.toLowerCase();
            const rowLicenseType = cells[3].textContent.trim();
            const rowRestriction = cells[7].textContent.includes('يوجد حظر') ? '1' : '0';
            const rowEvacuation = cells[14].textContent.includes('تم الإخلاء') ? '1' : '0';

            const matchesSearch = searchText === '' || rowText.includes(searchText);
            const matchesType = licenseType === '' || rowLicenseType.includes(licenseType);
            const matchesRestriction = restriction === '' || rowRestriction === restriction;
            const matchesEvacuation = evacuation === '' || rowEvacuation === evacuation;

            row.style.display = 
                matchesSearch && matchesType && matchesRestriction && matchesEvacuation
                ? '' 
                : 'none';
        }
    }

    searchInput.addEventListener('input', filterTable);
    licenseTypeFilter.addEventListener('change', filterTable);
    restrictionFilter.addEventListener('change', filterTable);
    evacuationFilter.addEventListener('change', filterTable);
});

// دالة تصدير البيانات إلى Excel
function exportToExcel() {
    const table = document.getElementById('licensesTable');
    const wb = XLSX.utils.table_to_book(table, { sheet: "بيانات الرخص" });
    XLSX.writeFile(wb, 'بيانات_الرخص_' + new Date().toISOString().slice(0, 10) + '.xlsx');
}

// دالة طباعة التقرير
function printReport() {
    window.print();
}
</script>
@endpush

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #1976D2, #2196F3);
}

.table th {
    white-space: nowrap;
    background-color: #343a40 !important;
}

.badge {
    font-size: 0.8rem;
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}

@media print {
    .btn-group, .card-header .btn, .row.p-3.bg-light {
        display: none !important;
    }
    
    .table {
        font-size: 12px;
    }
    
    .badge {
        border: 1px solid #000;
    }
}
</style>
@endsection 