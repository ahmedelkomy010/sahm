@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
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
                            <button type="button" class="btn btn-light btn-sm" onclick="exportAllData()">
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
                            <input type="text" class="form-control" id="globalSearch" placeholder="ابحث في أي حقل...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">فلتر حسب نوع الرخصة</label>
                            <select class="form-select" id="licenseTypeFilter">
                                <option value="">جميع الأنواع</option>
                                <option value="مشروع">مشروع</option>
                                <option value="طوارئ">طوارئ</option>
                                <option value="عادي">عادي</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">فلتر حسب حالة الحظر</label>
                            <select class="form-select" id="restrictionFilter">
                                <option value="">جميع الحالات</option>
                                <option value="1">يوجد حظر</option>
                                <option value="0">لا يوجد حظر</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">فلتر حسب حالة الإخلاء</label>
                            <select class="form-select" id="evacuationFilter">
                                <option value="">جميع الحالات</option>
                                <option value="1">تم الإخلاء</option>
                                <option value="0">لم يتم الإخلاء</option>
                            </select>
                        </div>
                    </div>

                    <!-- الجدول الرئيسي -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0" id="licensesDataTable">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th style="min-width: 80px;">#</th>
                                    <th style="min-width: 150px;">أمر العمل</th>
                                    <th style="min-width: 120px;">رقم الرخصة</th>
                                    <th style="min-width: 100px;">تاريخ الرخصة</th>
                                    <th style="min-width: 100px;">نوع الرخصة</th>
                                    <th style="min-width: 120px;">قيمة الرخصة</th>
                                    <th style="min-width: 120px;">قيمة التمديدات</th>
                                    <th style="min-width: 100px;">حالة الحظر</th>
                                    <th style="min-width: 150px;">جهة الحظر</th>
                                    <th style="min-width: 100px;">تاريخ البداية</th>
                                    <th style="min-width: 100px;">تاريخ النهاية</th>
                                    <th style="min-width: 80px;">المدة</th>
                                    <th style="min-width: 100px;">حالة الإخلاء</th>
                                    <th style="min-width: 120px;">رقم رخصة الإخلاء</th>
                                    <th style="min-width: 120px;">قيمة الإخلاء</th>
                                    <th style="min-width: 100px;">تاريخ الإخلاء</th>
                                    <th style="min-width: 150px;">رقم رخصة المخالفة</th>
                                    <th style="min-width: 120px;">قيمة المخالفة</th>
                                    <th style="min-width: 100px;">تاريخ المخالفة</th>
                                    <th style="min-width: 120px;">موعد سداد المخالفة</th>
                                    <th style="min-width: 200px;">ملاحظات</th>
                                    <th style="min-width: 100px;">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($licenses as $index => $license)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($license->workOrder)
                                            <a href="{{ route('admin.work-orders.show', $license->workOrder) }}" 
                                               class="text-decoration-none" target="_blank">
                                                {{ $license->workOrder->order_number ?? 'غير محدد' }}
                                            </a>
                                        @else
                                            <span class="text-muted">غير مرتبط</span>
                                        @endif
                                    </td>
                                    <td>{{ $license->license_number ?? '-' }}</td>
                                    <td>{{ $license->license_date ? $license->license_date->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        @if($license->license_type)
                                            <span class="badge bg-info">{{ $license->license_type }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($license->license_value ?? 0, 2) }}</td>
                                    <td>{{ number_format($license->extension_value ?? 0, 2) }}</td>
                                    <td>
                                        @if($license->has_restriction)
                                            <span class="badge bg-danger">يوجد حظر</span>
                                        @else
                                            <span class="badge bg-success">لا يوجد حظر</span>
                                        @endif
                                    </td>
                                    <td>{{ $license->restriction_authority ?? '-' }}</td>
                                    <td>{{ $license->license_start_date ? $license->license_start_date->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $license->license_end_date ? $license->license_end_date->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        @if($license->license_start_date && $license->license_end_date)
                                            @php
                                                $days = $license->license_start_date->diffInDays($license->license_end_date);
                                            @endphp
                                            <span class="badge bg-primary">{{ $days }} يوم</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($license->is_evacuated)
                                            <span class="badge bg-warning">تم الإخلاء</span>
                                        @else
                                            <span class="badge bg-secondary">لم يتم الإخلاء</span>
                                        @endif
                                    </td>
                                    <td>{{ $license->evac_license_number ?? '-' }}</td>
                                    <td>{{ number_format($license->evac_license_value ?? 0, 2) }}</td>
                                    <td>{{ $license->evac_date ? $license->evac_date->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $license->violation_license_number ?? '-' }}</td>
                                    <td>{{ number_format($license->violation_license_value ?? 0, 2) }}</td>
                                    <td>{{ $license->violation_license_date ? $license->violation_license_date->format('Y-m-d') : '-' }}</td>
                                    <td>{{ $license->violation_due_date ? $license->violation_due_date->format('Y-m-d') : '-' }}</td>
                                    <td>
                                        @if($license->notes)
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                  title="{{ $license->notes }}">
                                                {{ Str::limit($license->notes, 50) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.licenses.show', $license) }}" 
                                               class="btn btn-sm btn-outline-info" title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.licenses.edit', $license) }}" 
                                               class="btn btn-sm btn-outline-primary" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($license->workOrder)
                                                <a href="{{ route('admin.work-orders.license', $license->workOrder) }}" 
                                                   class="btn btn-sm btn-outline-success" title="إدارة الجودة">
                                                    <i class="fas fa-flask"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="22" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p>لا توجد بيانات رخص حالياً</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- إحصائيات سريعة -->
                    <div class="row p-3 bg-light border-top">
                        <div class="col-md-3">
                            <div class="card border-0 bg-primary text-white">
                                <div class="card-body p-3 text-center">
                                    <h5 class="mb-1">{{ $licenses->count() }}</h5>
                                    <small>إجمالي الرخص</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-danger text-white">
                                <div class="card-body p-3 text-center">
                                    <h5 class="mb-1">{{ $licenses->where('has_restriction', true)->count() }}</h5>
                                    <small>رخص محظورة</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-warning text-white">
                                <div class="card-body p-3 text-center">
                                    <h5 class="mb-1">{{ $licenses->where('is_evacuated', true)->count() }}</h5>
                                    <small>رخص مخلاة</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-success text-white">
                                <div class="card-body p-3 text-center">
                                    <h5 class="mb-1">{{ number_format($licenses->sum('license_value'), 0) }}</h5>
                                    <small>إجمالي قيمة الرخص</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تفعيل فلاتر البحث
    const globalSearch = document.getElementById('globalSearch');
    const licenseTypeFilter = document.getElementById('licenseTypeFilter');
    const restrictionFilter = document.getElementById('restrictionFilter');
    const evacuationFilter = document.getElementById('evacuationFilter');
    const table = document.getElementById('licensesDataTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    function filterTable() {
        const globalTerm = globalSearch.value.toLowerCase();
        const licenseType = licenseTypeFilter.value;
        const restriction = restrictionFilter.value;
        const evacuation = evacuationFilter.value;

        rows.forEach(row => {
            if (row.children.length === 1) return; // تجاهل صف "لا توجد بيانات"

            const text = row.textContent.toLowerCase();
            const rowLicenseType = row.children[4].textContent.trim();
            const rowRestriction = row.children[7].textContent.includes('يوجد حظر') ? '1' : '0';
            const rowEvacuation = row.children[12].textContent.includes('تم الإخلاء') ? '1' : '0';

            const matchesGlobal = globalTerm === '' || text.includes(globalTerm);
            const matchesType = licenseType === '' || rowLicenseType.includes(licenseType);
            const matchesRestriction = restriction === '' || rowRestriction === restriction;
            const matchesEvacuation = evacuation === '' || rowEvacuation === evacuation;

            if (matchesGlobal && matchesType && matchesRestriction && matchesEvacuation) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    globalSearch.addEventListener('input', filterTable);
    licenseTypeFilter.addEventListener('change', filterTable);
    restrictionFilter.addEventListener('change', filterTable);
    evacuationFilter.addEventListener('change', filterTable);
});

// تصدير البيانات إلى Excel
function exportAllData() {
    const table = document.getElementById('licensesDataTable');
    const rows = Array.from(table.querySelectorAll('tr'));
    const csvData = [];

    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const cols = Array.from(row.querySelectorAll('th, td'));
            const rowData = cols.slice(0, -1).map(col => {
                // تنظيف النص من الأيقونات والعناصر غير المرغوبة
                let text = col.textContent.trim();
                text = text.replace(/\s+/g, ' ');
                return `"${text}"`;
            });
            csvData.push(rowData.join(','));
        }
    });

    const csv = csvData.join('\n');
    const blob = new Blob(['\ufeff' + csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'بيانات_الجودة_والرخص_' + new Date().toISOString().slice(0, 10) + '.csv';
    link.click();
}

// طباعة التقرير
function printReport() {
    const printContent = document.querySelector('.card').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div style="direction: rtl; font-family: Arial, sans-serif;">
            <h2 style="text-align: center; margin-bottom: 30px;">تقرير بيانات إدارة الجودة والرخص</h2>
            <p style="text-align: center; margin-bottom: 30px;">تاريخ التقرير: ${new Date().toLocaleDateString('ar-SA')}</p>
            ${printContent}
        </div>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}
</script>
@endpush

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #1976D2, #2196F3, #42A5F5);
}

.table {
    font-size: 0.875rem;
}

.table th {
    background: linear-gradient(45deg, #212529, #495057) !important;
    color: white !important;
    border: none;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    white-space: nowrap;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    transition: all 0.3s ease;
}

.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}

.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    margin: 0 1px;
}

.card {
    border-radius: 1rem;
    overflow: hidden;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
}

@media print {
    .btn, .form-control, .form-select, .card-header .d-flex > div:last-child {
        display: none !important;
    }
    
    .card {
        box-shadow: none !important;
        border: 1px solid #ccc !important;
    }
    
    .table {
        font-size: 10px !important;
    }
    
    .table th, .table td {
        padding: 4px !important;
        border: 1px solid #000 !important;
    }
}

@media (max-width: 768px) {
    .table {
        font-size: 0.75rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.375rem;
        font-size: 0.75rem;
    }
}
</style>
@endsection 