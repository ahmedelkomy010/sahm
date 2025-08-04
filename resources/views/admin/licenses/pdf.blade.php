<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل رخصة الحفر - {{ $license->license_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 20px;
            direction: rtl;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .section {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 10px;
            margin: -15px -15px 15px -15px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            color: #007bff;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px dashed #eee;
            padding-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            min-width: 150px;
        }
        .info-value {
            color: #333;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            color: white;
        }
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
        .badge-warning { background-color: #ffc107; color: #333; }
        .badge-info { background-color: #17a2b8; }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تفاصيل رخصة الحفر</h1>
        <p>رقم الرخصة: {{ $license->license_number }}</p>
        <p>أمر العمل: {{ $license->workOrder->order_number ?? 'غير محدد' }}</p>
    </div>

    <!-- المعلومات الأساسية -->
    <div class="section">
        <div class="section-title">المعلومات الأساسية</div>
        <div class="info-row">
            <span class="info-label">نوع الرخصة:</span>
            <span class="info-value">
                @switch($license->license_type)
                    @case('emergency')
                        طوارئ
                        @break
                    @case('project')
                        مشروع
                        @break
                    @case('normal')
                        عادي
                        @break
                    @default
                        غير محدد
                @endswitch
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">قيمة الرخصة:</span>
            <span class="info-value">{{ number_format($license->license_value ?? 0, 2) }} ريال</span>
        </div>
        <div class="info-row">
            <span class="info-label">حالة الرخصة:</span>
            <span class="info-value">نشطة</span>
        </div>
    </div>

    <!-- تواريخ الرخصة -->
    <div class="section">
        <div class="section-title">تواريخ الرخصة</div>
        <div class="info-row">
            <span class="info-label">تاريخ الإصدار:</span>
            <span class="info-value">{{ $license->license_date ? $license->license_date->format('Y/m/d') : 'غير محدد' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">تاريخ التفعيل:</span>
            <span class="info-value">{{ $license->license_start_date ? $license->license_start_date->format('Y/m/d') : 'غير محدد' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">تاريخ الانتهاء:</span>
            <span class="info-value">{{ $license->license_end_date ? $license->license_end_date->format('Y/m/d') : 'غير محدد' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">مدة الرخصة:</span>
            <span class="info-value">
                @if($license->license_start_date && $license->license_end_date)
                    {{ $license->license_start_date->diffInDays($license->license_end_date) }} يوم
                @else
                    غير محدد
                @endif
            </span>
        </div>
    </div>

    <!-- أبعاد الحفر -->
    <div class="section">
        <div class="section-title">أبعاد الحفر</div>
        <div class="info-row">
            <span class="info-label">الطول:</span>
            <span class="info-value">{{ number_format($license->excavation_length ?? 0, 2) }} متر</span>
        </div>
        <div class="info-row">
            <span class="info-label">العرض:</span>
            <span class="info-value">{{ number_format($license->excavation_width ?? 0, 2) }} متر</span>
        </div>
        <div class="info-row">
            <span class="info-label">العمق:</span>
            <span class="info-value">{{ number_format($license->excavation_depth ?? 0, 2) }} متر</span>
        </div>
        <div class="info-row">
            <span class="info-label">الحجم الإجمالي:</span>
            <span class="info-value">
                {{ number_format(($license->excavation_length ?? 0) * ($license->excavation_width ?? 0) * ($license->excavation_depth ?? 0), 2) }} متر مكعب
            </span>
        </div>
    </div>

    <!-- شهادة التنسيق -->
    <div class="section">
        <div class="section-title">شهادة التنسيق</div>
        <div class="info-row">
            <span class="info-label">رقم شهادة التنسيق:</span>
            <span class="info-value">{{ $license->coordination_certificate_number ?? 'غير محدد' }}</span>
        </div>
    </div>

    <!-- معلومات الحظر -->
    <div class="section">
        <div class="section-title">معلومات الحظر</div>
        @if($license->has_restriction)
            <div class="info-row">
                <span class="info-label">حالة الحظر:</span>
                <span class="info-value badge badge-danger">يوجد حظر</span>
            </div>
            @if($license->restriction_authority)
                <div class="info-row">
                    <span class="info-label">جهة الحظر:</span>
                    <span class="info-value">{{ $license->restriction_authority }}</span>
                </div>
            @endif
            @if($license->restriction_reason)
                <div class="info-row">
                    <span class="info-label">سبب الحظر:</span>
                    <span class="info-value">{{ $license->restriction_reason }}</span>
                </div>
            @endif
        @else
            <div class="info-row">
                <span class="info-label">حالة الحظر:</span>
                <span class="info-value badge badge-success">لا يوجد حظر</span>
            </div>
        @endif
    </div>

    <!-- إحصائيات سريعة -->
    <div class="section">
        <div class="section-title">إحصائيات سريعة</div>
        <div class="info-row">
            <span class="info-label">عدد التمديدات:</span>
            <span class="info-value">{{ $license->extensions?->count() ?? 0 }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">عدد المخالفات:</span>
            <span class="info-value">{{ $license->violations?->count() ?? 0 }}</span>
        </div>
    </div>

    <div class="footer">
        <p>تم إنشاء هذا التقرير في: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>شركة سهم بلدي للمقاولات</p>
    </div>
</body>
</html>