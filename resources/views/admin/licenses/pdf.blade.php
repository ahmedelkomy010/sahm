<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الرخصة - {{ $license->license_number ?? 'غير محدد' }}</title>
    <style>
        * {
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }
        
        body {
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            direction: rtl;
            text-align: right;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        
        .header h2 {
            color: #6c757d;
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }
        
        .section {
            margin-bottom: 25px;
            break-inside: avoid;
        }
        
        .section-title {
            background-color: #f8f9fa;
            border-right: 4px solid #007bff;
            padding: 10px 15px;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 14px;
            color: #007bff;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-cell {
            display: table-cell;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            vertical-align: top;
            width: 25%;
        }
        
        .info-label {
            font-weight: bold;
            color: #495057;
            background-color: #f8f9fa;
        }
        
        .info-value {
            color: #212529;
        }
        
        .dates-section {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .date-card {
            float: right;
            width: 47%;
            margin: 0 1.5%;
            border: 2px solid #dee2e6;
            padding: 15px;
            background-color: #fff;
        }
        
        .date-card.original {
            border-color: #007bff;
        }
        
        .date-card.extension {
            border-color: #ffc107;
        }
        
        .date-card-header {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .date-card.original .date-card-header {
            color: #007bff;
        }
        
        .date-card.extension .date-card-header {
            color: #856404;
        }
        
        .date-item {
            margin-bottom: 8px;
        }
        
        .date-label {
            font-weight: bold;
            color: #495057;
            font-size: 11px;
        }
        
        .date-value {
            font-size: 12px;
            color: #212529;
        }
        
        .date-value.start {
            color: #28a745;
            font-weight: bold;
        }
        
        .date-value.end {
            color: #dc3545;
            font-weight: bold;
        }
        
        .no-extension {
            text-align: center;
            color: #6c757d;
            font-style: italic;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            color: white;
        }
        
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-info { background-color: #17a2b8; }
        .badge-secondary { background-color: #6c757d; }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }
        
        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 6px 8px;
            text-align: center;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>تفاصيل الرخصة</h1>
        <h2>رقم الرخصة: {{ $license->license_number ?? 'غير محدد' }}</h2>
    </div>

    <!-- معلومات أساسية -->
    <div class="section">
        <div class="section-title">المعلومات الأساسية</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">أمر العمل</div>
                <div class="info-cell info-value">{{ $license->workOrder->order_number ?? 'غير محدد' }}</div>
                <div class="info-cell info-label">رقم الرخصة</div>
                <div class="info-cell info-value">{{ $license->license_number ?? 'غير محدد' }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">تاريخ الرخصة</div>
                <div class="info-cell info-value">{{ $license->license_date ? $license->license_date->format('Y-m-d') : 'غير محدد' }}</div>
                <div class="info-cell info-label">نوع الرخصة</div>
                <div class="info-cell info-value">{{ $license->license_type ?? 'غير محدد' }}</div>
            </div>
        </div>
    </div>

    <!-- القيم المالية -->
    <div class="section">
        <div class="section-title">القيم المالية</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">قيمة الرخصة</div>
                <div class="info-cell info-value">{{ $license->license_value ? number_format($license->license_value, 2) . ' ر.س' : 'غير محدد' }}</div>
                <div class="info-cell info-label">قيمة التمديدات</div>
                <div class="info-cell info-value">{{ $license->extension_value ? number_format($license->extension_value, 2) . ' ر.س' : 'غير محدد' }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">إجمالي القيمة</div>
                <div class="info-cell info-value">{{ ($license->license_value + $license->extension_value) ? number_format($license->license_value + $license->extension_value, 2) . ' ر.س' : 'غير محدد' }}</div>
                <div class="info-cell info-label"></div>
                <div class="info-cell info-value"></div>
            </div>
        </div>
    </div>

    <!-- التواريخ المهمة -->
    <div class="section">
        <div class="section-title">التواريخ المهمة</div>
        <div class="dates-section clearfix">
            <!-- الرخصة الأصلية -->
            <div class="date-card original">
                <div class="date-card-header">الرخصة الأصلية</div>
                <div class="date-item">
                    <div class="date-label">تاريخ البداية:</div>
                    <div class="date-value start">{{ $license->license_start_date ? $license->license_start_date->format('Y-m-d') : 'غير محدد' }}</div>
                </div>
                <div class="date-item">
                    <div class="date-label">تاريخ النهاية:</div>
                    <div class="date-value end">{{ $license->license_end_date ? $license->license_end_date->format('Y-m-d') : 'غير محدد' }}</div>
                </div>
            </div>
            
            <!-- فترة التمديد -->
            <div class="date-card extension">
                <div class="date-card-header">فترة التمديد</div>
                @if($license->license_extension_start_date || $license->license_extension_end_date)
                    <div class="date-item">
                        <div class="date-label">تاريخ بداية التمديد:</div>
                        <div class="date-value start">{{ $license->license_extension_start_date ? $license->license_extension_start_date->format('Y-m-d') : 'غير محدد' }}</div>
                    </div>
                    <div class="date-item">
                        <div class="date-label">تاريخ نهاية التمديد:</div>
                        <div class="date-value end">{{ $license->license_extension_end_date ? $license->license_extension_end_date->format('Y-m-d') : 'غير محدد' }}</div>
                    </div>
                @else
                    <div class="no-extension">لا يوجد تمديد لهذه الرخصة</div>
                @endif
            </div>
        </div>
    </div>

    <!-- أبعاد الحفر -->
    <div class="section">
        <div class="section-title">أبعاد الحفر</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">الطول</div>
                <div class="info-cell info-value">{{ $license->excavation_length ?? 'غير محدد' }} متر</div>
                <div class="info-cell info-label">العرض</div>
                <div class="info-cell info-value">{{ $license->excavation_width ?? 'غير محدد' }} متر</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">العمق</div>
                <div class="info-cell info-value">{{ $license->excavation_depth ?? 'غير محدد' }} متر</div>
                <div class="info-cell info-label"></div>
                <div class="info-cell info-value"></div>
            </div>
        </div>
    </div>

    <!-- حالة الحظر -->
    <div class="section">
        <div class="section-title">حالة الحظر</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">يوجد حظر</div>
                <div class="info-cell info-value">
                    <span class="badge {{ $license->has_restriction ? 'badge-danger' : 'badge-success' }}">
                        {{ $license->has_restriction ? 'نعم' : 'لا' }}
                    </span>
                </div>
                @if($license->has_restriction)
                <div class="info-cell info-label">جهة الحظر</div>
                <div class="info-cell info-value">{{ $license->restriction_authority ?? 'غير محدد' }}</div>
                @else
                <div class="info-cell info-label"></div>
                <div class="info-cell info-value"></div>
                @endif
            </div>
        </div>
    </div>

    <!-- الاختبارات المطلوبة -->
    <div class="section">
        <div class="section-title">الاختبارات المطلوبة</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">اختبار العمق</div>
                <div class="info-cell info-value">
                    <span class="badge {{ $license->has_depth_test ? 'badge-success' : 'badge-secondary' }}">
                        {{ $license->has_depth_test ? 'مطلوب' : 'غير مطلوب' }}
                    </span>
                </div>
                <div class="info-cell info-label">اختبار التربة</div>
                <div class="info-cell info-value">
                    <span class="badge {{ $license->has_soil_test ? 'badge-success' : 'badge-secondary' }}">
                        {{ $license->has_soil_test ? 'مطلوب' : 'غير مطلوب' }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">اختبار الأسفلت</div>
                <div class="info-cell info-value">
                    <span class="badge {{ $license->has_asphalt_test ? 'badge-success' : 'badge-secondary' }}">
                        {{ $license->has_asphalt_test ? 'مطلوب' : 'غير مطلوب' }}
                    </span>
                </div>
                <div class="info-cell info-label">اختبار دك التربة</div>
                <div class="info-cell info-value">
                    <span class="badge {{ $license->has_soil_compaction_test ? 'badge-success' : 'badge-secondary' }}">
                        {{ $license->has_soil_compaction_test ? 'مطلوب' : 'غير مطلوب' }}
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">اختبار RC1/MC1</div>
                <div class="info-cell info-value">
                    <span class="badge {{ $license->has_rc1_mc1_test ? 'badge-success' : 'badge-secondary' }}">
                        {{ $license->has_rc1_mc1_test ? 'مطلوب' : 'غير مطلوب' }}
                    </span>
                </div>
                <div class="info-cell info-label">اختبار انترلوك</div>
                <div class="info-cell info-value">
                    <span class="badge {{ $license->has_interlock_test ? 'badge-success' : 'badge-secondary' }}">
                        {{ $license->has_interlock_test ? 'مطلوب' : 'غير مطلوب' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات الإخلاءات -->
    @if($license->is_evacuated)
    <div class="section">
        <div class="section-title">معلومات الإخلاءات</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">حالة الإخلاء</div>
                <div class="info-cell info-value">
                    <span class="badge badge-warning">تم الإخلاء</span>
                </div>
                <div class="info-cell info-label">رقم رخصة الإخلاء</div>
                <div class="info-cell info-value">{{ $license->evac_license_number ?? 'غير محدد' }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">قيمة رخصة الإخلاء</div>
                <div class="info-cell info-value">{{ $license->evac_license_value ? number_format($license->evac_license_value, 2) . ' ر.س' : 'غير محدد' }}</div>
                <div class="info-cell info-label">رقم سداد الإخلاء</div>
                <div class="info-cell info-value">{{ $license->evac_payment_number ?? 'غير محدد' }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">تاريخ الإخلاء</div>
                <div class="info-cell info-value">{{ $license->evac_date ? $license->evac_date->format('Y-m-d') : 'غير محدد' }}</div>
                <div class="info-cell info-label">مبلغ الإخلاء</div>
                <div class="info-cell info-value">{{ $license->evac_amount ? number_format($license->evac_amount, 2) . ' ر.س' : 'غير محدد' }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- معلومات المخالفات -->
    @if($license->violation_number || $license->violation_license_number)
    <div class="section">
        <div class="section-title">معلومات المخالفات</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">رقم رخصة المخالفة</div>
                <div class="info-cell info-value">{{ $license->violation_license_number ?? 'غير محدد' }}</div>
                <div class="info-cell info-label">قيمة رخصة المخالفة</div>
                <div class="info-cell info-value">{{ $license->violation_license_value ? number_format($license->violation_license_value, 2) . ' ر.س' : 'غير محدد' }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">تاريخ رخصة المخالفة</div>
                <div class="info-cell info-value">{{ $license->violation_license_date ? $license->violation_license_date->format('Y-m-d') : 'غير محدد' }}</div>
                <div class="info-cell info-label">رقم المخالفة</div>
                <div class="info-cell info-value">{{ $license->violation_number ?? 'غير محدد' }}</div>
            </div>
            <div class="info-row">
                <div class="info-cell info-label">رقم سداد المخالفة</div>
                <div class="info-cell info-value">{{ $license->violation_payment_number ?? 'غير محدد' }}</div>
                <div class="info-cell info-label">آخر موعد سداد</div>
                <div class="info-cell info-value">{{ $license->violation_due_date ? $license->violation_due_date->format('Y-m-d') : 'غير محدد' }}</div>
            </div>
        </div>
        @if($license->violation_cause)
        <div style="margin-top: 15px;">
            <div class="info-label" style="display: block; margin-bottom: 5px;">مسبب المخالفة:</div>
            <div style="background-color: #f8f9fa; padding: 10px; border: 1px solid #dee2e6; border-radius: 4px;">
                {{ $license->violation_cause }}
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- الملاحظات -->
    @if($license->notes)
    <div class="section">
        <div class="section-title">الملاحظات</div>
        <div style="background-color: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; border-radius: 4px;">
            {{ $license->notes }}
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>تم إنشاء هذا التقرير في: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>نظام إدارة الرخص - جميع الحقوق محفوظة</p>
    </div>
</body>
</html>
