@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fs-4">صفحة التنفيذ</h3>
                    <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right"></i> عودة
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- قسم الأعمال المدنية -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-hard-hat fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">الأعمال المدنية</h4>
                                    <p class="card-text text-muted mb-4">إدارة وتوثيق الأعمال المدنية للمشروع</p>
                                    <a href="{{ route('admin.work-orders.civil-works', $workOrder) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-arrow-left ml-1"></i>
                                        الانتقال إلى الأعمال المدنية
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- قسم التركيبات -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-tools fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">التركيبات</h4>
                                    <p class="card-text text-muted mb-4">إدارة وتوثيق أعمال التركيبات للمشروع</p>
                                    <a href="{{ route('admin.work-orders.installations', $workOrder) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-arrow-left ml-1"></i>
                                        الانتقال إلى التركيبات
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- قسم أعمال التمديد والكهرباء -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-bolt fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">أعمال التمديد والكهرباء</h4>
                                    <p class="card-text text-muted mb-4">إدارة وتوثيق أعمال التمديد والكهرباء للمشروع</p>
                                    <a href="{{ route('admin.work-orders.electrical-works', $workOrder) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-arrow-left ml-1"></i>
                                        الانتقال إلى أعمال التمديد والكهرباء
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($logs) && $logs->count())
    <div class="card mt-4">
        <div class="card-header bg-info text-white">سجل جميع العمليات المدخلة</div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>القسم</th>
                        <th>البيانات المدخلة</th>
                        <th>تاريخ التسجيل</th>
                        <th style="width: 10%">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $fieldNames = [
                        // التركيبات
                        'single_meter_box' => 'صندوق عداد مفرد',
                        'double_meter_box_single' => 'صندوق عداد مزدوج (عداد واحد)',
                        'double_meter_box_double' => 'صندوق عداد مزدوج (عدادين)',
                        'quad_meter_box_triple' => 'صندوق عداد ثلاثي',
                        'quad_meter_box_quad' => 'صندوق عداد رباعي',
                        'ct_meter_box' => 'صندوق CT',
                        'mini_pillar_base' => 'قاعدة ميني بلر',
                        'mini_pillar_head' => 'رأس ميني بلر',
                        'mini_pillar_ml' => 'ميني بلر ML',
                        'ring_base_triple' => 'قاعدة رنج ثلاثي',
                        'ring_base_quad' => 'قاعدة رنج رباعي',
                        'cole_base_500' => 'قاعدة كول 500',
                        'cole_base_1000' => 'قاعدة كول 1000',
                        'cole_base_1500' => 'قاعدة كول 1500',
                        'pole_10m' => 'عمود 10 متر',
                        'pole_13m' => 'عمود 13 متر',
                        'pole_14m' => 'عمود 14 متر',
                        // الأعمال المدنية
                        'excavation_unsurfaced_soil' => 'حفريات تربة ترابية غير مسفلتة',
                        'excavation_surfaced_soil' => 'حفريات تربة ترابية مسفلتة',
                        'excavation_unsurfaced_rock' => 'حفريات تربة صخرية غير مسفلتة',
                        'excavation_surfaced_rock' => 'حفريات تربة صخرية مسفلتة',
                        'open_excavation' => 'حفر مفتوح',
                        'excavation_precise' => 'حفريات دقيقة',
                        // الكهرباء
                        'al_500_joint' => 'وصلة 3 × 500 ألمونيوم 13.8kv',
                        'al_500_end' => 'نهاية 3 × 500 ألمونيوم 13.8kv',
                        'al_70_end' => 'نهاية 4 × 70 ألمونيوم 1kv',
                        'al_185_end' => 'نهاية 4 × 185 ألمونيوم 1kv',
                        'al_300_end' => 'نهاية 4 × 300 ألمونيوم 1kv',
                        'al_33kv_joint' => 'وصلة 33kv',
                        'al_33kv_end' => 'نهاية 33kv',
                        'bi_metalk_joint' => 'وصلة باي ميتالك 13,8 kv',
                        'bi_metalk_1kv_joint' => 'وصلة باي ميتالك 1 kv',
                        'low_4x70_wire' => 'لفرات 4x70 ألمونيوم 1kv',
                        'cu_1x50_end' => 'نهاية 1x50 نحاس 13.8 kv',
                        'al_3x70_end' => 'نهاية 3x70 ألمونيوم 13.8 kv',
                        'tariff_meter' => 'تأريفي عداد',
                        'tariff_mini_pillar' => 'تأريفي ميني بلر',
                        'tariff_switch' => 'تأريفي قاطع',
                        'tariff_transformer' => 'تأريفي محول',
                        'joint_33kv' => 'وصلة 33kv',
                        'end_33kv' => 'نهاية 33kv',
                        'bi_metallic_joint_13_8kv' => 'وصلة باي ميتالك 13.8kv',
                        'bi_metallic_joint_1kv' => 'وصلة باي ميتالك 1kv',
                        'lugs_4x70_1kv' => 'لفرات 4x70 ألمونيوم 1kv',
                        'end_1x50_cu_13_8kv' => 'نهاية 1x50 نحاس 13.8kv',
                        'end_3x70_al_13_8kv' => 'نهاية 3x70 ألمونيوم 13.8kv',
                        'tension_4x120_al_low' => 'شد أسلاك 4x120 ألمونيوم منخفض',
                        'tension_1x70_al_33kv' => 'شد أسلاك 1x70 ألمونيوم 33kv',
                        'tension_3x70_al_33kv' => 'شد أسلاك 3x70 ألمونيوم 33kv',
                        'tension_6x170_al_33kv' => 'شد أسلاك 6x170 ألمونيوم 33kv',
                        'cable_4x70_low' => 'تمديد كابل 4x70 منخفض',
                        'cable_4x185_low' => 'تمديد كابل 4x185 منخفض',
                    ];

                    $subFieldNames = [
                        'medium' => 'متوسط',
                        'low' => 'منخفض',
                        'sand_under' => 'تحت الرمل',
                        'sand_over' => 'فوق الرمل',
                        'first_sibz' => 'سبز أول',
                        'second_sibz' => 'سبز ثاني',
                        'concrete' => 'خرسانة',
                        'first_asphalt' => 'أسفلت طبقة أولى',
                        'asphalt_scraping' => 'كشط وإعادة سفلتة',
                        'kerb_strip' => 'شريط كزيري',
                        'protection_tile' => 'بلاط حماية',
                        // أضف أي حقول فرعية أخرى هنا حسب الحاجة
                    ];
                    @endphp
                    @foreach($logs as $log)
                    <tr>
                        <td>
                            @if($log->section == 'civil')
                                الأعمال المدنية
                            @elseif($log->section == 'installations')
                                التركيبات
                            @elseif($log->section == 'electrical')
                                أعمال التمديد والكهرباء
                            @else
                                {{ $log->section }}
                            @endif
                        </td>
                        <td>
                            @php $data = json_decode($log->data, true); @endphp
                            <ul style="list-style: disc; padding-right: 18px;">
                                @if($log->section == 'electrical' && isset($data['electrical_items']))
                                    @foreach($data['electrical_items'] as $key => $item)
                                        @php
                                            $name = $fieldNames[$key] ?? $key;
                                            $values = [];
                                            foreach($item as $k => $v) {
                                                if(!empty($v) && $v !== '0' && $v !== 'no' && $v !== 'na') {
                                                    $values[] = ($k == 'status' ? 'الحالة: ' : ($k == 'quantity' ? 'العدد: ' : ($k == 'meters' ? 'الأمتار: ' : $k . ': '))) .
                                                                ($v == 'yes' ? 'نعم' : ($v == 'no' ? 'لا' : ($v == 'na' ? 'لا ينطبق' : $v)));
                                                }
                                            }
                                        @endphp
                                        @if(count($values))
                                            <li>
                                                <strong>{{ $name }}:</strong>
                                                <ul style="list-style: circle; padding-right: 18px;">
                                                    @foreach($values as $val)
                                                        <li>{{ $val }}</li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endif
                                    @endforeach
                                @elseif($log->section == 'installations' && isset($data['installations']))
                                    @foreach($data['installations'] as $key => $item)
                                        @php
                                            $name = $fieldNames[$key] ?? $key;
                                            $values = [];
                                            foreach($item as $k => $v) {
                                                if(!empty($v) && $v !== '0' && $v !== 'no' && $v !== 'na') {
                                                    $values[] = ($k == 'status' ? 'الحالة: ' : ($k == 'quantity' ? 'العدد: ' : $k . ': ')) .
                                                                ($v == 'yes' ? 'نعم' : ($v == 'no' ? 'لا' : ($v == 'na' ? 'لا ينطبق' : $v)));
                                                }
                                            }
                                        @endphp
                                        @if(count($values))
                                            <li>
                                                <strong>{{ $name }}:</strong>
                                                <ul style="list-style: circle; padding-right: 18px;">
                                                    @foreach($values as $val)
                                                        <li>{{ $val }}</li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endif
                                    @endforeach
                                @else
                                    @foreach($data as $key => $value)
                                        @if(isset($fieldNames[$key]) && !empty($value) && $key !== 'token' && $key !== '_method')
                                            <li>
                                                <strong>{{ $fieldNames[$key] }}:</strong>
                                                @if(is_array($value))
                                                    <ul style="list-style: circle; padding-right: 18px;">
                                                        @foreach($value as $subKey => $subValue)
                                                            @php
                                                                $allEmpty = true;
                                                                if(is_array($subValue)) {
                                                                    foreach($subValue as $v) {
                                                                        if(!empty($v) && $v !== '0' && $v !== null && $v !== 'no' && $v !== 'na') {
                                                                            $allEmpty = false;
                                                                            break;
                                                                        }
                                                                    }
                                                                } else {
                                                                    $allEmpty = empty($subValue) || $subValue === '0' || $subValue === null || $subValue === 'no' || $subValue === 'na';
                                                                }
                                                            @endphp
                                                            @if(!$allEmpty)
                                                                <li>
                                                                    <strong>{{ $subFieldNames[$subKey] ?? $subKey }}@if(in_array($subKey, ['kerb_strip', 'protection_tile'])) <span class="text-info">(بتخصيص الطول والعدد)</span>@endif:</strong>
                                                                    @if(is_array($subValue))
                                                                        <ul style="list-style: circle; padding-right: 18px;">
                                                                            @if(in_array($subKey, ['kerb_strip', 'protection_tile']))
                                                                                @if(!empty($subValue['quantity']))
                                                                                    <li>العدد: {{ $subValue['quantity'] }}</li>
                                                                                @endif
                                                                                @if(!empty($subValue['length']))
                                                                                    <li>الطول: {{ $subValue['length'] }}</li>
                                                                                @endif
                                                                            @elseif(in_array($subKey, ['first_asphalt', 'asphalt_scraping']))
                                                                                @foreach($subValue as $k => $v)
                                                                                    @if(!empty($v) && $v !== '0' && $v !== null && $v !== 'no' && $v !== 'na')
                                                                                        <li>
                                                                                            {{ __($k == 'length' ? 'الطول' : ($k == 'width' ? 'العرض' : $k)) }}
                                                                                            : {{ $v == 'yes' ? 'نعم' : ($v == 'no' ? 'لا' : ($v == 'na' ? 'لا ينطبق' : $v) ) }}
                                                                                        </li>
                                                                                    @endif
                                                                                @endforeach
                                                                                @php
                                                                                    $hasBoth = isset($subValue['length'], $subValue['width']) &&
                                                                                              is_numeric($subValue['length']) && is_numeric($subValue['width']);
                                                                                @endphp
                                                                                @if($hasBoth)
                                                                                    <li>
                                                                                        <strong>الإجمالي (م²):</strong>
                                                                                        {{ number_format($subValue['length'] * $subValue['width'], 2) }}
                                                                                    </li>
                                                                                @endif
                                                                            @else
                                                                                @foreach($subValue as $k => $v)
                                                                                    @if(!empty($v) && $v !== '0' && $v !== null && $v !== 'no' && $v !== 'na')
                                                                                        <li>
                                                                                            {{ __($k == 'length' ? 'الطول' : ($k == 'width' ? 'العرض' : ($k == 'depth' ? 'العمق' : ($k == 'quantity' ? 'العدد' : ($k == 'status' ? 'الحالة' : ($k == 'meters' ? 'الأمتار' : $k)))))) }}
                                                                                            : {{ $v == 'yes' ? 'نعم' : ($v == 'no' ? 'لا' : ($v == 'na' ? 'لا ينطبق' : $v) ) }}
                                                                                        </li>
                                                                                    @endif
                                                                                @endforeach
                                                                                @php
                                                                                    $hasAll = isset($subValue['length'], $subValue['width'], $subValue['depth']) &&
                                                                                              is_numeric($subValue['length']) && is_numeric($subValue['width']) && is_numeric($subValue['depth']);
                                                                                @endphp
                                                                                @if($hasAll)
                                                                                    <li>
                                                                                        <strong>الإجمالي (م³):</strong>
                                                                                        {{ number_format($subValue['length'] * $subValue['width'] * $subValue['depth'], 2) }}
                                                                                    </li>
                                                                                @endif
                                                                            @endif
                                                                        </ul>
                                                                    @else
                                                                        {{ $subValue == 'yes' ? 'نعم' : ($subValue == 'no' ? 'لا' : ($subValue == 'na' ? 'لا ينطبق' : $subValue) ) }}
                                                                    @endif
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    {{ $value == 'yes' ? 'نعم' : ($value == 'no' ? 'لا' : ($value == 'na' ? 'لا ينطبق' : $value)) }}
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </td>
                        <td>{{ date('Y-m-d H:i', strtotime($log->created_at)) }}</td>
                        <td class="text-center">
                            <form action="{{ route('admin.work-orders.delete-log', $log->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
                                    <i class="fas fa-trash-alt"></i> حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

.icon-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(74, 144, 226, 0.1);
    border-radius: 50%;
}

.card-title {
    color: var(--text-color);
    font-weight: 600;
    font-size: 1.25rem;
}

.btn-primary {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(74, 144, 226, 0.2);
}

@media (max-width: 768px) {
    .col-md-4 {
        margin-bottom: 1rem;
    }
}
</style>
@endsection 