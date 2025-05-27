@extends('layouts.app')

@section('title', 'المواد')
@section('header', 'جدول المواد')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- القسم الأيمن - نموذج إضافة المواد -->
        <div class="col-md-6">
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-gradient-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-plus-circle ml-2"></i>
                        إضافة مادة جديدة
                    </h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <form action="{{ route('admin.work-orders.materials.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        
                        <!-- معلومات أمر العمل -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">معلومات أمر العمل</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="work_order_id" class="form-label fw-bold">أمر العمل</label>
                                    <select name="work_order_id" id="work_order_id" class="form-select form-select-lg">
                                        <option value=""> أمر العمل</option>
                                        @foreach($workOrders as $workOrder)
                                            <option value="{{ $workOrder->id }}">{{ $workOrder->order_number }} - {{ $workOrder->subscriber_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">يرجى اختيار أمر العمل</div>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات المادة الأساسية -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">معلومات المادة الأساسية</h5>
                            </div>
                            
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="line" class="form-label fw-bold">السطر</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                            <input type="text" class="form-control" id="line" name="line">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="work_type" class="form-label fw-bold"> كود المادة </label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input id="work_type_number" type="number" min="1" max="999" class="form-control mb-2" placeholder="أدخل رقم المادة">
                                        </div>
                                        <div class="col-md-8">
                                            <select id="work_type" class="form-select @error('work_type') is-invalid @enderror" name="work_type">
                                                <option value="">وصف المادة</option>
                                                <option value="908112050" {{ old('work_type') == '908112050' ? 'selected' : '' }}>- CABLE,PWR,600V/1KV,QUADRUPLEX,3X50+1X50</option>
                                                <option value="908112120" {{ old('work_type') == '908112120' ? 'selected' : '' }}>- CABLE,PWR,600V/1KV,QUADRUPLEX,3X120MM2</option>
                                                <option value="908101001" {{ old('work_type') == '908101001' ? 'selected' : '' }}>- COND,BR,ACSR,QUAIL (2/0 AWG),6AL,1ALWLD </option>
                                                <option value="908101002" {{ old('work_type') == '908101002' ? 'selected' : '' }}>- COND,BR,ACSR,MERLIN(336.4MCM)18AL,170MM2</option>
                                                <option value="908111101" {{ old('work_type') == '908111101' ? 'selected' : '' }}>- CNDCTR,BR,CU,35SQMM,7STR,SOFT DRWN</option>
                                                <option value="908111102" {{ old('work_type') == '908111102' ? 'selected' : '' }}>- CNDCTR,BR,CU,70SQMM,19STR,SOFT DRWN </option>
                                                <option value="908113005" {{ old('work_type') == '908113005' ? 'selected' : '' }}>- CABLE,PWR,15KV,CU,1C,50/16MM2,XLPLE,UARM </option>
                                                <option value="908113001" {{ old('work_type') == '908113001' ? 'selected' : '' }}>- CABLE,PWR,15KV,CU,3C,185/35MM2,XPLE,UARM </option>
                                                <option value="908113002" {{ old('work_type') == '908113002' ? 'selected' : '' }}>- CABLE,PWR,15KV,CU,3C,300/35SQMM,XPLE, </option>
                                                <option value="908114004" {{ old('work_type') == '908114004' ? 'selected' : '' }}>- CABLE,PWR,15KV,AL,3X500/35MM2,XLPE/LLDPE </option>
                                                <option value="908113007" {{ old('work_type') == '908113007' ? 'selected' : '' }}>- CABLE,PWR,36KV,CU,3C,240/35MM2,XPLE,ARM </option>
                                                <option value="908111001" {{ old('work_type') == '908111001' ? 'selected' : '' }}>- CABLE,PWR,600V/1KV,CU,1C,35MM2,XLPE </option>
                                                <option value="908111002" {{ old('work_type') == '908111002' ? 'selected' : '' }}>- CABLE,PWR,600V/1KV,CU,1C,120MM2,XLPE</option>
                                                <option value="908111004" {{ old('work_type') == '908111004' ? 'selected' : '' }}>- CABLE,PWR,600V/1KV,CU,1C,630MM2,XLPE </option>
                                                <option value="908111005" {{ old('work_type') == '908111005' ? 'selected' : '' }}>- CABLE,PWR,600V/1KV,AL,4C,70MM2,XLPE </option>
                                                <option value="110430980" {{ old('work_type') == '110430980' ? 'selected' : '' }}>- CABLE:4X95MM2,ALUM,XLPE/SWA/PVC,LV AS</option>
                                                <option value="908111006" {{ old('work_type') == '908111006' ? 'selected' : '' }}>- CABLE,PWR,600V/1KV,AL,4C,185MM2,XLPE </option>
                                                <option value="908111007" {{ old('work_type') == '908111007' ? 'selected' : '' }}>- CABLE,PWR,600V/1KV,AL,4C,300MM2,XLPE </option>
                                                <option value="908111014" {{ old('work_type') == '908111014' ? 'selected' : '' }}>- CABLE, PWR, 1KV, AL, 1C, 800/2.6 MM2, XPLE, PVC-ST2</option>
                                                <option value="908122054" {{ old('work_type') == '908122054' ? 'selected' : '' }}>- CONN, ELEC, TERM, 15KV, 800 SQM, AL, 4HPAD</option>
                                                <option value="110435040" {{ old('work_type') == '110435040' ? 'selected' : '' }}>- CABLE,PWR,600V/1KV,AL,4C,500MM2,XLPE</option>
                                                <option value="908121004" {{ old('work_type') == '908121004' ? 'selected' : '' }}>- JOINT KIT,STR,15KV,3X185/35MM2,CU,UAR </option>
                                                <option value="908121005" {{ old('work_type') == '908121005' ? 'selected' : '' }}>- JOINT KIT,STR,15KV,3X300/35MM2,CU,AR</option>
                                                <option value="908121016" {{ old('work_type') == '908121016' ? 'selected' : '' }}>- JOINT KIT,TRANS,15KV,3X185/35MM2,CU </option>
                                                <option value="908121155" {{ old('work_type') == '908121155' ? 'selected' : '' }}>- JOINT KIT,TRANS,HS,15KV,185-500/35MM2,BM </option>
                                                <option value="908121156" {{ old('work_type') == '908121156' ? 'selected' : '' }}>- JOINT KIT,TRANS,PM,15KV,185-500/35MM2,BM </option>
                                                <option value="908121149" {{ old('work_type') == '908121149' ? 'selected' : '' }}>- JOINT KIT,TRANS,HS,15KV,300-500/35MM2,BM </option>
                                                <option value="908121153" {{ old('work_type') == '908121153' ? 'selected' : '' }}>- JOINT KIT,TRANS,PM,15KV,300-500/35MM2,BM </option>
                                                <option value="908121142" {{ old('work_type') == '908121142' ? 'selected' : '' }}>- JOINT KIT,STR,HS,15KV,3X500/35MM2,AL,AR </option>
                                                <option value="908121150" {{ old('work_type') == '908121150' ? 'selected' : '' }}>- JOINT KIT,STR,PM,15KV,3X500/35MM2,AL,AR</option>
                                                <!-- 30 -->
                                                <option value="908121001" {{ old('work_type') == '908121001' ? 'selected' : '' }}>-JOINT KIT,STR,1KV,4X70MM2,AL,UAR</option>
                                                <option value="116425940" {{ old('work_type') == '116425940' ? 'selected' : '' }}>-JOINT KIT,STR,1KV,4X95MM2,AL</option>
                                                <option value="908121002" {{ old('work_type') == '908121002' ? 'selected' : '' }}>-JOINT KIT,STR,1KV,4X185MM2,AL,UAR</option>
                                                <option value="908121003" {{ old('work_type') == '908121003' ? 'selected' : '' }}>-JOINT KIT,STR,1KV,4X300MM2,AL,UAR</option>
                                                <option value="116425930" {{ old('work_type') == '116425930' ? 'selected' : '' }}>-JOINT KIT,STR,1KV,4X500MM2,AL</option>
                                                <option value="116425990" {{ old('work_type') == '116425990' ? 'selected' : '' }}>-JOINT KIT,STR,1KV,4X500MM2 To 4X300MM2,AL</option>
                                                <option value="908121042" {{ old('work_type') == '908121042' ? 'selected' : '' }}>-TERM KIT,STR,15KV,1X50/16MM2,CU</option>
                                                <option value="908121043" {{ old('work_type') == '908121043' ? 'selected' : '' }}>-TERM KIT,RT,15KV,1X50/16MM2,CU</option>
                                                <option value="908121121" {{ old('work_type') == '908121121' ? 'selected' : '' }}>-TERM KIT,ST,PM,15KV,50/16MM2,CU,I/D</option>
                                                <option value="908121048" {{ old('work_type') == '908121048' ? 'selected' : '' }}>-TERM KIT,RT,15KV,3X185/35MM2,CU,IN</option>
                                                <option value="908121051" {{ old('work_type') == '908121051' ? 'selected' : '' }}>-TERM KIT,HS,RT,15KV,3X300/35MM2,CU</option>
                                                <option value="908121151" {{ old('work_type') == '908121151' ? 'selected' : '' }}>-TERM KIT,STR,PM,15KV,3X500/35MM2,AL,O/D</option>
                                                <option value="908121152" {{ old('work_type') == '908121152' ? 'selected' : '' }}>-TERM KIT,STR,PM,15KV,3X500/35MM2,AL,I/D</option>
                                                <option value="908121154" {{ old('work_type') == '908121154' ? 'selected' : '' }}>-TERM KIT,HS,RT,15KV,3X500/35MM2,AL,I/D</option>
                                                <option value="908121159" {{ old('work_type') == '908121159' ? 'selected' : '' }}>-TERM KIT,PM,RT,15KV,3X500/35MM2,AL,I/D</option>
                                                <option value="908121008" {{ old('work_type') == '908121008' ? 'selected' : '' }}>-TERM KIT,STR,1KV,4X70MM2,AL</option>
                                                <option value="116428670" {{ old('work_type') == '116428670' ? 'selected' : '' }}>-TERM KIT,STR,1KV,4X95MM2,AL</option>
                                                <option value="908121009" {{ old('work_type') == '908121009' ? 'selected' : '' }}>-TERM KIT,STR,1KV,4X185MM2,AL</option>
                                                <option value="908121010" {{ old('work_type') == '908121010' ? 'selected' : '' }}>-TERM KIT,STR,1KV,4X300MM2,AL</option>
                                                <option value="116428730" {{ old('work_type') == '116428730' ? 'selected' : '' }}>-TERM KIT,STR,1KV,4X500MM2,AL</option>
                                                <option value="116426020" {{ old('work_type') == '116426020' ? 'selected' : '' }}>-BRANCH JIONT 4X300MM2 MAIN / 4X95MM2</option>
                                                <option value="116426060" {{ old('work_type') == '116426060' ? 'selected' : '' }}>-BRANCH JIONT 4X300MM2 MAIN / 4X185MM2</option>
                                                <option value="116426720" {{ old('work_type') == '116426720' ? 'selected' : '' }}>-STRAIGHT JIONT 4X300MM2 MAIN / 4X300MM2</option>
                                                <option value="116426000" {{ old('work_type') == '116426000' ? 'selected' : '' }}>-BRANCH JIONT 4X500MM2 MAIN / 4X95MM2</option>
                                                <option value="116426050" {{ old('work_type') == '116426050' ? 'selected' : '' }}>-BRANCH JIONT 4X500MM2 MAIN / 4X185MM2</option>
                                                <option value="116426730" {{ old('work_type') == '116426730' ? 'selected' : '' }}>-BRANCH JIONT 4X500MM2 MAIN / 4X300MM2</option>
                                                <option value="116426030" {{ old('work_type') == '116426030' ? 'selected' : '' }}>-D BRANCH JIONT 4X300MM2 MAIN / 4X95MM2</option>
                                                <option value="116426080" {{ old('work_type') == '116426080' ? 'selected' : '' }}>-D BRANCH JIONT 4X300MM2 MAIN / 4X185MM2</option>
                                                <option value="116035220" {{ old('work_type') == '116035220' ? 'selected' : '' }}>-D BRANCH JIONT 4X500MM2 MAIN / 4X95MM2</option>
                                                <option value="116426040" {{ old('work_type') == '116426040' ? 'selected' : '' }}>-D BRANCH JIONT 4X500MM2 MAIN / 4X185MM2</option>
                                                <option value="908371001" {{ old('work_type') == '908371001' ? 'selected' : '' }}>-MCCB,750VAC,30A,3P,220/127&380/220V</option>
                                                <option value="908371013" {{ old('work_type') == '908371013' ? 'selected' : '' }}>-MCCB :50A,3POLE,400V.60 HZ,INDOOR SURF-</option>
                                                <option value="908371002" {{ old('work_type') == '908371002' ? 'selected' : '' }}>-MCCB,750VAC,60A,3P,220/127&380/220V</option>
                                                <option value="908371014" {{ old('work_type') == '908371014' ? 'selected' : '' }}>-MCCB:70 AMPS,400V.60 HZ,INDOOR SURFACE</option>
                                                <option value="908371003" {{ old('work_type') == '908371003' ? 'selected' : '' }}>-MCCB,750VAC,100A,3P,220/127&380/220V</option>
                                                <option value="908371004" {{ old('work_type') == '908371004' ? 'selected' : '' }}>-MCCB,750VAC,150A,3P,220/127&380/220V</option>
                                                <option value="119413760" {{ old('work_type') == '119413760' ? 'selected' : '' }}>-MCCB :160 AMPS,3POLE,400 VOLTS,60 HZ,</option>
                                                <option value="908371005" {{ old('work_type') == '908371005' ? 'selected' : '' }}>-MCCB,750VAC,200A,3P,220/127&380/220V</option>
                                                <option value="908371006" {{ old('work_type') == '908371006' ? 'selected' : '' }}>-MCCB,750VAC,300A,3P,220/127&380/220V</option>
                                                <option value="908371007" {{ old('work_type') == '908371007' ? 'selected' : '' }}>-MCCB,750VAC,400A,3P,220/127&380/220V</option>
                                                <option value="908371008" {{ old('work_type') == '908371008' ? 'selected' : '' }}>-MCCB,750VAC,500A,3P,W/ LOCK</option>
                                                <option value="908373001" {{ old('work_type') == '908373001' ? 'selected' : '' }}>-MCCB750VAC,200A,220/127&380/220V,W/O KEY</option>
                                                <option value="908372001" {{ old('work_type') == '908372001' ? 'selected' : '' }}>-MCCB750VAC,400A,220/127&380/220V,W/O KEY</option>
                                                <option value="119416080" {{ old('work_type') == '119416080' ? 'selected' : '' }}>-MCCB,750VAC,500A,3P,W/ LOCK</option>
                                                <option value="908371009" {{ old('work_type') == '908371009' ? 'selected' : '' }}>-MCCB,750VAC,630A,3P,W/ LOCK</option>
                                                <option value="908371010" {{ old('work_type') == '908371010' ? 'selected' : '' }}>-MCCB,750VAC,800A,3P,W/ LOCK</option>
                                                <option value="908371017" {{ old('work_type') == '908371017' ? 'selected' : '' }}>-BREAKER,CRCT,MC,750V,ABS POLE,600 A</option>
                                                <option value="908371018" {{ old('work_type') == '908371018' ? 'selected' : '' }}>-BREAKER,CRCT,MC,750V,3 POLE,800A</option>
                                                <option value="908371019" {{ old('work_type') == '908371019' ? 'selected' : '' }}>-BREAKER,CRCT,MC,750V,3 POLE,1000 A</option>
                                                <option value="908401006" {{ old('work_type') == '908401006' ? 'selected' : '' }}>-METER, KWH, (100)A, 3P, 400/230/133VAC ANALOG</option>
                                                <option value="908401007" {{ old('work_type') == '908401007' ? 'selected' : '' }}>-METER, KWH, (160)A, 3P, 400/230/133VAC ANALOG</option>
                                                <option value="908401008" {{ old('work_type') == '908401008' ? 'selected' : '' }}>-METER,KWH,CT OPER,1.5(6)A,3P,400V ANALOG</option>
                                                <option value="908402001" {{ old('work_type') == '908402001' ? 'selected' : '' }}>-METER,KWH,DIG,10(100)A,127/220/380V,4W</option>
                                                <option value="908402002" {{ old('work_type') == '908402002' ? 'selected' : '' }}>-METER,KWH,DIG,20(160)A,127/220/380V,4W</option>
                                                <option value="908501001" {{ old('work_type') == '908501001' ? 'selected' : '' }}>-TFMR,CT,LV,200/5A,10VA</option>
                                                <option value="908501002" {{ old('work_type') == '908501002' ? 'selected' : '' }}>-TFMR,CT,LV,300/5A,10VA</option>
                                                <option value="908501003" {{ old('work_type') == '908501003' ? 'selected' : '' }}>-TFMR,CT,LV,400/5A,10VA</option>
                                                <option value="908501004" {{ old('work_type') == '908501004' ? 'selected' : '' }}>-TFMR,CT,LV,500/5A,10VA</option>
                                                <option value="908501005" {{ old('work_type') == '908501005' ? 'selected' : '' }}>-TFMR,CT,LV,600/5A,10VA</option>
                                                <option value="908501006" {{ old('work_type') == '908501006' ? 'selected' : '' }}>-TFMR,CT,LV,800/5A,10VA</option>
                                                <option value="908501007" {{ old('work_type') == '908501007' ? 'selected' : '' }}>-TFMR,CT,LV,1500/5A,10VA,SOLID CORE</option>
                                                <option value="908501008" {{ old('work_type') == '908501008' ? 'selected' : '' }}>-TFMR,CT,LV,3000/5A,10VA,SOLID CORE</option>
                                                <option value="908501009" {{ old('work_type') == '908501009' ? 'selected' : '' }}>-TFMR,CT,LV,4000/5A,10VA,SOLID CORE</option>
                                                <option value="908501107" {{ old('work_type') == '908501107' ? 'selected' : '' }}>-TFMR,CT,LV,1500/5A,10VA,SPLIT CORE</option>
                                                <option value="908501108" {{ old('work_type') == '908501108' ? 'selected' : '' }}>-TFMR,CT,LV,3000/5A,10VA,SPLIT CORE</option>
                                                <option value="908501109" {{ old('work_type') == '908501109' ? 'selected' : '' }}>-TFMR,CT,LV,4000/5A,10VA,SPLIT CORE</option>
                                                <option value="908421001" {{ old('work_type') == '908421001' ? 'selected' : '' }}>-BOX,M,1 CUST UP TO 150A,380V,OTDR</option>
                                                <option value="101820240" {{ old('work_type') == '101820240' ? 'selected' : '' }}>-BOX:METER,SINGLE,1,50 SQMM,430 M</option>
                                                <option value="101820910" {{ old('work_type') == '101820910' ? 'selected' : '' }}>-C,BOX,METER:OUTDOOR,1,220/380 VA</option>
                                                <option value="908421002" {{ old('work_type') == '908421002' ? 'selected' : '' }}>-BOX,M,2 CUST UP TO 150A EACH,380V,OTDR</option>
                                                <!-- 100 -->
                                                <option value="908421003" {{ old('work_type') == '908421003' ? 'selected' : '' }}>-BOX,M,4 CUST UP TO 150A EACH,380V,OTDR</option>
                                                <option value="101820810" {{ old('work_type') == '101820810' ? 'selected' : '' }}>-BOX,M,5 CUST,INDR,220/380 VAC VOLT</option>
                                                <option value="908421004" {{ old('work_type') == '908421004' ? 'selected' : '' }}>-BOX,M,1 CUST,DIST,CT OPER,REMOTE,OTDR</option>
                                                <option value="908421005" {{ old('work_type') == '908421005' ? 'selected' : '' }}>-BOX,M,1 CUST,CT,380V,200A,OTDR</option>
                                                <option value="908421006" {{ old('work_type') == '908421006' ? 'selected' : '' }}>-BOX,M,1CUST,CT,380V,300/400A,OTDR</option>
                                                <option value="908421007" {{ old('work_type') == '908421007' ? 'selected' : '' }}>-BOX,M,1CUST,CT,380V,630A,785X205X965MM</option>
                                                <option value="908422800" {{ old('work_type') == '908422800' ? 'selected' : '' }}>-CABINET,SERVICE,800A,POLYESTER</option>
                                                <option value="908121068" {{ old('work_type') == '908121068' ? 'selected' : '' }}>-BOOT,RT ANG,15KV,1X50/16SQMM,CU,XLPE</option>
                                                <option value="908121070" {{ old('work_type') == '908121070' ? 'selected' : '' }}>-BOOT,RT ANG,15KV,3X185/35MM2,CU,XLPE</option>
                                                <option value="908121071" {{ old('work_type') == '908121071' ? 'selected' : '' }}>-BOOT,RT ANG,15KV,3X300/35MM2,CU/AL,XLPE</option>
                                                <option value="908121146" {{ old('work_type') == '908121146' ? 'selected' : '' }}>-BOOT,RT ANG,HS,15KV,3X500/35MM2,AL,XLPE</option>
                                                <option value="908121076" {{ old('work_type') == '908121076' ? 'selected' : '' }}>-BOOT,STR,15KV,1X50/16SQMM,CU,XLPE</option>
                                                <option value="908121078" {{ old('work_type') == '908121078' ? 'selected' : '' }}>-BOOT,STR,15KV,3X185/35MM2,CU,XLPE</option>
                                                <option value="908121079" {{ old('work_type') == '908121079' ? 'selected' : '' }}>-BOOT,STR,15KV,3X300/35MM2,CU/AL,XLPE</option>
                                                <option value="908121147" {{ old('work_type') == '908121147' ? 'selected' : '' }}>-BOOT,STR,HS,15KV,3X500/35MM2,AL,XLPE,AR</option>
                                                <option value="908121017" {{ old('work_type') == '908121017' ? 'selected' : '' }}>-CAP,CBL END,1KV,4X70-185MM2,AL,XLPE/PVC</option>
                                                <option value="908121019" {{ old('work_type') == '908121019' ? 'selected' : '' }}>-CAP,CBL END,1KV,4X300-3X185/35MM2,AL</option>
                                                <option value="116425440" {{ old('work_type') == '116425440' ? 'selected' : '' }}>-CAP,CABLE END,4C X 500 SQMM,1.1 KV,POLYO</option>
                                                <option value="116418370" {{ old('work_type') == '116418370' ? 'selected' : '' }}>-TUBING,SHRINK,POLYCARB,56.15 MM DIA,#MWT</option>
                                                <option value="116418500" {{ old('work_type') == '116418500' ? 'selected' : '' }}>-TUBING,SHRINK,POLYCARB,81.55 MM DIA,#MWT</option>
                                                <option value="116410990" {{ old('work_type') == '116410990' ? 'selected' : '' }}>-SLEEVE:REPAIR,HEAT SHRINK,4 X150 SQM-95</option>
                                                <option value="908121031" {{ old('work_type') == '908121031' ? 'selected' : '' }}>-SLEEVE,RPR,(4)300SQMM,XLPE/PVC,1500MM LG</option>
                                                <option value="116411140" {{ old('work_type') == '116411140' ? 'selected' : '' }}>-SLEEVE,RPR,4 X 500 SQMM AL CNDCTR,#20041</option>
                                                <option value="908121025" {{ old('work_type') == '908121025' ? 'selected' : '' }}>-SLEEVE,REPAIR,1X500/35SQMM,XLPE,UARM</option>
                                                <option value="908122002" {{ old('work_type') == '908122002' ? 'selected' : '' }}>-CONNECTOR,LUG,35SQMM CU,(1)M10,BLT HL</option>
                                                <option value="908122003" {{ old('work_type') == '908122003' ? 'selected' : '' }}>-CONNECTOR,LUG,50MM2 CU,(1)M12 BLT HL</option>
                                                <option value="908122010" {{ old('work_type') == '908122010' ? 'selected' : '' }}>-CONNECTOR,LUG,70SQMM AL,(1)M12 BLT HL</option>
                                                <option value="908122034" {{ old('work_type') == '908122034' ? 'selected' : '' }}>-CONNECTOR,LUG,70SQMM CU,13MMDIA,40.4MMLG</option>
                                                <option value="115250950" {{ old('work_type') == '115250950' ? 'selected' : '' }}>-CONN,ELEC,TERM,AL,95 SQ MM CNDCTR,#XCX95</option>
                                                <option value="908122035" {{ old('work_type') == '908122035' ? 'selected' : '' }}>-CONNECTOR,LUG,120SQMM CU,13MMDIA,85MM LG</option>
                                                <option value="908122036" {{ old('work_type') == '908122036' ? 'selected' : '' }}>-CONNECTOR,LUG,120SQMM AL,(1)M12 BLT HL</option>
                                                <option value="908122009" {{ old('work_type') == '908122009' ? 'selected' : '' }}>-CONNECTOR,LUG,185SQMM AL,(1)M12 BLT HL</option>
                                                <option value="908122008" {{ old('work_type') == '908122008' ? 'selected' : '' }}>-CONNECTOR,LUG,300SQMM AL,(1)M12 BLT HL</option>
                                                <option value="908122012" {{ old('work_type') == '908122012' ? 'selected' : '' }}>-CONNECTOR,LUG,300MM2 AL/CU,(1)M10 BLT HL</option>
                                                <option value="115225060" {{ old('work_type') == '115225060' ? 'selected' : '' }}>-CONN,ELEC,TERM,AL,500SQMM CNDCTR,#SCTS63</option>
                                                <option value="908122006" {{ old('work_type') == '908122006' ? 'selected' : '' }}>-CONNECTOR,LUG,630MM2 CU,(4)M10 BLT HL</option>
                                                <option value="118211290" {{ old('work_type') == '118211290' ? 'selected' : '' }}>-FUSE CARRIER: FOR LV DIST BOARD</option>
                                                <option value="118144120" {{ old('work_type') == '118144120' ? 'selected' : '' }}>-FUSE LINK: HRC 40A 13.8/15KV FOR SF6 INS</option>
                                                <option value="908342032" {{ old('work_type') == '908342032' ? 'selected' : '' }}>-FUSE,POWER,17.5KV,50A</option>
                                                <option value="908342033" {{ old('work_type') == '908342033' ? 'selected' : '' }}>-FUSE,POWER,17.5KV,80A</option>
                                                <option value="908342034" {{ old('work_type') == '908342034' ? 'selected' : '' }}>-FUSE,POWER,17.5KV,100A</option>
                                                <option value="908342035" {{ old('work_type') == '908342035' ? 'selected' : '' }}>-FUSE,POWER,17.5KV,125A</option>
                                                <option value="118148010" {{ old('work_type') == '118148010' ? 'selected' : '' }}>-FUSE,PWR,13.8KV,80 A,HRC,FE,#155OHGMA80</option>
                                                <option value="118144040" {{ old('work_type') == '118144040' ? 'selected' : '' }}>-HV 13.8KV 40 AMPS FUSE OIL IMMERSED</option>
                                                <option value="118142540" {{ old('work_type') == '118142540' ? 'selected' : '' }}>-FUSE LINK:HRC 25 AMPS,13800V.63.5 MM.</option>
                                                <option value="118054040" {{ old('work_type') == '118054040' ? 'selected' : '' }}>-FUSE LINK,HRC,400MPS,500V,WEDGE "J" TYPE</option>
                                                <option value="118055020" {{ old('work_type') == '118055020' ? 'selected' : '' }}>-FUSE LINK HRC 500 AMP,500V,WEDGE J TYPE</option>
                                                <option value="908342036" {{ old('work_type') == '908342036' ? 'selected' : '' }}>-Fuse Catriage 200 AMP</option>
                                                <option value="118054050" {{ old('work_type') == '118054050' ? 'selected' : '' }}>-Fuse Catriage 400 AMP</option>
                                                <option value="118055030" {{ old('work_type') == '118055030' ? 'selected' : '' }}>-Fuse Catriage 500 AMP</option>
                                                <option value="118221310" {{ old('work_type') == '118221310' ? 'selected' : '' }}>-HOLDER,FUSE:CARTRIDGE FUSE,2000A,110/220</option>
                                                <option value="101811940" {{ old('work_type') == '101811940' ? 'selected' : '' }}>-BLOCK:TERMINAL UPTO 185MM2 FOR 4 IN/OUT</option>
                                                <option value="101811920" {{ old('work_type') == '101811920' ? 'selected' : '' }}>-BLOCK,TERMINAL,MOULDED,UPTO 185MM2 STR</option>
                                                <option value="115420350" {{ old('work_type') == '115420350' ? 'selected' : '' }}>-CONNECTOR, SPLIT BOLT FOR 35MM2 CU</option> 
                                                <option value="115420520" {{ old('work_type') == '115420520' ? 'selected' : '' }}>-CONNECTOR, SPLIT BOLT FOR 50MM2 CU</option>
                                                <option value="115420720" {{ old('work_type') == '115420720' ? 'selected' : '' }}>-CONNECTOR, SPLIT BOLT FOR 70MM2 CU</option>
                                                <option value="115420950" {{ old('work_type') == '115420950' ? 'selected' : '' }}>-CONNECTOR, SPLIT BOLT FOR 95MM2 CU</option>
                                                <option value="115421850" {{ old('work_type') == '115421850' ? 'selected' : '' }}>-CONNECTOR, SPLIT BOLT FOR 185MM2 CU</option>
                                                <option value="115432110" {{ old('work_type') == '115432110' ? 'selected' : '' }}>-CONNECTOR MECH. TYPE 70-95MM2 AL ST. JOI</option>
                                                <option value="115431150" {{ old('work_type') == '115431150' ? 'selected' : '' }}>-CONNECTOR MECH. TYPE FOR 300MM2 STR</option>
                                                <!-- 160 -->
                                                <option value="115432140" {{ old('work_type') == '115432140' ? 'selected' : '' }}>-CONNECTOR MECHANICAL TYPE FOR 500MM2</option> 
                                                <option value="115155010" {{ old('work_type') == '115155010' ? 'selected' : '' }}>-CABLE BIMETAL CONNECTOR 500MM2 AL/95MM2</option>
                                                <option value="908342020" {{ old('work_type') == '908342020' ? 'selected' : '' }}>-LINK,FUSE,36KV,10A,FAST</option>
                                                <option value="908342022" {{ old('work_type') == '908342022' ? 'selected' : '' }}>-LINK,FUSE,36KV,15A,FAST</option>
                                                <option value="908342023" {{ old('work_type') == '908342023' ? 'selected' : '' }}>-LINK,FUSE,36KV,20A,FAST</option>
                                                <option value="908342024" {{ old('work_type') == '908342024' ? 'selected' : '' }}>-LINK,FUSE,36KV,25A,FAST</option>
                                                <option value="908342025" {{ old('work_type') == '908342025' ? 'selected' : '' }}>-LINK,FUSE,36KV,30A,FAST</option>
                                                <option value="908342026" {{ old('work_type') == '908342026' ? 'selected' : '' }}>-LINK,FUSE,36KV,40A,FAST</option> 
                                                <option value="908342027" {{ old('work_type') == '908342027' ? 'selected' : '' }}>-LINK,FUSE,36KV,50A,FAST</option>
                                                <option value="908342028" {{ old('work_type') == '908342028' ? 'selected' : '' }}>-LINK,FUSE,36KV,65A,FAST</option>
                                                <option value="908342029" {{ old('work_type') == '908342029' ? 'selected' : '' }}>-LINK,FUSE,36KV,80A,FAST</option>
                                                <option value="908342030" {{ old('work_type') == '908342030' ? 'selected' : '' }}>-LINK,FUSE,36KV,100A,FAST</option>
                                                <option value="118172020" {{ old('work_type') == '118172020' ? 'selected' : '' }}>-LINK,FUSE,33/34.5 KV,200A,#704200</option>
                                                <option value="908341003" {{ old('work_type') == '908341003' ? 'selected' : '' }}>-CUTOUT,FUSE,100A,33KV,825MM CRP</option>
                                                <option value="908341004" {{ old('work_type') == '908341004' ? 'selected' : '' }}>-CUTOUT,FUSE,100A,33KV,1320MM CRP</option> 
                                                <option value="118181210" {{ old('work_type') == '118181210' ? 'selected' : '' }}>-CUTOUT,FUSE,60HZ FREQ,16 KA INTCAP,#9254</option>
                                                <option value="908202053" {{ old('work_type') == '908202053' ? 'selected' : '' }}>-ROD,GRD,CUWLD STL,16MM DIA,1200MM LG</option>
                                                <option value="908312001" {{ old('work_type') == '908312001' ? 'selected' : '' }}>-PILLAR,DIST,LV,400A,800X310X830MM</option>
                                                <option value="908312002" {{ old('work_type') == '908312002' ? 'selected' : '' }}>-BASE,PILLAR DIST LV,GLASS REINF</option>
                                                <option value="908312003" {{ old('work_type') == '908312003' ? 'selected' : '' }}>-PILLAR,DIST,LV,GLASS REINF,400A,NO BASE</option>
                                                <option value="102015050" {{ old('work_type') == '102015050' ? 'selected' : '' }}>-TFMR,DIST,PF,500KVA,33KV,231/133V,170KV</option>
                                                <option value="102015170" {{ old('work_type') == '102015170' ? 'selected' : '' }}>-TFMR,DIST,PF,500KVA,33KV,231/400,170KV</option> 
                                                <!-- 182 -->
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option> 
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option> 
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option> 
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                              
                                               
                                            
                                            </select>
                                        </div>
                                    </div>
                                    @error('work_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                

                                <script>
                                    document.addEventListener("DOMContentLoaded", function () {
                                        const materialInput = document.getElementById("work_type_number");
                                        const materialSelect = document.getElementById("work_type");

                                        // ربط رقم المادة بالقيمة داخل القائمة
                                        const materialsMap = {
                                            "908112050": "- CABLE,PWR,600V/1KV,QUADRUPLEX,3X50+1X50",
                                            "908112120": "- CABLE,PWR,600V/1KV,QUADRUPLEX,3X120MM2",
                                            "908101001": "- COND,BR,ACSR,QUAIL (2/0 AWG),6AL,1ALWLD",
                                            "908101002": "- COND,BR,ACSR,MERLIN(336.4MCM)18AL,170MM2",
                                            "908111101": "- CNDCTR,BR,CU,35SQMM,7STR,SOFT DRWN",
                                            "908111102": "- CNDCTR,BR,CU,70SQMM,19STR,SOFT DRWN",
                                            "908113005": "- CABLE,PWR,15KV,CU,1C,50/16MM2,XLPLE,UARM",
                                            "908113001": "- CABLE,PWR,15KV,CU,3C,185/35MM2,XPLE,UARM",
                                            "908113002": "- CABLE,PWR,15KV,CU,3C,300/35SQMM,XPLE,",
                                            "908114004": "- CABLE,PWR,15KV,AL,3X500/35MM2,XLPE/LLDPE",
                                            "908113007": "- CABLE,PWR,36KV,CU,3C,240/35MM2,XPLE,ARM",
                                            "908111001": "- CABLE,PWR,600V/1KV,CU,1C,35MM2,XLPE",
                                            "908111002": "- CABLE,PWR,600V/1KV,CU,1C,120MM2,XLPE",
                                            "908111004": "- CABLE,PWR,600V/1KV,CU,1C,630MM2,XLPE",
                                            "908111005": "- CABLE,PWR,600V/1KV,AL,4C,70MM2,XLPE",
                                            "110430980": "- CABLE:4X95MM2,ALUM,XLPE/SWA/PVC,LV AS",
                                            "908111006": "- CABLE,PWR,600V/1KV,AL,4C,185MM2,XLPE",
                                            "908111007": "- CABLE,PWR,600V/1KV,AL,4C,300MM2,XLPE",
                                            "908111014": "- CABLE, PWR, 1KV, AL, 1C, 800/2.6 MM2, XPLE, PVC-ST2",
                                            "908122054": "- CONN, ELEC, TERM, 15KV, 800 SQM, AL, 4HPAD",
                                            "110435040": "- CABLE,PWR,600V/1KV,AL,4C,500MM2,XLPE",
                                            "908121004": "- JOINT KIT,STR,15KV,3X185/35MM2,CU,UAR",
                                            "908121005": "- JOINT KIT,STR,15KV,3X300/35MM2,CU,AR",
                                            "908121016": "- JOINT KIT,TRANS,15KV,3X185/35MM2,CU",
                                            "908121155": "- JOINT KIT,TRANS,HS,15KV,185-500/35MM2,BM",
                                            "908121156": "- JOINT KIT,TRANS,PM,15KV,185-500/35MM2,BM",
                                            "908121149": "- JOINT KIT,TRANS,HS,15KV,300-500/35MM2,BM",
                                            "908121153": "- JOINT KIT,TRANS,PM,15KV,300-500/35MM2,BM",
                                            "908121142": "- JOINT KIT,STR,HS,15KV,3X500/35MM2,AL,AR",
                                            "908121150": "- JOINT KIT,STR,PM,15KV,3X500/35MM2,AL,AR",
                                            "908121001": "-JOINT KIT,STR,1KV,4X70MM2,AL,UAR",
                                            "116425940": "-JOINT KIT,STR,1KV,4X95MM2,AL",
                                            "908121002": "-JOINT KIT,STR,1KV,4X185MM2,AL,UAR",
                                            "908121003": "-JOINT KIT,STR,1KV,4X300MM2,AL,UAR",
                                            "116425930": "-JOINT KIT,STR,1KV,4X500MM2,AL",
                                            "116425990": "-JOINT KIT,STR,1KV,4X500MM2 To 4X300MM2,AL",
                                            "908121042": "-TERM KIT,STR,15KV,1X50/16MM2,CU",
                                            "908121043": "-TERM KIT,RT,15KV,1X50/16MM2,CU",
                                            "908121121": "-TERM KIT,ST,PM,15KV,50/16MM2,CU,I/D",
                                            "908121048": "-TERM KIT,RT,15KV,3X185/35MM2,CU,IN",
                                            "908121051": "-TERM KIT,HS,RT,15KV,3X300/35MM2,CU",
                                            "908121151": "-TERM KIT,STR,PM,15KV,3X500/35MM2,AL,O/D",
                                            "908121152": "-TERM KIT,STR,PM,15KV,3X500/35MM2,AL,I/D",
                                            "908121154": "-TERM KIT,HS,RT,15KV,3X500/35MM2,AL,I/D",
                                            "908121159": "-TERM KIT,PM,RT,15KV,3X500/35MM2,AL,I/D",
                                            "908121008": "-TERM KIT,STR,1KV,4X70MM2,AL",
                                            "116428670": "-TERM KIT,STR,1KV,4X95MM2,AL",
                                            "908121009": "-TERM KIT,STR,1KV,4X185MM2,AL",
                                            "908121010": "-TERM KIT,STR,1KV,4X300MM2,AL",
                                            "116428730": "-TERM KIT,STR,1KV,4X500MM2,AL",
                                            "116426020": "-BRANCH JIONT 4X300MM2 MAIN / 4X95MM2",
                                            "116426060": "-BRANCH JIONT 4X300MM2 MAIN / 4X185MM2",
                                            "116426720": "-STRAIGHT JIONT 4X300MM2 MAIN / 4X300MM2",
                                            "116426000": "-BRANCH JIONT 4X500MM2 MAIN / 4X95MM2",
                                            "116426050": "-BRANCH JIONT 4X500MM2 MAIN / 4X185MM2",
                                            "116426730": "-BRANCH JIONT 4X500MM2 MAIN / 4X300MM2",
                                            "116426030": "-D BRANCH JIONT 4X300MM2 MAIN / 4X95MM2",
                                            "116426080": "-D BRANCH JIONT 4X300MM2 MAIN / 4X185MM2",
                                            "116035220": "-D BRANCH JIONT 4X500MM2 MAIN / 4X95MM2",
                                            "116426040": "-D BRANCH JIONT 4X500MM2 MAIN / 4X185MM2",
                                            "908371001": "-MCCB,750VAC,30A,3P,220/127&380/220V",
                                            "908371013": "-MCCB :50A,3POLE,400V.60 HZ,INDOOR SURF-",
                                            "908371002": "-MCCB,750VAC,60A,3P,220/127&380/220V",
                                            "908371014": "-MCCB:70 AMPS,400V.60 HZ,INDOOR SURFACE",
                                            "908371003": "-MCCB,750VAC,100A,3P,220/127&380/220V",
                                            "908371004": "-MCCB,750VAC,150A,3P,220/127&380/220V",
                                            "119413760": "-MCCB :160 AMPS,3POLE,400 VOLTS,60 HZ,",
                                            "908371005": "-MCCB,750VAC,200A,3P,220/127&380/220V",
                                            "908371006": "-MCCB,750VAC,300A,3P,220/127&380/220V",
                                            "908371007": "-MCCB,750VAC,400A,3P,220/127&380/220V",
                                            "908371008": "-MCCB,750VAC,500A,3P,W/ LOCK",
                                            "908373001": "-MCCB750VAC,200A,220/127&380/220V,W/O KEY",
                                            "908372001": "-MCCB750VAC,400A,220/127&380/220V,W/O KEY",
                                            "119416080": "-MCCB,750VAC,500A,3P,W/ LOCK",
                                            "908371009": "-MCCB,750VAC,630A,3P,W/ LOCK",
                                            "908371010": "-MCCB,750VAC,800A,3P,W/ LOCK",
                                            "908371017": "-BREAKER,CRCT,MC,750V,ABS POLE,600 A",
                                            "908371018": "-BREAKER,CRCT,MC,750V,3 POLE,800A",
                                            "908371019": "-BREAKER,CRCT,MC,750V,3 POLE,1000 A",
                                            "908401006": "-METER, KWH, (100)A, 3P, 400/230/133VAC ANALOG",
                                            "908401007": "-METER, KWH, (160)A, 3P, 400/230/133VAC ANALOG",
                                            "908401008": "-METER,KWH,CT OPER,1.5(6)A,3P,400V ANALOG",
                                            "908402001": "-METER,KWH,DIG,10(100)A,127/220/380V,4W",
                                            "908402002": "-METER,KWH,DIG,20(160)A,127/220/380V,4W",
                                            "908501001": "-TFMR,CT,LV,200/5A,10VA",
                                            "908501002": "-TFMR,CT,LV,300/5A,10VA",
                                            "908501003": "-TFMR,CT,LV,400/5A,10VA",
                                            "908501004": "-TFMR,CT,LV,500/5A,10VA",
                                            "908501005": "-TFMR,CT,LV,600/5A,10VA",
                                            "908501006": "-TFMR,CT,LV,800/5A,10VA",
                                            "908501007": "-TFMR,CT,LV,1500/5A,10VA,SOLID CORE",
                                            "908501008": "-TFMR,CT,LV,3000/5A,10VA,SOLID CORE",
                                            "908501009": "-TFMR,CT,LV,4000/5A,10VA,SOLID CORE",
                                            "908501107": "-TFMR,CT,LV,1500/5A,10VA,SPLIT CORE",
                                            "908501108": "-TFMR,CT,LV,3000/5A,10VA,SPLIT CORE",
                                            "908501109": "-TFMR,CT,LV,4000/5A,10VA,SPLIT CORE",
                                            "908421001": "-BOX,M,1 CUST UP TO 150A,380V,OTDR",
                                            "101820240": "-BOX:METER,SINGLE,1,50 SQMM,430 M",
                                            "101820910": "-C,BOX,METER:OUTDOOR,1,220/380 VA",
                                            "908421002": "-BOX,M,2 CUST UP TO 150A EACH,380V,OTDR",
                                            "908421003": "-BOX,M,4 CUST UP TO 150A EACH,380V,OTDR",
                                            "101820810": "-BOX,M,5 CUST,INDR,220/380 VAC VOLT",
                                            "908421004": "-BOX,M,1 CUST,DIST,CT OPER,REMOTE,OTDR",
                                            "908421005": "-BOX,M,1 CUST,CT,380V,200A,OTDR",
                                            "908421006": "-BOX,M,1CUST,CT,380V,300/400A,OTDR",
                                            "908421007": "-BOX,M,1CUST,CT,380V,630A,785X205X965MM",
                                            "908422800": "-CABINET,SERVICE,800A,POLYESTER",
                                            "908121068": "-BOOT,RT ANG,15KV,1X50/16SQMM,CU,XLPE",
                                            "908121070": "-BOOT,RT ANG,15KV,3X185/35MM2,CU,XLPE",
                                            "908121071": "-BOOT,RT ANG,15KV,3X300/35MM2,CU/AL,XLPE",
                                            "908121146": "-BOOT,RT ANG,HS,15KV,3X500/35MM2,AL,XLPE",
                                            "908121076": "-BOOT,STR,15KV,1X50/16SQMM,CU,XLPE",
                                            "908121078": "-BOOT,STR,15KV,3X185/35MM2,CU,XLPE",
                                            "908121079": "-BOOT,STR,15KV,3X300/35MM2,CU/AL,XLPE",
                                            "908121147": "-BOOT,STR,HS,15KV,3X500/35MM2,AL,XLPE,AR",
                                            "908121017": "-CAP,CBL END,1KV,4X70-185MM2,AL,XLPE/PVC",
                                            "908121019": "-CAP,CBL END,1KV,4X300-3X185/35MM2,AL",
                                            "116425440": "-CAP,CABLE END,4C X 500 SQMM,1.1 KV,POLYO",
                                            "116418370": "-TUBING,SHRINK,POLYCARB,56.15 MM DIA,#MWT",
                                            "116418500": "-TUBING,SHRINK,POLYCARB,81.55 MM DIA,#MWT",
                                            "116410990": "-SLEEVE:REPAIR,HEAT SHRINK,4 X150 SQM-95",
                                            "908121031": "-SLEEVE,RPR,(4)300SQMM,XLPE/PVC,1500MM LG",
                                            "116411140": "-SLEEVE,RPR,4 X 500 SQMM AL CNDCTR,#20041",
                                            "908121025": "-SLEEVE,REPAIR,1X500/35SQMM,XLPE,UARM",
                                            "908122002": "-CONNECTOR,LUG,35SQMM CU,(1)M10,BLT HL",
                                            "908122003": "-CONNECTOR,LUG,50MM2 CU,(1)M12 BLT HL",
                                            "908122010": "-CONNECTOR,LUG,70SQMM AL,(1)M12 BLT HL",
                                            "908122034": "-CONNECTOR,LUG,70SQMM CU,13MMDIA,40.4MMLG",
                                            "115250950": "-CONN,ELEC,TERM,AL,95 SQ MM CNDCTR,#XCX95",
                                            "908122035": "-CONNECTOR,LUG,120SQMM CU,13MMDIA,85MM LG",
                                            "908122036": "-CONNECTOR,LUG,120SQMM AL,(1)M12 BLT HL",
                                            "908122009": "-CONNECTOR,LUG,185SQMM AL,(1)M12 BLT HL",
                                            "908122008": "-CONNECTOR,LUG,300SQMM AL,(1)M12 BLT HL",
                                            "908122012": "-CONNECTOR,LUG,300MM2 AL/CU,(1)M10 BLT HL",
                                            "115225060": "-CONN,ELEC,TERM,AL,500SQMM CNDCTR,#SCTS63",
                                            "908122006": "-CONNECTOR,LUG,630MM2 CU,(4)M10 BLT HL",
                                            "118211290": "-FUSE CARRIER: FOR LV DIST BOARD",
                                            "118144120": "-FUSE LINK: HRC 40A 13.8/15KV FOR SF6 INS",
                                            "908342032": "-FUSE,POWER,17.5KV,50A",
                                            "908342033": "-FUSE,POWER,17.5KV,80A",
                                            "908342034": "-FUSE,POWER,17.5KV,100A",
                                            "908342035": "-FUSE,POWER,17.5KV,125A",
                                            "118148010": "-FUSE,PWR,13.8KV,80 A,HRC,FE,#155OHGMA80",
                                            "118144040": "-HV 13.8KV 40 AMPS FUSE OIL IMMERSED",
                                            "118142540": "-FUSE LINK:HRC 25 AMPS,13800V.63.5 MM.",
                                            "118054040": "-FUSE LINK,HRC,400MPS,500V,WEDGE \"J\" TYPE",
                                            "118055020": "-FUSE LINK HRC 500 AMP,500V,WEDGE J TYPE",
                                            "908342036": "-Fuse Catriage 200 AMP",
                                            "118054050": "-Fuse Catriage 400 AMP",
                                            "118055030": "-Fuse Catriage 500 AMP",
                                            "118221310": "-HOLDER,FUSE:CARTRIDGE FUSE,2000A,110/220",
                                            "101811940": "-BLOCK:TERMINAL UPTO 185MM2 FOR 4 IN/OUT",
                                            "101811920": "-BLOCK,TERMINAL,MOULDED,UPTO 185MM2 STR",
                                            "115420350": "-CONNECTOR, SPLIT BOLT FOR 35MM2 CU",
                                            "115420520": "-CONNECTOR, SPLIT BOLT FOR 50MM2 CU",
                                            "115420720": "-CONNECTOR, SPLIT BOLT FOR 70MM2 CU",
                                            "115420950": "-CONNECTOR, SPLIT BOLT FOR 95MM2 CU",
                                            "115421850": "-CONNECTOR, SPLIT BOLT FOR 185MM2 CU",
                                            "115432110": "-CONNECTOR MECH. TYPE 70-95MM2 AL ST. JOI",
                                            "115431150": "-CONNECTOR MECH. TYPE FOR 300MM2 STR",
                                            "115432140": "-CONNECTOR MECHANICAL TYPE FOR 500MM2",
                                            "115155010": "-CABLE BIMETAL CONNECTOR 500MM2 AL/95MM2",
                                            "908342020": "-LINK,FUSE,36KV,10A,FAST",
                                            "908342022": "-LINK,FUSE,36KV,15A,FAST",
                                            "908342023": "-LINK,FUSE,36KV,20A,FAST",
                                            "908342024": "-LINK,FUSE,36KV,25A,FAST",
                                            "908342025": "-LINK,FUSE,36KV,30A,FAST",
                                            "908342026": "-LINK,FUSE,36KV,40A,FAST",
                                            "908342027": "-LINK,FUSE,36KV,50A,FAST",
                                            "908342028": "-LINK,FUSE,36KV,65A,FAST",
                                            "908342029": "-LINK,FUSE,36KV,80A,FAST",
                                            "908342030": "-LINK,FUSE,36KV,100A,FAST",
                                            "118172020": "-LINK,FUSE,33/34.5 KV,200A,#704200",
                                            "908341003": "-CUTOUT,FUSE,100A,33KV,825MM CRP",
                                            "908341004": "-CUTOUT,FUSE,100A,33KV,1320MM CRP",
                                            "118181210": "-CUTOUT,FUSE,60HZ FREQ,16 KA INTCAP,#9254",
                                            "908202053": "-ROD,GRD,CUWLD STL,16MM DIA,1200MM LG",
                                            "908312001": "-PILLAR,DIST,LV,400A,800X310X830MM",
                                            "908312002": "-BASE,PILLAR DIST LV,GLASS REINF",
                                            "908312003": "-PILLAR,DIST,LV,GLASS REINF,400A,NO BASE",
                                            "102015050": "-TFMR,DIST,PF,500KVA,33KV,231/133V,170KV",
                                            "102015170": "-TFMR,DIST,PF,500KVA,33KV,231/400,170KV"
                                        };

                                        // عند كتابة رقم المادة
                                        materialInput.addEventListener("input", function () {
                                            const number = materialInput.value;
                                            if (materialsMap.hasOwnProperty(number)) {
                                                materialSelect.value = number;
                                            } else {
                                                materialSelect.value = ""; // إعادة التعيين إذا لم يكن الرقم موجود
                                            }
                                        });
                                    });
                                </script>


                        <!-- معلومات الكميات -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">معلومات الكميات</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="planned_quantity" class="form-label fw-bold">الكمية المخططة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calculator"></i></span>
                                            <input type="number" step="0.01" class="form-control" id="planned_quantity" name="planned_quantity">
                                        </div>
                                        <div class="col-md-8 mb-4">
                                        <label for="line" class="form-label fw-bold">الكمية المصروفة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                            <input type="text" class="form-control" id="line" name="line">
                                        </div>
                                    </div>
                                        <div class="col-md-8 mb-4">
                                        <label for="line" class="form-label fw-bold">الكمية المنفذة بالموقع </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                            <input type="text" class="form-control" id="line" name="line">
                                        </div>
                                    </div>
                                        <div class="invalid-feedback">يرجى إدخال الكمية المخططة</div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="unit" class="form-label fw-bold">الوحدة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-ruler"></i></span>
                                            <select class="form-select" id="unit" name="unit">
                                                <option value="">اختر الوحدة</option>
                                                <option value="L.M">L.M</option>
                                                <option value="Ech">Ech</option>
                                                <option value="Kit">Kit</option>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback">يرجى اختيار الوحدة</div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="actual_quantity" class="form-label fw-bold">الكمية المنفذة</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                            <input type="number" step="0.01" class="form-control" id="actual_quantity" name="actual_quantity">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- المرفقات والملفات -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">المرفقات والملفات</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="check_in_file" class="form-label fw-bold">CHECK LIST</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                            <input type="file" class="form-control" id="check_in_file" name="check_in_file">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="date_gatepass" class="form-label fw-bold">DATE GATEPASS</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                            <input type="date" class="form-control" id="date_gatepass" name="date_gatepass">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="gate_pass_file" class="form-label fw-bold">GATE PASS</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-export"></i></span>
                                            <input type="file" class="form-control" id="gate_pass_file" name="gate_pass_file">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="store_in_file" class="form-label fw-bold">STORE IN</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-import"></i></span>
                                            <input type="file" class="form-control" id="store_in_file" name="store_in_file">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="store_out_file" class="form-label fw-bold">STORE OUT</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-export"></i></span>
                                            <input type="file" class="form-control" id="store_out_file" name="store_out_file">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="store_out_file" class="form-label fw-bold">DDO</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-file-export"></i></span>
                                            <input type="file" class="form-control" id="store_out_file" name="store_out_file">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save ml-2"></i>
                                حفظ المادة
                            </button>
                            <button type="submit" class="btn btn-success btn-lg px-5" name="save_and_continue" value="1">
                                <i class="fas fa-plus ml-2"></i>
                                حفظ وإضافة مادة أخرى
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- القسم الأيسر - جدول المواد -->
        <div class="col-12 mt-5">
            <div class="card shadow-lg">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">
                        <i class="fas fa-list ml-2"></i>
                        جدول المواد
                    </h3>
                    <a href="{{ route('admin.work-orders.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-right ml-1"></i>
                        العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle ml-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle ml-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>كود المادة</th>
                                    <th>الوصف</th>
                                    <th>أمر العمل</th>
                                    <th>السطر</th>
                                    <th>الكمية المخططة</th>
                                    <th>الكمية المنفذة</th>
                                    <th>الكمية المصروفة</th>
                                    <th>الفرق</th>
                                    <th>الوحدة</th>
                                    <th>تاريخ الإضافة</th>
                                    <th>CHECK LIST</th>
                                    <th>GATE PASS</th>
                                    <th>STORE IN</th>
                                    <th>STORE OUT</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($materials->count() > 0)
                                    @foreach($materials as $material)
                                        <tr>
                                            <td>{{ $material->code }}</td>
                                            <td>{{ $material->description }}</td>
                                            <td>
                                                @if($material->workOrder)
                                                    {{ $material->workOrder->order_number }} - {{ $material->workOrder->subscriber_name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $material->line }}</td>
                                            <td>{{ $material->planned_quantity }}</td>
                                            <td>{{ $material->actual_quantity }}</td>
                                            <td>{{ $material->difference }}</td>
                                            <td>{{ $material->unit }}</td>
                                            <td>{{ $material->created_at ? $material->created_at->format('Y-m-d H:i') : '-' }}</td>
                                            <td>
                                                @if($material->check_in_file)
                                                    <a href="{{ asset('storage/' . $material->check_in_file) }}" target="_blank" class="btn btn-sm btn-success">تحميل</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($material->gate_pass_file)
                                                    <a href="{{ asset('storage/' . $material->gate_pass_file) }}" target="_blank" class="btn btn-sm btn-success">تحميل</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($material->store_in_file)
                                                    <a href="{{ asset('storage/' . $material->store_in_file) }}" target="_blank" class="btn btn-sm btn-success">تحميل</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($material->store_out_file)
                                                    <a href="{{ asset('storage/' . $material->store_out_file) }}" target="_blank" class="btn btn-sm btn-success">تحميل</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-primary view-material" data-material="{{ json_encode($material) }}" title="عرض التفاصيل">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="{{ route('admin.work-orders.materials.edit', $material) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.work-orders.materials.destroy', $material) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه المادة؟')" title="حذف">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="14" class="text-center py-4">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle ml-2"></i>
                                                لا توجد مواد مسجلة
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $materials->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal لعرض تفاصيل المادة -->
<div class="modal fade" id="materialDetailsModal" tabindex="-1" aria-labelledby="materialDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="materialDetailsModalLabel">تفاصيل المادة</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="m-0">معلومات أساسية</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>كود المادة:</strong>
                                        <span id="modal-code" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>وصف المادة:</strong>
                                        <div id="modal-description" class="d-block mt-1 p-2 bg-light rounded"></div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>أمر العمل:</strong>
                                        <span id="modal-work-order" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>السطر:</strong>
                                        <span id="modal-line" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>الكمية المصروفة:</strong>
                                        <span id="modal-line" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="m-0">معلومات الكمية</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>الكمية المخططة:</strong>
                                        <span id="modal-planned-quantity" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>الوحدة:</strong>
                                        <span id="modal-unit" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>الكمية المنفذة الفعلية:</strong>
                                        <span id="modal-actual-quantity" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>الفرق:</strong>
                                        <span id="modal-difference" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="m-0">الملفات والمرفقات</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>CHECK LIST:</strong>
                                        <div id="modal-check-in-file" class="d-block mt-1"></div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>GATE PASS:</strong>
                                        <div id="modal-gate-pass-file" class="d-block mt-1"></div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>STORE IN:</strong>
                                        <div id="modal-store-in-file" class="d-block mt-1"></div>
                                    </div>
                                    <div class="mb-3">
                                        <strong>STORE OUT:</strong>
                                        <div id="modal-store-out-file" class="d-block mt-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="m-0">معلومات المخزون</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>DATE GATEPASS:</strong>
                                        <span id="modal-date-gatepass" class="d-block mt-1 p-2 bg-light rounded"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <a href="#" id="modal-edit-link" class="btn btn-warning">تعديل المادة</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// تعريف مسار التخزين للاستخدام في JavaScript
const storageUrl = "{{ asset('storage') }}";
// تعريف مسار تعديل المواد
const editMaterialUrl = "{{ route('admin.work-orders.materials.edit', '') }}";

// قاعدة بيانات مؤقتة لأكواد المواد والأوصاف - يمكن استبدالها بطلب AJAX للحصول على البيانات من السيرفر
const materialsData = {
    "M001": "أنابيب مياه بلاستيكية قطر 50 مم",
    "M002": "أنابيب صرف صحي 100 مم",
    "M003": "محابس مياه معدنية 3/4 بوصة",
    "M004": "وصلات بلاستيكية T شكل",
    "M005": "صمامات تحكم بالضغط",
    // يمكن إضافة المزيد من الأكواد والأوصاف
};

// تحديث وصف المادة عند تغيير كود المادة
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('code');
    const descriptionInput = document.getElementById('description');
    const autoDescriptionInput = document.getElementById('auto_description');
    const descAlert = document.getElementById('desc-alert');
    
    let typingTimer;
    const doneTypingInterval = 500; // انتظر 500 مللي ثانية بعد توقف الكتابة
    
    codeInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        const code = this.value.trim();
        
        if (code) {
            typingTimer = setTimeout(function() {
                // جلب الوصف من الخادم
                fetch(`/admin/work-orders/materials/get-description/${code}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.description) {
                            autoDescriptionInput.value = data.description;
                            descriptionInput.value = data.description; // نسخ الوصف إلى حقل الوصف الرئيسي
                            descAlert.style.display = 'none';
                        } else {
                            autoDescriptionInput.value = '';
                            descAlert.textContent = 'لم يتم العثور على وصف لهذا الكود';
                            descAlert.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        descAlert.textContent = 'حدث خطأ أثناء جلب الوصف';
                        descAlert.style.display = 'block';
                    });
            }, doneTypingInterval);
        } else {
            autoDescriptionInput.value = '';
            descriptionInput.value = '';
            descAlert.style.display = 'none';
        }
    });
});

// معالجة عرض تفاصيل المادة في النافذة المنبثقة
document.querySelectorAll('.view-material').forEach(button => {
    button.addEventListener('click', function() {
        const materialData = JSON.parse(this.getAttribute('data-material'));
        
        // تعبئة بيانات المادة في النافذة المنبثقة
        document.getElementById('modal-code').textContent = materialData.code;
        document.getElementById('modal-description').textContent = materialData.description;
        document.getElementById('modal-work-order').textContent = materialData.work_order ? 
            materialData.work_order.order_number + ' - ' + materialData.work_order.subscriber_name : 'غير محدد';
        document.getElementById('modal-line').textContent = materialData.line || '-';
        document.getElementById('modal-planned-quantity').textContent = materialData.planned_quantity;
        document.getElementById('modal-unit').textContent = materialData.unit;
        document.getElementById('modal-actual-quantity').textContent = materialData.actual_quantity || '-';
        document.getElementById('modal-difference').textContent = materialData.difference || '-';
        document.getElementById('modal-date-gatepass').textContent = materialData.date_gatepass || '-';
        
        // معالجة ملفات CHECK IN/OUT
        const checkInFileElement = document.getElementById('modal-check-in-file');
        checkInFileElement.innerHTML = '';
        if (materialData.check_in_file) {
            checkInFileElement.innerHTML = `
                <a href="${storageUrl}/${materialData.check_in_file}" target="_blank" class="btn btn-sm btn-success mt-2">
                    <i class="fas fa-file-download"></i> تحميل الملف
                </a>`;
        } else {
            checkInFileElement.innerHTML = '<span class="badge bg-light text-dark mt-2">لا يوجد ملف</span>';
        }
        
        // معالجة ملف GATE PASS
        const gatePassFileElement = document.getElementById('modal-gate-pass-file');
        gatePassFileElement.innerHTML = '';
        if (materialData.gate_pass_file) {
            gatePassFileElement.innerHTML = `
                <a href="${storageUrl}/${materialData.gate_pass_file}" target="_blank" class="btn btn-sm btn-success mt-2">
                    <i class="fas fa-file-download"></i> تحميل الملف
                </a>`;
        } else {
            gatePassFileElement.innerHTML = '<span class="badge bg-light text-dark mt-2">لا يوجد ملف</span>';
        }
        
        // معالجة ملف STORE IN
        const storeInFileElement = document.getElementById('modal-store-in-file');
        storeInFileElement.innerHTML = '';
        if (materialData.store_in_file) {
            storeInFileElement.innerHTML = `
                <a href="${storageUrl}/${materialData.store_in_file}" target="_blank" class="btn btn-sm btn-success mt-2">
                    <i class="fas fa-file-download"></i> تحميل الملف
                </a>`;
        } else {
            storeInFileElement.innerHTML = '<span class="badge bg-light text-dark mt-2">لا يوجد ملف</span>';
        }
        
        // معالجة ملف STORE OUT
        const storeOutFileElement = document.getElementById('modal-store-out-file');
        storeOutFileElement.innerHTML = '';
        if (materialData.store_out_file) {
            storeOutFileElement.innerHTML = `
                <a href="${storageUrl}/${materialData.store_out_file}" target="_blank" class="btn btn-sm btn-success mt-2">
                    <i class="fas fa-file-download"></i> تحميل الملف
                </a>`;
        } else {
            storeOutFileElement.innerHTML = '<span class="badge bg-light text-dark mt-2">لا يوجد ملف</span>';
        }
        
        // تعيين رابط التعديل
        document.getElementById('modal-edit-link').href = "{{ route('admin.work-orders.materials.edit', '') }}/" + materialData.id;
        
        // عرض النافذة المنبثقة
        const modal = new bootstrap.Modal(document.getElementById('materialDetailsModal'));
        modal.show();
    });
});

// إضافة التحقق من صحة النموذج
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
@endsection 