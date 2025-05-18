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
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <h3 class="mb-0 fs-4 text-center text-md-start">بيانات الرخص</h3>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <a href="{{ route('admin.work-orders.licenses') }}" class="btn btn-back btn-sm">
                                <i class="fas fa-arrow-right"></i> العودة لصفحة الرخص
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- فلتر وبحث -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form action="{{ route('admin.work-orders.licenses.data') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="بحث..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">حالة الرخصة</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>سارية</option>
                                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهية</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="type" class="form-select">
                                        <option value="">نوع الرخصة</option>
                                        @foreach($licenses->pluck('license_type')->unique() as $type)
                                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> بحث
                                    </button>
                                    <a href="{{ route('admin.work-orders.licenses.data') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> إعادة تعيين
                                    </a>
                                </div>
                            </form>
                    </div>
                </div>

                    <!-- جدول الرخص -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>رقم أمر العمل</th>
                                    <th>رقم الرخصة</th>
                                    <th>نوع الرخصة</th>
                                    <th>تاريخ الرخصة</th>
                                    <th>تاريخ البداية</th>
                                    <th>تاريخ النهاية</th>
                                    <th>حالة الرخصة</th>
                                    <th>طول الرخصة</th>
                                    <th>الحظر</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($licenses as $license)
                                    <tr>
                                        <td>{{ $license->workOrder->order_number ?? 'غير محدد' }}</td>
                                        <td>{{ $license->license_number ?? 'غير محدد' }}</td>
                                        <td>{{ $license->license_type ?? 'غير محدد' }}</td>
                                        <td>{{ $license->license_date ? \Carbon\Carbon::parse($license->license_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>{{ $license->license_start_date ? \Carbon\Carbon::parse($license->license_start_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>{{ $license->license_end_date ? \Carbon\Carbon::parse($license->license_end_date)->format('Y-m-d') : 'غير محدد' }}</td>
                                        <td>
                                            @php
                                                $endDate = $license->license_extension_end_date ?? $license->license_end_date;
                                                $daysLeft = \Carbon\Carbon::parse($endDate)->diffInDays(now(), false);
                                                $statusClass = $daysLeft > 0 ? 'danger' : 'success';
                                                $statusText = $daysLeft > 0 ? 'منتهية' : 'سارية';
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                            @if($daysLeft !== null)
                                                <small class="text-muted d-block">
                                                    {{ $daysLeft > 0 ? 'انتهت منذ ' . abs($daysLeft) . ' يوم' : 'متبقي ' . abs($daysLeft) . ' يوم' }}
                                                        </small>
                                            @endif
                                        </td>
                                        <td>{{ $license->license_length ? $license->license_length . ' متر' : 'غير محدد' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $license->has_restriction ? 'danger' : 'success' }}">
                                                {{ $license->has_restriction ? 'نعم' : 'لا' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.licenses.show', $license->id) }}" class="btn btn-info btn-sm" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.licenses.edit', $license->id) }}" class="btn btn-primary btn-sm" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">لا توجد رخص مسجلة</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
            </div>
            
                    <!-- الترقيم -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $licenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* تنسيق الأزرار */
.btn {
    transition: all 0.3s ease;
    border: none;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-back {
    background-color: #795548;
    color: white;
}

.btn-back:hover {
    background-color: #6D4C41;
    color: white;
}

/* تحسين الهيدر */
.bg-gradient-primary {
    background: linear-gradient(45deg, #1976D2, #2196F3);
}

/* تحسين الجداول */
.table {
    margin-bottom: 0;
}

.table th {
    font-weight: 600;
    white-space: nowrap;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

/* تحسين البطاقات */
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    margin-bottom: 1.5rem;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
    background-color: #f8f9fa;
}

.card-header h4 {
    font-weight: 600;
    margin: 0;
}

/* تحسين الشارات */
.badge {
    padding: 0.5em 1em;
    font-weight: 500;
    border-radius: 0.5rem;
}

/* تحسين الأيقونات */
.fas {
    margin-right: 0.5rem;
}

/* تحسين الروابط */
a {
    text-decoration: none;
}

/* تحسين التجاوب */
@media (max-width: 768px) {
    .table-responsive {
        border: 0;
    }
    
    .table th,
    .table td {
        white-space: normal;
    }
    
    .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.875rem;
    }
}
</style>
@endsection 