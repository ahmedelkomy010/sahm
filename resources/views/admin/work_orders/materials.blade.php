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
                                                <option value="908511032" {{ old('work_type') == '908511032' ? 'selected' : '' }}>-TFMR,PD,1.5MVA,33KX231/133V,170KVB</option>
                                                <option value="908511108" {{ old('work_type') == '908511108' ? 'selected' : '' }}>-TFMR,PF,300KVA,33KX231/133V,200KVB</option>
                                                <option value="908511126" {{ old('work_type') == '908511126' ? 'selected' : '' }}>-TFMR,PD,300KVA,33KX231/133V,200KVB,CU</option>
                                                <option value="908511128" {{ old('work_type') == '908511128' ? 'selected' : '' }}>-TFMR,PD,500KVA,33KX231/133V,200KVB,CU</option>
                                                <option value="908511130" {{ old('work_type') == '908511130' ? 'selected' : '' }}>-TFMR,PD,1MVA,33KX231/133V,200KVB</option>
                                                <option value="908512025" {{ old('work_type') == '908512025' ? 'selected' : '' }}>-TFMR,PF,300KVA,33KX400/230V,200KVBL,CU</option>
                                                <option value="908512033" {{ old('work_type') == '908512033' ? 'selected' : '' }}>-TFMR,PD,300KVA,33KX400/230V,200KVBL,CU</option> 
                                                <option value="908512041" {{ old('work_type') == '908512041' ? 'selected' : '' }}>-TFMR,PD,500KVA,33KX400/230V,200KVBL</option>
                                                <option value="908512049" {{ old('work_type') == '908512049' ? 'selected' : '' }}>-TFMR,PD,1MVA,33KX400/230V,200KVBL</option>
                                                <option value="908512057" {{ old('work_type') == '908512057' ? 'selected' : '' }}>-TFMR,PD,1.5MVA,33KX400/230V,200KVBL</option>
                                                <option value="908513017" {{ old('work_type') == '908513017' ? 'selected' : '' }}>-TFMR,PD,1MVA,33KX400/230V,200KVB,AL</option>
                                                <option value="908513018" {{ old('work_type') == '908513018' ? 'selected' : '' }}>-TFMR,PD,1.5MVA,33KX400/230V,200KVB,AL</option>
                                                <option value="908513020" {{ old('work_type') == '908513020' ? 'selected' : '' }}>-TFMR,PD,300KVA,33KX400/230V,200KVB,AL</option>
                                                <option value="908513021" {{ old('work_type') == '908513021' ? 'selected' : '' }}>-TFMR,PF,300KVA,33KX400/230V,200KVB,AL</option> 
                                                <option value="908513022" {{ old('work_type') == '908513022' ? 'selected' : '' }}>-TFMR,PD,500KVA,33KX400/230V,200KVB,AL</option>
                                                <option value="908514013" {{ old('work_type') == '908514013' ? 'selected' : '' }}>-TFMR,PD,1MVA,33KX400/230/133V,200KVB,AL</option>
                                                <option value="908514015" {{ old('work_type') == '908514015' ? 'selected' : '' }}>-TFMR,PD,300KVA,33KX400/230/133V,200KV,AL</option>
                                                <option value="908514016" {{ old('work_type') == '908514016' ? 'selected' : '' }}>-TFMR,PF,300KVA,33KX400/230/133V,200KV,AL</option>
                                                <option value="103151010" {{ old('work_type') == '103151010' ? 'selected' : '' }}>-CABINET:LV DISTRIBUTION C/W 300A MCCB PE</option>
                                                <option value="908514023" {{ old('work_type') == '908514023' ? 'selected' : '' }}>-TFMR,PD,1.5MVA,33KV/400-231-133,BIL 200KV,AL</option>
                                                <option value="103151010" {{ old('work_type') == '103151010' ? 'selected' : '' }}>-CABINET:LV DISTRIBUTION C/W 300A MCCB PE</option> 
                                                <option value="103151020" {{ old('work_type') == '103151020' ? 'selected' : '' }}>-CABINET:LV DISTRIBUTION C/W 500A MCCB PE</option>
                                                <option value="103151030" {{ old('work_type') == '103151030' ? 'selected' : '' }}>-CABINET:LV DISTRIBUTION C/W 800A MCCB PE</option>
                                                <option value="908313011" {{ old('work_type') == '908313011' ? 'selected' : '' }}>-CABINET,LV,300A,FOR PMT,1 MCCB OF 300A</option>
                                                <option value="908313012" {{ old('work_type') == '908313012' ? 'selected' : '' }}>-CABINET,LV,400A,FOR PMT,1 MCCB OF 400A</option>
                                                <option value="908313008" {{ old('work_type') == '908313008' ? 'selected' : '' }}>-CABINET,LV,800A,FOR PMT,4 MCCB OF 200A</option>
                                                <option value="104046010" {{ old('work_type') == '104046010' ? 'selected' : '' }}>-TIE WIRE 5MM DIA. ALUMINIUM ALLOY</option>
                                                <option value="104052580" {{ old('work_type') == '104052580' ? 'selected' : '' }}>-CLAMP:SUSPENSION HDG FOR 125MM2 ACSR,</option>
                                                <option value="104052610" {{ old('work_type') == '104052610' ? 'selected' : '' }}>-SUSPENSION CLAMP FOR  255MM2 ACSR</option>
                                                <option value="908121021" {{ old('work_type') == '908121021' ? 'selected' : '' }}>-TERM KIT,STR,36KV,3X185/35MM2,CU,O/D</option>
                                                <option value="908151004" {{ old('work_type') == '908151004' ? 'selected' : '' }}>-INSULATOR,POST,PORC,35KV,178MM D,825MM</option>
                                                <option value="908151005" {{ old('work_type') == '908151005' ? 'selected' : '' }}>-INSULATOR,SUSP,PORC,254MM,70KN, 292MM</option>
                                                <option value="908202012" {{ old('work_type') == '908202012' ? 'selected' : '' }}>-BOLT,MACH,GS,20MM DIA,60MM LG,HEX</option>
                                                <option value="908202025" {{ old('work_type') == '908202025' ? 'selected' : '' }}>-BOLT,MACH,GS,20MM DIA,400MM LG,HEX</option> 
                                                <option value="908202036" {{ old('work_type') == '908202036' ? 'selected' : '' }}>-NUT,EYE,GS,16MM BLT SZ,40MMWD X 50MM LG</option>
                                                <option value="908202037" {{ old('work_type') == '908202037' ? 'selected' : '' }}>-NUT,EYE,GS,20MM BLT SZ,40MM WD X 50MM LG</option>
                                                <option value="908202198" {{ old('work_type') == '908202198' ? 'selected' : '' }}>-BOLT,EYE,GS,16MM DIA,180MM LG</option>
                                                <option value="908202220" {{ old('work_type') == '908202220' ? 'selected' : '' }}>-BOLT,EYE,GS,16MM DIA,155MM LG,SHOULDER</option>
                                                <option value="908202221" {{ old('work_type') == '908202221' ? 'selected' : '' }}>-BOLT,EYE,GS,16MM DIA,235MM LG,SHOULDER</option>
                                                <option value="908202222" {{ old('work_type') == '908202222' ? 'selected' : '' }}>-BOLT,EYE,GS,16MM DIA,255MM LG,SHOULDER</option>
                                                <option value="908202223" {{ old('work_type') == '908202223' ? 'selected' : '' }}>-BOLT,EYE,GS,16MM DIA,275MM LG,SHOULDER</option> 
                                                <option value="908202224" {{ old('work_type') == '908202224' ? 'selected' : '' }}>-BOLT,EYE,GS,16MM DIA,285MM LG,SHOULDER</option>
                                                <option value="908202225" {{ old('work_type') == '908202225' ? 'selected' : '' }}>-BOLT,EYE,GS,16MM DIA,310MM LG,SHOULDER</option>
                                                <option value="908202226" {{ old('work_type') == '908202226' ? 'selected' : '' }}>-BOLT,EYE,GS,20MM DIA,260MM LG,SHOULDER</option>
                                                <option value="908202227" {{ old('work_type') == '908202227' ? 'selected' : '' }}>-BOLT,EYE,GS,20MM DIA,365MM LG,SHOULDER</option>
                                                <option value="908202042" {{ old('work_type') == '908202042' ? 'selected' : '' }}>-PLATE,DA,GS,100X700X12MM,22MM DIA HOLE</option>
                                                <option value="908202090" {{ old('work_type') == '908202090' ? 'selected' : '' }}>-CLIP,BARBED WIRE,SSTL</option>
                                                <option value="908202091" {{ old('work_type') == '908202091' ? 'selected' : '' }}>-N,3BOLTS-CLAMP,STRAIN,AA,11.34MMDIA,36K</option> 
                                                <option value="908202092" {{ old('work_type') == '908202092' ? 'selected' : '' }}>-N,3BOLTS-CLAMP,STRAIN,AA,17.35MM,45K</option>
                                                <option value="908202093" {{ old('work_type') == '908202093' ? 'selected' : '' }}>-CLAMP,STRAIN,AA,11.34MMDIA,36KN,STRAIGHT</option>
                                                <option value="908202094" {{ old('work_type') == '908202094' ? 'selected' : '' }}>-CLAMP,STRAIN,AA,14.31MMDIA,45KN,STRAIGHT</option>
                                                <option value="908202096" {{ old('work_type') == '908202096' ? 'selected' : '' }}>-CLAMP,SUSP,AA,11.34 MM DIA,36KN UTS</option>
                                                <option value="908202097" {{ old('work_type') == '908202097' ? 'selected' : '' }}>-CLAMP,SUSP,AA,17.35MM DIA,45KN UTS</option>
                                                <option value="908202098" {{ old('work_type') == '908202098' ? 'selected' : '' }}>-CLAMP,35-70SQMM CU,16MM DIA CWLD,GRD,BR</option>
                                                <option value="908202101" {{ old('work_type') == '908202101' ? 'selected' : '' }}>-CLAMP,TOP SERV MAST,GS,76X40X6X176/230MM</option>
                                                <option value="908202102" {{ old('work_type') == '908202102' ? 'selected' : '' }}>-CLAMP,SERV MAST,GS,76MMDIAX60MMWDX4MMTH</option>
                                                <option value="908202184" {{ old('work_type') == '908202184' ? 'selected' : '' }}>-COVER,H-TAP,HI-DENSITY PLST,40X47X140MM</option>
                                                <option value="908202186" {{ old('work_type') == '908202186' ? 'selected' : '' }}>-EXTENSION,CLEVIS,EYE,355 MM LG, 45KN</option>
                                                <option value="908202196" {{ old('work_type') == '908202196' ? 'selected' : '' }}>-CONNECTOR,PG,AL,QPLX QPLX,4X120SQMM</option>
                                                <option value="908202209" {{ old('work_type') == '908202209' ? 'selected' : '' }}>-CONNECTOR,PG,AL,MERLIN/AW,3-BOLTS</option>
                                                <option value="908351004" {{ old('work_type') == '908351004' ? 'selected' : '' }}>-ARRESTER,SURGE,36KV,1320MM,10KA OPR DUTY</option> 
                                                <option value="116423870" {{ old('work_type') == '116423870' ? 'selected' : '' }}>-TERM. OUTDOOR 33 KV 3X120 MM2</option>
                                                <option value="908201001" {{ old('work_type') == '908201001' ? 'selected' : '' }}>-POLE,STL,DIST,LV,10MLG,TPROCT,OC10</option>
                                                <option value="908201003" {{ old('work_type') == '908201003' ? 'selected' : '' }}>-POLE,STL TBLR,DIST,13MLG,TPR OCT,OC13S</option>
                                                <option value="908201006" {{ old('work_type') == '908201006' ? 'selected' : '' }}>-POLE,STL,DIST,MV,15MLG,TPROCT,OC15SD</option>
                                                <option value="908201007" {{ old('work_type') == '908201007' ? 'selected' : '' }}>-POLE,STL,DIST,LV,10MLG,TPROCT,OC10SFS</option>
                                                <option value="908201009" {{ old('work_type') == '908201009' ? 'selected' : '' }}>-POLE,STLوTBLR,DIST,13MLG,TPR OCT,OC13SFS</option>
                                                <option value="908201011" {{ old('work_type') == '908201011' ? 'selected' : '' }}>-POLE,STL,DIST,MV,15MLG,TPROCT,OC15SFS</option> 
                                                <option value="115110760" {{ old('work_type') == '115110760' ? 'selected' : '' }}>-CONN,ELEC,TERM,ACSR,#RYCS288RL</option>
                                                <!-- 251 -->
                                                <option value="116425500" {{ old('work_type') == '116425500' ? 'selected' : '' }}>-KIT,TERMINATION SUPPORT,MOUNTING BRACKET 33KV</option>
                                                <option value="118053060" {{ old('work_type') == '118053060' ? 'selected' : '' }}>-FUSE:LOW VOLTAGE,315 A,415 VAC,80 KA,BOL</option>
                                                <option value="908121058" {{ old('work_type') == '908121058' ? 'selected' : '' }}>-JOINT KIT,STR,36KV,3X240/35MM2,CU,AR</option>
                                                <option value="908121067" {{ old('work_type') == '908121067' ? 'selected' : '' }}>-TERM KIT,RT,36KV,3X240/35MM2,CU</option>
                                                <option value="908121137" {{ old('work_type') == '908121137' ? 'selected' : '' }}>-TERM KIT,STR,PM,36KV,3X240/35MM2,CU,AR/I</option>
                                                <option value="908122005" {{ old('work_type') == '908122005' ? 'selected' : '' }}>-CONNECTOR,LUG,300MM2 CU,(1)M16 BLT HL</option> 
                                                <option value="908122007" {{ old('work_type') == '908122007' ? 'selected' : '' }}>-CONNECTOR,LUG,300MM2 AL,(1)M17 BLT HL</option>
                                                <option value="908122014" {{ old('work_type') == '908122014' ? 'selected' : '' }}>-CONNECTOR,SLV,70MM2 AL,TIN PLT AL</option>
                                                <option value="908122015" {{ old('work_type') == '908122015' ? 'selected' : '' }}>-CONNECTOR,SLV,185MM2 AL,TIN PLT AL</option>
                                                <option value="908122029" {{ old('work_type') == '908122029' ? 'selected' : '' }}>-CONNECTOR,SLV,120MM2 AL-70MM2 CU,BIMTLC</option>
                                                <option value="908122037" {{ old('work_type') == '908122037' ? 'selected' : '' }}>-CONNECTOR,LUG,240SQMM CU,17MM DIA HL</option>
                                                <option value="908122039" {{ old('work_type') == '908122039' ? 'selected' : '' }}>-CONNECTOR,LUG,185SQMM CU,(1)M16 BLT HL</option>
                                                <option value="908122041" {{ old('work_type') == '908122041' ? 'selected' : '' }}>-CONNECTOR,LUG,15KV,500MM2,AL,17MM HL</option>
                                                <option value="908122043" {{ old('work_type') == '908122043' ? 'selected' : '' }}>-CONNECTOR,SLV,BM,500-185MM2</option>
                                                <option value="908202034" {{ old('work_type') == '908202034' ? 'selected' : '' }}>-CONNECTOR,TERM,PLUG,AL,170.5SQMM ACSR/AW</option>
                                                <option value="908202059" {{ old('work_type') == '908202059' ? 'selected' : '' }}>-SLEEVE,REPAIR,125.1SQMMACSR,AL,275MM LG</option>
                                                <option value="908202080" {{ old('work_type') == '908202080' ? 'selected' : '' }}>-CROSSARM,METAL,GS,L100X100X12X2400MM</option>
                                                <option value="908202189" {{ old('work_type') == '908202189' ? 'selected' : '' }}>-CONNECTOR,GRNDG,C,CU,100-125SQMMCU</option>
                                                <option value="908202235" {{ old('work_type') == '908202235' ? 'selected' : '' }}>-CONNECTOR,PG,AL,170SQMM,67.4SQMM,3-BOLTS</option> 
                                                <option value="908381000" {{ old('work_type') == '908381000' ? 'selected' : '' }}>-INDICATOR,FAULT,AUTOMATIC RESET,25/50A</option>
                                                <option value="116613870" {{ old('work_type') == '116613870' ? 'selected' : '' }}>-CABLE WARNING TAPE</option>
                                                <option value="908020201" {{ old('work_type') == '908020201' ? 'selected' : '' }}>-LOCK,PAD,OPERATION,6MM,40MM,17.2MM,BRS</option>
                                                <option value="104033840" {{ old('work_type') == '104033840' ? 'selected' : '' }}>-ANGLE,TOWER,40 MM,1500 MM,6 MM,STL</option>
                                                <option value="104033850" {{ old('work_type') == '104033850' ? 'selected' : '' }}>-ANGLE,TOWER,40MM,1500 MM,6 MM,STL</option>
                                                <option value="104033870" {{ old('work_type') == '104033870' ? 'selected' : '' }}>-BRACE,CROSSARM,HDG STL</option>
                                                <option value="104044430" {{ old('work_type') == '104044430' ? 'selected' : '' }}>-SHACKLE:PIN,55 MM INSIDE WD,106 MM INSID</option> 
                                                <option value="104256530" {{ old('work_type') == '104256530' ? 'selected' : '' }}>-ARRESTER,SURGE,36KV,170KV BIL,DIST</option>
                                                <option value="102015040" {{ old('work_type') == '102015040' ? 'selected' : '' }}>-TFMR,DIST,PD,500KVA,13.8K V AC,400 V AC</option>
                                                <option value="110131080" {{ old('work_type') == '110131080' ? 'selected' : '' }}>-CONDUCTOR:ELECTRICAL,ACSR,125.1 SQMM,6/1</option>
                                                <option value="110132550" {{ old('work_type') == '110132550' ? 'selected' : '' }}>-CONDUCTOR:ELECTRICAL,ACSR,255 SQMM,18/1,</option>
                                                <option value="115112550" {{ old('work_type') == '115112550' ? 'selected' : '' }}>-CONN,ELEC,SLV,TENSION,AA BODY,#YDS319RYD</option>
                                                <option value="115411800" {{ old('work_type') == '115411800' ? 'selected' : '' }}>-CABLE,PWR,600V/1KV,CU,1C,185MM2,XLPE</option>
                                                <option value="908111003" {{ old('work_type') == '908111003' ? 'selected' : '' }}>-CABLE,PWR,36KV,CU,3C,185/35 MV,,XPLE,ARM</option> 
                                                <option value="908113008" {{ old('work_type') == '908113008' ? 'selected' : '' }}>-CABLE,PWR,36KV,CU,1C,50/16MM2,XPLE,UARM</option>
                                                <option value="908113009" {{ old('work_type') == '908113009' ? 'selected' : '' }}>-TERM KIT,STR,36KV,1X50/16MM2,CU,O/D</option>
                                                <option value="908121028" {{ old('work_type') == '908121028' ? 'selected' : '' }}>-TERM KIT,RT,36KV,1X50/16MM2,CU</option>
                                                <option value="908121030" {{ old('work_type') == '908121030' ? 'selected' : '' }}>-TERM KIT,RT,36KV,3X185/35MM2,CU</option>
                                                <option value="908121033" {{ old('work_type') == '908121033' ? 'selected' : '' }}>-TERMINATION KIT:CABLE,HEAT/COLD SHRINK,I</option>
                                                <option value="908121059" {{ old('work_type') == '908121059' ? 'selected' : '' }}>-SPLICE KIT:CABLE,HEAT/COLD SHRINK,STRAIG</option>
                                                <option value="908121073" {{ old('work_type') == '908121073' ? 'selected' : '' }}>-TERM KIT,STR,36KV,3X240/35MM2,CU,I/D</option>
                                                <option value="908151003" {{ old('work_type') == '908151003' ? 'selected' : '' }}>-BOOT,RT ANG,36KV,3X240/35MM2,XLPE</option>
                                                <option value="908151006" {{ old('work_type') == '908151006' ? 'selected' : '' }}>-INSULATOR,POST,PORC,35KV,185MM D, 1320MM</option>
                                                <option value="908202055" {{ old('work_type') == '908202055' ? 'selected' : '' }}>-INSULATOR,STAND-OFF,PORC,35KV,195MM,660</option> 
                                                <option value="908202060" {{ old('work_type') == '908202060' ? 'selected' : '' }}>-CONNECTOR,SLV,120X50SQMM AL,AL W/NYL JKT</option>
                                                <option value="908202062" {{ old('work_type') == '908202062' ? 'selected' : '' }}>-CONNECTOR,SLV,120SQMM ACSR/AW,AL,45KN</option>
                                                <option value="908202063" {{ old('work_type') == '908202063' ? 'selected' : '' }}>-CONN,ELEC,SLV,TENSION</option>
                                                <option value="908202064" {{ old('work_type') == '908202064' ? 'selected' : '' }}>-CONNECTOR,SLV,120SQMM AL,AL W/NYL JKT</option>
                                                <!-- 300 -->
                                                <option value="908202066" {{ old('work_type') == '908202066' ? 'selected' : '' }}>-CONNECTOR,SLV,50SQMM,AL W/ NYL JKT</option>
                                                <option value="908202081" {{ old('work_type') == '908202081' ? 'selected' : '' }}>-CHANNEL,FUSE CUTOUT,C125X65X65X6X2400MM</option>
                                                <option value="908202082" {{ old('work_type') == '908202082' ? 'selected' : '' }}>-CHANNEL,TFMR MTG,GS,150X75X75X6.5X2400MM</option>
                                                <option value="908202192" {{ old('work_type') == '908202192' ? 'selected' : '' }}>-GUARD,GRD WIRE,PVC,12.7MM DIAX2440MM LG</option>
                                                <option value="908202193" {{ old('work_type') == '908202193' ? 'selected' : '' }}>-GUARD,GUY,PVC-YELLOW,51MMWDX2440MMLG</option>
                                                <option value="908202194" {{ old('work_type') == '908202194' ? 'selected' : '' }}>-HEAD,SERV ENT,AL,SLIP-ON W/2BLT,76MM OD</option>
                                                <option value="908202200" {{ old('work_type') == '908202200' ? 'selected' : '' }}>-CONNECTOR,PG,AL,QUAIL/AW,2-BOLTS</option>
                                                <option value="908202201" {{ old('work_type') == '908202201' ? 'selected' : '' }}>-CLAMP,SUSP,AA,14.31 MM DIA,45KN MIN UTS</option>
                                                <option value="908202210" {{ old('work_type') == '908202210' ? 'selected' : '' }}>-CONNECTOR,PG,AL,120SQMM,50SQMM,COMP</option> 
                                                <option value="908202228" {{ old('work_type') == '908202228' ? 'selected' : '' }}>-BRACE,CROSSARM,GS,BOTH END CLIPPED</option>
                                                <option value="908202236" {{ old('work_type') == '908202236' ? 'selected' : '' }}>-CONNECTOR,PG,70SQMM CU,70SQMM AL,2-BOLT</option>
                                                <option value="908202237" {{ old('work_type') == '908202237' ? 'selected' : '' }}>-CONNECTOR,PG,70SQMM CU,120SQMM AL,2-BOLT</option>
                                                <option value="908301009" {{ old('work_type') == '908301009' ? 'selected' : '' }}>-SWITCH,OHD,VERTICAL,33KV,3P,400A,825MM</option>
                                                <option value="908301010" {{ old('work_type') == '908301009' ? 'selected' : '' }}>-SWITCH,DISC,OVRHD,33KV,3 P,60HZ, 400A</option>
                                                <option value="908301015" {{ old('work_type') == '908301015' ? 'selected' : '' }}>-SWITCH,OHD,VERT,3P,33KV,600A, 1320MM</option>
                                                <option value="908301016" {{ old('work_type') == '908301016' ? 'selected' : '' }}>-SWITCH,OHD,HORIZONTAL,3P,33KV,600A,FOR</option> 
                                                <option value="908511025" {{ old('work_type') == '908511025' ? 'selected' : '' }}>-TFMR,PD,300KVA,33KX400/231V,170KVB</option>
                                                <option value="908511027" {{ old('work_type') == '908511027' ? 'selected' : '' }}>-TFMR,PD,500KVA,33KX400/231V,170KVB</option>
                                                <option value="908511031" {{ old('work_type') == '908511031' ? 'selected' : '' }}>-TFMR,PD,1.5MVA,33KX400/231V,170KVB</option>
                                                <option value="908511129" {{ old('work_type') == '908511129' ? 'selected' : '' }}>-TFMR,PD,1MVA,33KX400/231V,200KVB</option>
                                                <option value="908421008" {{ old('work_type') == '908421008' ? 'selected' : '' }}>-LOCK:EQUIPMENT,METER SEAL,PLASTIC/STL,19</option>
                                                <option value="908421009" {{ old('work_type') == '908421009' ? 'selected' : '' }}>-BLOCK,TERMINAL,150A,PA66,55MMWX85MMLX85M</option>
                                                <option value="908421010" {{ old('work_type') == '908421010' ? 'selected' : '' }}>-BLOCK,200A,PA66,115MMWX150MMLX95MMH,SING</option> 
                                                <option value="908421011" {{ old('work_type') == '908421011' ? 'selected' : '' }}>-BLOCK,200A,PA66,115MMWX150MMLX95MMH,QUAD</option>
                                                <option value="908421012" {{ old('work_type') == '908421012' ? 'selected' : '' }}>-BLOCK,400A,FRP+CU,230MMWX260MMLX130MMH,Q</option>
                                                <option value="908421013" {{ old('work_type') == '908421013' ? 'selected' : '' }}>-BLOCK,200A,PA66,115MMWX150MMLX95MMH,DOUB</option>
                                                <option value="908421014" {{ old('work_type') == '908421014' ? 'selected' : '' }}>-BLOCK,300A,PA66,170MMWX125MMLGX90MMH,DOU</option>
                                                <option value="908421015" {{ old('work_type') == '908421015' ? 'selected' : '' }}>-COVER,FIBERGLASS,965X270X4MMTH,SINGLEMET</option>
                                                <option value="908421016" {{ old('work_type') == '908421016' ? 'selected' : '' }}>-COVER,FIBERGLASS,965X965X4,QUADRUPLEMETE</option>
                                                <option value="908421017" {{ old('work_type') == '908421017' ? 'selected' : '' }}>-COVER,FIBERGLASS,560X965X4MMTH,DOUBLE ME</option>
                                                <option value="908421018" {{ old('work_type') == '908421018' ? 'selected' : '' }}>-COVER,FIBERGLASS,278X140X3MM,COMMONMETER</option>
                                                <option value="908421019" {{ old('work_type') == '908421019' ? 'selected' : '' }}>-MOUNT,HDG-STL,370X215X1.5MM,METERANFMCCB</option>
                                                <option value="908421020" {{ old('work_type') == '908421020' ? 'selected' : '' }}>-MOUNT,STL,92X20X2MM,CABLEFIXINGFACILITY</option>
                                                <option value="908421021" {{ old('work_type') == '908421021' ? 'selected' : '' }}>-MOUNT,GALVANIZEDCOIL,300X195X1.5MM,QUADR</option>
                                                <option value="908421022" {{ old('work_type') == '908421022' ? 'selected' : '' }}>-MOUNT,GALVANIZEDCOIL,320X200X1.5MM</option>
                                                <option value="908421023" {{ old('work_type') == '908421023' ? 'selected' : '' }}>-LUG,TINNEDCOPPER,1XM12HOLE,35SQMM,TERMIN</option>
                                                <option value="100008292" {{ old('work_type') == '100008292' ? 'selected' : '' }}>-SPOUT,BUSBAR & CABLE,ER,#GCE8003899R0101</option>
                                                <option value="100010763" {{ old('work_type') == '100010763' ? 'selected' : '' }}>-FUSE:LOW VOLTAGE,1/4 A,250 VAC,FAST BLOW</option> 
                                                <option value="101021180" {{ old('work_type') == '101021180' ? 'selected' : '' }}>-METER,ESERV,ANL,380/220/127V,50(200),3 P</option>
                                                <option value="101811910" {{ old('work_type') == '101811910' ? 'selected' : '' }}>-SET,TERMINAL BLOCK 185 SQMM AL/CU,#4ACB1</option>
                                                <option value="101820230" {{ old('work_type') == '101820230' ? 'selected' : '' }}>-BOX,M,1 CUST,M,220/380 VAC VOLT</option>
                                                <option value="102012150" {{ old('work_type') == '102012150' ? 'selected' : '' }}>-TFMR,DIST,200KVA,13800/4160 V AC,#T3SP20</option>
                                                <option value="102361110" {{ old('work_type') == '102361110' ? 'selected' : '' }}>-PLATFORM (PREFABRICATED)FOR TRANSFORMER</option>
                                                <option value="103113420" {{ old('work_type') == '103113420' ? 'selected' : '' }}>-SWITCHGEAR:METAL ENCLOSED,RMU, O/D, (1)</option>
                                                <option value="103116400" {{ old('work_type') == '103116400' ? 'selected' : '' }}>-METERING UNIT,13.8KV V,3 P,400A</option> 
                                                <option value="103116420" {{ old('work_type') == '103116420' ? 'selected' : '' }}>-KIT,RING MAIN UNIT 13.8 KV,BUSBAR BAND J</option>
                                                <option value="103117020" {{ old('work_type') == '103117020' ? 'selected' : '' }}>-SWITCHGEAR:METAL ENCLOSED,RMU, O/D,</option>
                                                <option value="103117030" {{ old('work_type') == '103117020' ? 'selected' : '' }}>-SWITCHGEAR:METAL ENCLOSED,RMU, O/D,OIL,1</option>
                                                <option value="103117150" {{ old('work_type') == '103117150' ? 'selected' : '' }}>-KIT,BUSBAR END CAP,13.8 KV LUCY RMU</option>
                                                <option value="103117160" {{ old('work_type') == '103117160' ? 'selected' : '' }}>-KIT,RING MAIN UNIT 13.8 KV,BUSBAR COUPLI</option>
                                                <option value="103209010" {{ old('work_type') == '103209010' ? 'selected' : '' }}>-TFMR,PKG SUB,1MVA KVA,13.8KV,231Y/133 V</option>
                                                <option value="103209040" {{ old('work_type') == '103209040' ? 'selected' : '' }}>-TFMR,PKG SUB,1MVA KVA,13.8KV,400/231V</option> 
                                                <option value="104052250" {{ old('work_type') == '104052250' ? 'selected' : '' }}>-CLAMP,STRAIN,AL,40 KN</option>
                                                <option value="104103260" {{ old('work_type') == '104103260' ? 'selected' : '' }}>-INSULATOR,POST,BROWN, CHOCOLATE GLAZE,#R</option>
                                                <option value="104112080" {{ old('work_type') == '104112080' ? 'selected' : '' }}>-PIN,GROUND WIRE,OVERHEAD LINE,19 MM DIA</option>
                                                <option value="104186520" {{ old('work_type') == '104186520' ? 'selected' : '' }}>-SWITCH,DISC,OVRHD,60HZ FREQ,400A,#D2SWIT</option>
                                                <option value="110110540" {{ old('work_type') == '110110540' ? 'selected' : '' }}>-WIRE:GROUNDING,CU,50 SQMM,1</option>
                                                <option value="110110950" {{ old('work_type') == '110110950' ? 'selected' : '' }}>-CABLE,ELEC,CU,PVC,95SQMM,1,19 STRAND,BLK</option>
                                                <option value="115113020" {{ old('work_type') == '115113020' ? 'selected' : '' }}>-CONN,ELEC,TERM,CU,300 SQMM STR CNDCTR</option>
                                                <option value="115210950" {{ old('work_type') == '115210950' ? 'selected' : '' }}>-CONN,ELEC,TERM,CU,95SQMM CNDCTR,#T9512</option>
                                                <!-- 360 -->
                                                <option value="115216310" {{ old('work_type') == '115216310' ? 'selected' : '' }}>-CONN,ELEC,TERM,AL/CU,630SQMM CNDCTR,#T63</option>
                                                <option value="115251850" {{ old('work_type') == '115251850' ? 'selected' : '' }}>-CONN,ELEC,TERM,AL/CU,185SQMM CNDCTR,#AL1</option>
                                                <option value="115253030" {{ old('work_type') == '115253030' ? 'selected' : '' }}>-CONN,ELEC,TERM,AL,#BL3004 BIMETAL </option>
                                                <option value="115431210" {{ old('work_type') == '115431210' ? 'selected' : '' }}>-CONNECTOR:PARALLEL GROOVE,2X2 BOLTED,300</option>
                                                <option value="116202630" {{ old('work_type') == '116202630' ? 'selected' : '' }}>-TUBING,SHRINK,POLF,#MWTM3512YEJ502021816</option>
                                                <option value="116410660" {{ old('work_type') == '116410660' ? 'selected' : '' }}>-CAP,CABLE END,95 TO 115 MM DIA,33 KV,POL</option>
                                                <option value="116410900" {{ old('work_type') == '116410900' ? 'selected' : '' }}>-BOOT,INSUL,CABLE-BUSHING,ANG,33KV,#ESIB6</option> 
                                                <option value="116411700" {{ old('work_type') == '116411700' ? 'selected' : '' }}>-SLEEVE,CABLE INSULATION REPAIR, WRAP-ARO</option>
                                                <option value="116412500" {{ old('work_type') == '116412500' ? 'selected' : '' }}>-SEALING END:INDOOR FOR 13.8KV,1X150MM2 X</option>
                                                <option value="116418950" {{ old('work_type') == '116418950' ? 'selected' : '' }}>-TUBING,SHRINK,POLF,154 MM ID,1M LG,33KV</option>
                                                <option value="116425890" {{ old('work_type') == '116425890' ? 'selected' : '' }}>-JOINT STRAIGHT TROUGHT 4X500 MM2,ALUM.</option>
                                                <option value="116425900" {{ old('work_type') == '116425900' ? 'selected' : '' }}>-SPLICE KIT:CABLE,HEAT SHRINK,DIRECT BURI</option>
                                                <option value="116426130" {{ old('work_type') == '116426130' ? 'selected' : '' }}>-TERMINAL,INDR,HEAT SHRINKABLE,2 TO 35 KV</option>
                                                <option value="116426790" {{ old('work_type') == '116426790' ? 'selected' : '' }}>-TERMINATION KIT:CABLE,HEATSHRINK,O/D,15</option> 
                                                <option value="116428600" {{ old('work_type') == '116428600' ? 'selected' : '' }}>-GLAND,CBL,50 SQMM, 3 CORE CBL,#EAKT1605</option>
                                                <option value="118051630" {{ old('work_type') == '118051630' ? 'selected' : '' }}>-FUSE LINK:HRC 160 AMPS 500V,WEDGE SIZE</option>
                                                <option value="118052010" {{ old('work_type') == '118052010' ? 'selected' : '' }}>-FUSE:LOW VOLTAGE,200 A,415 VAC,SLOW BLOW</option>
                                                <option value="118053100" {{ old('work_type') == '118053100' ? 'selected' : '' }}>-FUSE:LOW VOLTAGE,300 A,500 V AC,120 KA,K</option>
                                                <option value="118054020" {{ old('work_type') == '118054020' ? 'selected' : '' }}>-FUSE:LOW VOLTAGE,400 A,415 VAC,80 KA,BOL</option>
                                                <option value="119411010" {{ old('work_type') == '119411010' ? 'selected' : '' }}>-BREAKER,CRCT,MC,400 VAC 60 HZ,3 POLE,#F1</option>
                                                <option value="119411510" {{ old('work_type') == '119411510' ? 'selected' : '' }}>-BREAKER,CRCT,MC,480 VAC, 50 TO 60 HZ,#GH</option> 
                                                <option value="119414760" {{ old('work_type') == '119414760' ? 'selected' : '' }}>-BREAKER,CRCT,MC,220 TO 380 V 60 HZ,#S160</option>
                                                <option value="119416070" {{ old('work_type') == '119416070' ? 'selected' : '' }}>-BREAKER,CIRCUIT, 500AMP 600V 50/60HZ</option>
                                                <option value="119421420" {{ old('work_type') == '119421420' ? 'selected' : '' }}>-BREAKER,CRCT,MC,400/440 VDC,2 POLE,2 A</option>
                                                <option value="905010041" {{ old('work_type') == '905010041' ? 'selected' : '' }}>-GLOVES,ELEC,1KV,CL 1,RBR,9,360 MM LG,YEL</option>
                                                <option value="905010056" {{ old('work_type') == '905010056' ? 'selected' : '' }}>-HELMET:SAFETY,HIGH VOLTAGE PROTECTION,WH</option>
                                                <option value="905010064" {{ old('work_type') == '905010064' ? 'selected' : '' }}>-TAPE,WARNING,70 MM WD X 200 M LG</option>
                                                <option value="905010065" {{ old('work_type') == '905010065' ? 'selected' : '' }}>-CONE,TRAFFIC,SAFETY,30 SQCM BASE X 50 CM</option>
                                                <option value="905020040" {{ old('work_type') == '905020040' ? 'selected' : '' }}>-MASK:PROTECTION,WELDING MIST,HALF FACE</option>
                                                <option value="905020070" {{ old('work_type') == '905020070' ? 'selected' : '' }}>-GLOVES:WORKING,HEAVY DUTY,MEDIUM,HIGH PE</option>
                                                <option value="905020071" {{ old('work_type') == '905020071' ? 'selected' : '' }}>-GLOVES:WORKING,HEAVY DUTY,LARGE,HIGH PER</option>
                                                <option value="905020076" {{ old('work_type') == '905020076' ? 'selected' : '' }}>-GLOVES:WORKING,HEAVY DUTY,8,HIGH PERFORM</option>
                                                <option value="905020077" {{ old('work_type') == '905020077' ? 'selected' : '' }}>-GLOVES:WORKING,HEAVY DUTY,9,HIGH PERFORM</option>
                                                <option value="905020085" {{ old('work_type') == '905020085' ? 'selected' : '' }}>-GLOVES, NON-CHEMICAL, MEDIUM, PVC AND SI</option>
                                                <option value="905020086" {{ old('work_type') == '905020086' ? 'selected' : '' }}>-GLOVES, NON-CHEMICAL, LARGE, PVC AND SIL</option>
                                                <option value="905030121" {{ old('work_type') == '905030121' ? 'selected' : '' }}>-PROTECTOR,HEARING,FOAM</option> 
                                                <option value="908020202" {{ old('work_type') == '908020202' ? 'selected' : '' }}>-LOCK,PAD,SAFETY,6MM,40MM,17.2MM,BRS/RED</option>
                                                <option value="908021001" {{ old('work_type') == '908021001' ? 'selected' : '' }}>-OIL,INSUL,TRANSFORMER,0.19 SPGR,30KV</option>
                                                <option value="908111010" {{ old('work_type') == '908111010' ? 'selected' : '' }}>-CABLE,CNTRL,750V,CU,12CORE,2.5SQMM,PVC</option>
                                                <option value="908111011" {{ old('work_type') == '908111011' ? 'selected' : '' }}>-CABLE,CNTRL,750V,CU,2CORE,2.5SQMM,PVC</option>
                                                <!-- 400 -->
                                                <option value="908113003" {{ old('work_type') == '908113003' ? 'selected' : '' }}>-CABLE,PWR,15KV,AL,3C,300/35MM2,XPLE,ARM</option>
                                                <option value="908113004" {{ old('work_type') == '908113004' ? 'selected' : '' }}>-CABLE,PWR,15KV,AL,3C,70/16MM2,XPLE,ARM</option>
                                                <option value="908114002" {{ old('work_type') == '908114002' ? 'selected' : '' }}>-CABLE,PWR,15K,CU,3X300/35SQMM,XLPE/MDPE</option> 
                                                <option value="908121006" {{ old('work_type') == '908121006' ? 'selected' : '' }}>-JOINT KIT,STR,15KV,3X300/35MM2,AL,AR</option>
                                                <option value="908121007" {{ old('work_type') == '908121007' ? 'selected' : '' }}>-JOINT KIT,STR,15KV,3X70/16MM2,AL,AR</option>
                                                <option value="908121011" {{ old('work_type') == '908121011' ? 'selected' : '' }}>-TERM KIT,STR,15KV,3X185/35MM2,CU.</option>
                                                <option value="908121012" {{ old('work_type') == '908121012' ? 'selected' : '' }}>-TERM KIT,STR,15KV,3X300/35MM2,CU.</option>
                                                <option value="908121013" {{ old('work_type') == '908121013' ? 'selected' : '' }}>-TERM KIT,STR,15KV,3X300/35MM2,AL</option>
                                                <option value="908121015" {{ old('work_type') == '908121015' ? 'selected' : '' }}>-JOINT KIT,TRANS,15KV,3X185/35MM2,AL</option>
                                                <option value="908121020" {{ old('work_type') == '908121020' ? 'selected' : '' }}>-CAP,CBL END,15KV,1X50/16MM2,CU,XLPE,UARM</option> 
                                                <option value="908121022" {{ old('work_type') == '908121022' ? 'selected' : '' }}>-CAP,CBL END,15KV,3X300/35MM2,CU/AL,XLPE</option>
                                                <option value="908121026" {{ old('work_type') == '908121026' ? 'selected' : '' }}>-SLEEVE,REPAIR,3X185-240/35MM2,XLPE,ARM</option>
                                                <option value="908121027" {{ old('work_type') == '908121027' ? 'selected' : '' }}>-CAP,CBL END,3X185-500/35MM2,XLPE,ARM</option>
                                                <option value="908121029" {{ old('work_type') == '908121029' ? 'selected' : '' }}>-SLEEVE,REPAIR,1KV,4X70-185MM2,1500MM</option>
                                                <option value="908121032" {{ old('work_type') == '908121032' ? 'selected' : '' }}>-SLEEVE,REPAIR,15KV,1X50MM2,1500MM LG</option>
                                                <option value="908121034" {{ old('work_type') == '908121034' ? 'selected' : '' }}>-SLEEVE,REPAIR,15KV,3X300/35MM2,1500MM LG</option>
                                                <option value="908121035" {{ old('work_type') == '908121035' ? 'selected' : '' }}>-SLEEVE,REPAIR,15KV,3X70/16-3X185/35MM2</option>
                                                <option value="908121036" {{ old('work_type') == '908121036' ? 'selected' : '' }}>-TERM KIT,STR,36KV,1X50/16MM2,CU,I/D</option>
                                                <option value="908121037" {{ old('work_type') == '908121037' ? 'selected' : '' }}>-CONN,ELEC,EL,DB</option>
                                                <option value="908121040" {{ old('work_type') == '908121040' ? 'selected' : '' }}>-ELBOW,EC,15KV,400A,3X70/16MM2</option>
                                                <option value="908121047" {{ old('work_type') == '908121047' ? 'selected' : '' }}>-TERM KIT,STR,15KV,3X185/35MM2,CU</option>
                                                <option value="908121050" {{ old('work_type') == '908121050' ? 'selected' : '' }}>-TERM KIT,STR,15KV,3X300/35MM2,CU</option>
                                                <option value="908121054" {{ old('work_type') == '908121054' ? 'selected' : '' }}>-TERM KIT,RT,15KV,3X300/35MM2,AL</option>
                                                <option value="908121069" {{ old('work_type') == '908121069' ? 'selected' : '' }}>-BOOT,RT ANG,15KV,3X70/16MM2,AL,XLPE</option>
                                                <option value="908121075" {{ old('work_type') == '908121075' ? 'selected' : '' }}>-BOOT,RT ANG,36KV,1X50/16MM2,XLPE</option> 
                                                <option value="908121081" {{ old('work_type') == '908121081' ? 'selected' : '' }}>-BOOT,STR,36KV,3X240/35MM2,CU,XLPE</option>
                                                <option value="908121083" {{ old('work_type') == '908121083' ? 'selected' : '' }}>-BOOT,STR,36KV,1X50/16MM2,CU,XLPE</option>
                                                <option value="908121085" {{ old('work_type') == '908121085' ? 'selected' : '' }}>-SPLICE KIT,LG,STR,36KV,3X185/35SQMM</option>
                                                <option value="908121102" {{ old('work_type') == '908121102' ? 'selected' : '' }}>-JOINT KIT,STR,PM,15KV,3X185/35MM2,CU</option>
                                                <option value="908121103" {{ old('work_type') == '908121103' ? 'selected' : '' }}>-JOINT KIT,STR,PM,15KV,3X300/35MM2,CU,AR</option>
                                                <option value="908121106" {{ old('work_type') == '908121106' ? 'selected' : '' }}>-TERM KIT,STR,PM,1KV,4X70MM2,AL</option>
                                                <option value="908121107" {{ old('work_type') == '908121107' ? 'selected' : '' }}>-TERM KIT,STR,PM,1KV,4X185MM2,AL</option> 
                                                <option value="908121109" {{ old('work_type') == '908121109' ? 'selected' : '' }}>-TERM KIT,STR,PM,15KV,3X185/35MM2,CU,O/D</option>
                                                <option value="908121110" {{ old('work_type') == '908121110' ? 'selected' : '' }}>-TERM KIT,STR,PM,15KV,3X300/35MM2,CU,AR/O</option>
                                                <option value="908121114" {{ old('work_type') == '908121114' ? 'selected' : '' }}>-JOINT KIT,TRANS,PM,15KV,3X185-300,CU</option>
                                                <option value="908121121" {{ old('work_type') == '908121121' ? 'selected' : '' }}>-TERM KIT,ST,PM,15KV,50/16MM2,CU,I/D</option>
                                                <option value="908121122" {{ old('work_type') == '908121122' ? 'selected' : '' }}>-TERM KIT,RT,PM,15KV,50/16MM2,CU,I/D</option>
                                                <option value="908121126" {{ old('work_type') == '908121126' ? 'selected' : '' }}>-TERM KIT,STR,PM,15KV,3X300/35MM2,CU,AR/I</option>
                                                <option value="908121127" {{ old('work_type') == '908121127' ? 'selected' : '' }}>-TERM KIT,RT,PM,15KV,3X300/35MM2,CU,AR/I</option> 
                                                <option value="908121130" {{ old('work_type') == '908121130' ? 'selected' : '' }}>-JOINT KIT,STR,PM,36KV,500/35MM2,CU</option>
                                                <option value="908121131" {{ old('work_type') == '908121131' ? 'selected' : '' }}>-JOINT KIT,STR,PM,36KV,3X240/35MM2,CU,AR</option>
                                                <option value="908121132" {{ old('work_type') == '908121132' ? 'selected' : '' }}>-JOINT KIT,STR,PM,36KV,3X185/35MM2,CU,AR</option>
                                                <option value="908121134" {{ old('work_type') == '908121134' ? 'selected' : '' }}>-TERM KIT,STR,PM,36KV,500/35MM2,CU,I/D</option>
                                                <option value="908121143" {{ old('work_type') == '908121143' ? 'selected' : '' }}>-TERM KIT,STR,HS,15KV,3X500/35MM2,AL,O/D</option>
                                                <option value="908121144" {{ old('work_type') == '908121144' ? 'selected' : '' }}>-TERM KIT,L,15KV,600A,3X500MM2,AL,XLPE,AR</option>
                                                <option value="908121145" {{ old('work_type') == '908121145' ? 'selected' : '' }}>-SLEEVE,RPR,HS,15KV,3X500/35MM2,AL,XLPE</option>
                                                <option value="908121148" {{ old('work_type') == '908121148' ? 'selected' : '' }}>-TERM KIT,STR,HS,15KV,3X500/35MM2,AL,I/D</option> 
                                                <option value="908121157" {{ old('work_type') == '908121157' ? 'selected' : '' }}>-JOINT KIT,TRANS,HS,15KV,500-300/35MM2,AL</option>
                                                <option value="908121158" {{ old('work_type') == '908121158' ? 'selected' : '' }}>-JOINT KIT,TRANS,PM,15KV,500-300/35MM2,AL</option>
                                                <option value="908121173" {{ old('work_type') == '908121173' ? 'selected' : '' }}>-JOINT KIT,C/R,15KV,3CX300-500,AL ARM</option>
                                                <!-- 450 -->
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
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
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option> 
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
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
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option> 
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
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
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option> 
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
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
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
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
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option> 
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option> 
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
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
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option> 
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
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
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option> 
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '408' ? 'selected' : '' }}>-</option>
                                                <option value="" {{ old('work_type') == '804' ? 'selected' : '' }}>-</option>
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
                                            "908112050": "-CABLE,PWR,600V/1KV,QUADRUPLEX,3X50+1X50",
                                            "908112120": "-CABLE,PWR,600V/1KV,QUADRUPLEX,3X120MM2",
                                            "908101001": "-COND,BR,ACSR,QUAIL (2/0 AWG),6AL,1ALWLD",
                                            "908101002": "-COND,BR,ACSR,MERLIN(336.4MCM)18AL,170MM2",
                                            "908111101": "-CNDCTR,BR,CU,35SQMM,7STR,SOFT DRWN",
                                            "908111102": "-CNDCTR,BR,CU,70SQMM,19STR,SOFT DRWN",
                                            "908113005": "-CABLE,PWR,15KV,CU,1C,50/16MM2,XLPLE,UARM",
                                            "908113001": "-CABLE,PWR,15KV,CU,3C,185/35MM2,XPLE,UARM",
                                            "908113002": "-CABLE,PWR,15KV,CU,3C,300/35SQMM,XPLE,",
                                            "908114004": "-CABLE,PWR,15KV,AL,3X500/35MM2,XLPE/LLDPE",
                                            "908113007": "-CABLE,PWR,36KV,CU,3C,240/35MM2,XPLE,ARM",
                                            "908111001": "-CABLE,PWR,600V/1KV,CU,1C,35MM2,XLPE",
                                            "908111002": "-CABLE,PWR,600V/1KV,CU,1C,120MM2,XLPE",
                                            "908111004": "-CABLE,PWR,600V/1KV,CU,1C,630MM2,XLPE",
                                            "908111005": "-CABLE,PWR,600V/1KV,AL,4C,70MM2,XLPE",
                                            "110430980": "-CABLE:4X95MM2,ALUM,XLPE/SWA/PVC,LV AS",
                                            "908111006": "-CABLE,PWR,600V/1KV,AL,4C,185MM2,XLPE",
                                            "908111007": "-CABLE,PWR,600V/1KV,AL,4C,300MM2,XLPE",
                                            "908111014": "-CABLE, PWR, 1KV, AL, 1C, 800/2.6 MM2, XPLE, PVC-ST2",
                                            "908122054": "-CONN, ELEC, TERM, 15KV, 800 SQM, AL, 4HPAD",
                                            "110435040": "-CABLE,PWR,600V/1KV,AL,4C,500MM2,XLPE",
                                            "908121004": "-JOINT KIT,STR,15KV,3X185/35MM2,CU,UAR",
                                            "908121005": "-JOINT KIT,STR,15KV,3X300/35MM2,CU,AR",
                                            "908121016": "-JOINT KIT,TRANS,15KV,3X185/35MM2,CU",
                                            "908121155": "-JOINT KIT,TRANS,HS,15KV,185-500/35MM2,BM",
                                            "908121156": "-JOINT KIT,TRANS,PM,15KV,185-500/35MM2,BM",
                                            "908121149": "-JOINT KIT,TRANS,HS,15KV,300-500/35MM2,BM",
                                            "908121153": "-JOINT KIT,TRANS,PM,15KV,300-500/35MM2,BM",
                                            "908121142": "-JOINT KIT,STR,HS,15KV,3X500/35MM2,AL,AR",
                                            "908121150": "-JOINT KIT,STR,PM,15KV,3X500/35MM2,AL,AR",
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
                                            "102015170": "-TFMR,DIST,PF,500KVA,33KV,231/400,170KV",
                                            "908511032": "-TFMR,PD,1.5MVA,33KX231/133V,170KVB",
                                            "908511108": "-TFMR,PF,300KVA,33KX231/133V,200KVB",
                                            "908511126": "-TFMR,PD,300KVA,33KX231/133V,200KVB,CU",
                                            "908511128": "-TFMR,PD,500KVA,33KX231/133V,200KVB,CU",
                                            "908511130": "-TFMR,PD,1MVA,33KX231/133V,200KVB",
                                            "908512025": "-TFMR,PF,300KVA,33KX400/230V,200KVBL,CU",
                                            "908512033": "-TFMR,PD,300KVA,33KX400/230V,200KVBL,CU",
                                            "908512041": "-TFMR,PD,500KVA,33KX400/230V,200KVBL",
                                            "908512049": "-TFMR,PD,1MVA,33KX400/230V,200KVBL",
                                            "908512057": "-TFMR,PD,1.5MVA,33KX400/230V,200KVBL",
                                            "908513017": "-TFMR,PD,1MVA,33KX400/230V,200KVB,AL",
                                            "908513018": "-TFMR,PD,1.5MVA,33KX400/230V,200KVB,AL",
                                            "908513020": "-TFMR,PD,300KVA,33KX400/230V,200KVB,AL",
                                            "908513021": "-TFMR,PF,300KVA,33KX400/230V,200KVB,AL",
                                            "908513022": "-TFMR,PD,500KVA,33KX400/230V,200KVB,AL",
                                            "908514013": "-TFMR,PD,1MVA,33KX400/230/133V,200KVB,AL",
                                            "908514015": "-TFMR,PD,300KVA,33KX400/230/133V,200KV,AL",
                                            "908514016": "-TFMR,PF,300KVA,33KX400/230/133V,200KV,AL",
                                            "103151010": "-CABINET:LV DISTRIBUTION C/W 300A MCCB PE",
                                            "908514023": "-TFMR,PD,1.5MVA,33KV/400-231-133,BIL 200KV,AL",
                                            "103151020": "-CABINET:LV DISTRIBUTION C/W 500A MCCB PE",
                                            "103151030": "-CABINET:LV DISTRIBUTION C/W 800A MCCB PE",
                                            "908313011": "-CABINET,LV,300A,FOR PMT,1 MCCB OF 300A",
                                            "908313012": "-CABINET,LV,400A,FOR PMT,1 MCCB OF 400A",
                                            "908313008": "-CABINET,LV,800A,FOR PMT,4 MCCB OF 200A",
                                            "104046010": "-TIE WIRE 5MM DIA. ALUMINIUM ALLOY",
                                            "104052580": "-CLAMP:SUSPENSION HDG FOR 125MM2 ACSR,",
                                            "104052610": "-SUSPENSION CLAMP FOR  255MM2 ACSR",
                                            "908121021": "-TERM KIT,STR,36KV,3X185/35MM2,CU,O/D",
                                            "908151004": "-INSULATOR,POST,PORC,35KV,178MM D,825MM",
                                            "908151005": "-INSULATOR,SUSP,PORC,254MM,70KN, 292MM",
                                            "908202012": "-BOLT,MACH,GS,20MM DIA,60MM LG,HEX",
                                            "908202025": "-BOLT,MACH,GS,20MM DIA,400MM LG,HEX",
                                            "908202036": "-NUT,EYE,GS,16MM BLT SZ,40MMWD X 50MM LG",
                                            "908202037": "-NUT,EYE,GS,20MM BLT SZ,40MM WD X 50MM LG",
                                            "908202198": "-BOLT,EYE,GS,16MM DIA,180MM LG",
                                            "908202220": "-BOLT,EYE,GS,16MM DIA,155MM LG,SHOULDER",
                                            "908202221": "-BOLT,EYE,GS,16MM DIA,235MM LG,SHOULDER",
                                            "908202222": "-BOLT,EYE,GS,16MM DIA,255MM LG,SHOULDER",
                                            "908202223": "-BOLT,EYE,GS,16MM DIA,275MM LG,SHOULDER",
                                            "908202224": "-BOLT,EYE,GS,16MM DIA,285MM LG,SHOULDER",
                                            "908202225": "-BOLT,EYE,GS,16MM DIA,310MM LG,SHOULDER",
                                            "908202226": "-BOLT,EYE,GS,20MM DIA,260MM LG,SHOULDER",
                                            "908202227": "-BOLT,EYE,GS,20MM DIA,365MM LG,SHOULDER",
                                            "908202042": "-PLATE,DA,GS,100X700X12MM,22MM DIA HOLE",
                                            "908202090": "-CLIP,BARBED WIRE,SSTL",
                                            "908202091": "-N,3BOLTS-CLAMP,STRAIN,AA,11.34MMDIA,36K",
                                            "908202092": "-N,3BOLTS-CLAMP,STRAIN,AA,17.35MM,45K",
                                            "908202093": "-CLAMP,STRAIN,AA,11.34MMDIA,36KN,STRAIGHT",
                                            "908202094": "-CLAMP,STRAIN,AA,14.31MMDIA,45KN,STRAIGHT",
                                            "908202096": "-CLAMP,SUSP,AA,11.34 MM DIA,36KN UTS",
                                            "908202097": "-CLAMP,SUSP,AA,17.35MM DIA,45KN UTS",
                                            "908202098": "-CLAMP,35-70SQMM CU,16MM DIA CWLD,GRD,BR",
                                            "908202101": "-CLAMP,TOP SERV MAST,GS,76X40X6X176/230MM",
                                            "908202102": "-CLAMP,SERV MAST,GS,76MMDIAX60MMWDX4MMTH",
                                            "908202184": "-COVER,H-TAP,HI-DENSITY PLST,40X47X140MM",
                                            "908202186": "-EXTENSION,CLEVIS,EYE,355 MM LG, 45KN",
                                            "908202196": "-CONNECTOR,PG,AL,QPLX QPLX,4X120SQMM",
                                            "908202209": "-CONNECTOR,PG,AL,MERLIN/AW,3-BOLTS",
                                            "908351004": "-ARRESTER,SURGE,36KV,1320MM,10KA OPR DUTY",
                                            "116423870": "-TERM. OUTDOOR 33 KV 3X120 MM2",
                                            "908201001": "-POLE,STL,DIST,LV,10MLG,TPROCT,OC10",
                                            "908201003": "-POLE,STL TBLR,DIST,13MLG,TPR OCT,OC13S",
                                            "908201006": "-POLE,STL,DIST,MV,15MLG,TPROCT,OC15SD",
                                            "908201007": "-POLE,STL,DIST,LV,10MLG,TPROCT,OC10SFS",
                                            "908201009": "-POLE,STLوTBLR,DIST,13MLG,TPR OCT,OC13SFS",
                                            "908201011": "-POLE,STL,DIST,MV,15MLG,TPROCT,OC15SFS",
                                            "115110760": "-CONN,ELEC,TERM,ACSR,#RYCS288RL",
                                            "116425500": "-KIT,TERMINATION SUPPORT,MOUNTING BRACKET 33KV",
                                            "118053060": "-FUSE:LOW VOLTAGE,315 A,415 VAC,80 KA,BOL",
                                            "908121058": "-JOINT KIT,STR,36KV,3X240/35MM2,CU,AR",
                                            "908121067": "-TERM KIT,RT,36KV,3X240/35MM2,CU",
                                            "908121137": "-TERM KIT,STR,PM,36KV,3X240/35MM2,CU,AR/I",
                                            "908122005": "-CONNECTOR,LUG,300MM2 CU,(1)M16 BLT HL",
                                            "908122007": "-CONNECTOR,LUG,300MM2 AL,(1)M17 BLT HL",
                                            "908122014": "-CONNECTOR,SLV,70MM2 AL,TIN PLT AL",
                                            "908122015": "-CONNECTOR,SLV,185MM2 AL,TIN PLT AL",
                                            "908122029": "-CONNECTOR,SLV,120MM2 AL-70MM2 CU,BIMTLC",
                                            "908122037": "-CONNECTOR,LUG,240SQMM CU,17MM DIA HL",
                                            "908122039": "-CONNECTOR,LUG,185SQMM CU,(1)M16 BLT HL",
                                            "908122041": "-CONNECTOR,LUG,15KV,500MM2,AL,17MM HL",
                                            "908122043": "-CONNECTOR,SLV,BM,500-185MM2",
                                            "908202034": "-CONNECTOR,TERM,PLUG,AL,170.5SQMM ACSR/AW",
                                            "908202059": "-SLEEVE,REPAIR,125.1SQMMACSR,AL,275MM LG",
                                            "908202080": "-CROSSARM,METAL,GS,L100X100X12X2400MM",
                                            "908202189": "-CONNECTOR,GRNDG,C,CU,100-125SQMMCU",
                                            "908202235": "-CONNECTOR,PG,AL,170SQMM,67.4SQMM,3-BOLTS",
                                            "908381000": "-INDICATOR,FAULT,AUTOMATIC RESET,25/50A",
                                            "116613870": "-CABLE WARNING TAPE",
                                            "908020201": "-LOCK,PAD,OPERATION,6MM,40MM,17.2MM,BRS",
                                            "104033840": "-ANGLE,TOWER,40 MM,1500 MM,6 MM,STL",
                                            "104033850": "-ANGLE,TOWER,40MM,1500 MM,6 MM,STL",
                                            "104033870": "-BRACE,CROSSARM,HDG STL",
                                            "104044430": "-SHACKLE:PIN,55 MM INSIDE WD,106 MM INSID",
                                            "104256530": "-ARRESTER,SURGE,36KV,170KV BIL,DIST",
                                            "102015040": "-TFMR,DIST,PD,500KVA,13.8K V AC,400 V AC",
                                            "110131080": "-CONDUCTOR:ELECTRICAL,ACSR,125.1 SQMM,6/1",
                                            "110132550": "-CONDUCTOR:ELECTRICAL,ACSR,255 SQMM,18/1,",
                                            "115112550": "-CONN,ELEC,SLV,TENSION,AA BODY,#YDS319RYD",
                                            "115411800": "-CABLE,PWR,600V/1KV,CU,1C,185MM2,XLPE",
                                            "908111003": "-CABLE,PWR,36KV,CU,3C,185/35 MV,,XPLE,ARM",
                                            "908113008": "-CABLE,PWR,36KV,CU,1C,50/16MM2,XPLE,UARM",
                                            "908113009": "-TERM KIT,STR,36KV,1X50/16MM2,CU,O/D",
                                            "908121028": "-TERM KIT,RT,36KV,1X50/16MM2,CU",
                                            "908121030": "-TERM KIT,RT,36KV,3X185/35MM2,CU",
                                            "908121033": "-TERMINATION KIT:CABLE,HEAT/COLD SHRINK,I",
                                            "908121059": "-SPLICE KIT:CABLE,HEAT/COLD SHRINK,STRAIG",
                                            "908121073": "-TERM KIT,STR,36KV,3X240/35MM2,CU,I/D",
                                            "908151003": "-BOOT,RT ANG,36KV,3X240/35MM2,XLPE",
                                            "908151006": "-INSULATOR,POST,PORC,35KV,185MM D, 1320MM",
                                            "908202055": "-INSULATOR,STAND-OFF,PORC,35KV,195MM,660",
                                            "908202060": "-CONNECTOR,SLV,120X50SQMM AL,AL W/NYL JKT",
                                            "908202062": "-CONNECTOR,SLV,120SQMM ACSR/AW,AL,45KN",
                                            "908202063": "-CONN,ELEC,SLV,TENSION",
                                            "908202064": "-CONNECTOR,SLV,120SQMM AL,AL W/NYL JKT",
                                            "908202066": "-CONNECTOR,SLV,50SQMM,AL W/ NYL JKT",
                                            "908202081": "-CHANNEL,FUSE CUTOUT,C125X65X65X6X2400MM",
                                            "908202082": "-CHANNEL,TFMR MTG,GS,150X75X75X6.5X2400MM",
                                            "908202192": "-GUARD,GRD WIRE,PVC,12.7MM DIAX2440MM LG",
                                            "908202193": "-GUARD,GUY,PVC-YELLOW,51MMWDX2440MMLG",
                                            "908202194": "-HEAD,SERV ENT,AL,SLIP-ON W/2BLT,76MM OD",
                                            "908202200": "-CONNECTOR,PG,AL,QUAIL/AW,2-BOLTS",
                                            "908202201": "-CLAMP,SUSP,AA,14.31 MM DIA,45KN MIN UTS",
                                            "908202210": "-CONNECTOR,PG,AL,120SQMM,50SQMM,COMP",
                                            "908202228": "-BRACE,CROSSARM,GS,BOTH END CLIPPED",
                                            "908202236": "-CONNECTOR,PG,70SQMM CU,70SQMM AL,2-BOLT",
                                            "908202237": "-CONNECTOR,PG,70SQMM CU,120SQMM AL,2-BOLT",
                                            "908301009": "-SWITCH,OHD,VERTICAL,33KV,3P,400A,825MM",
                                            "908301010": "-SWITCH,DISC,OVRHD,33KV,3 P,60HZ, 400A",
                                            "908301015": "-SWITCH,OHD,VERT,3P,33KV,600A, 1320MM",
                                            "908301016": "-SWITCH,OHD,HORIZONTAL,3P,33KV,600A,FOR",
                                            "908511025": "-TFMR,PD,300KVA,33KX400/231V,170KVB",
                                            "908511027": "-TFMR,PD,500KVA,33KX400/231V,170KVB",
                                            "908511031": "-TFMR,PD,1.5MVA,33KX400/231V,170KVB",
                                            "908511129": "-TFMR,PD,1MVA,33KX400/231V,200KVB",
                                            "908421008": "-LOCK:EQUIPMENT,METER SEAL,PLASTIC/STL,19",
                                            "908421009": "-BLOCK,TERMINAL,150A,PA66,55MMWX85MMLX85M",
                                            "908421010": "-BLOCK,200A,PA66,115MMWX150MMLX95MMH,SING",
                                            "908421011": "-BLOCK,200A,PA66,115MMWX150MMLX95MMH,QUAD",
                                            "908421012": "-BLOCK,400A,FRP+CU,230MMWX260MMLX130MMH,Q",
                                            "908421013": "-BLOCK,200A,PA66,115MMWX150MMLX95MMH,DOUB",
                                            "908421014": "-BLOCK,300A,PA66,170MMWX125MMLGX90MMH,DOU",
                                            "908421015": "-COVER,FIBERGLASS,965X270X4MMTH,SINGLEMET",
                                            "908421016": "-COVER,FIBERGLASS,965X965X4,QUADRUPLEMETE",
                                            "908421017": "-COVER,FIBERGLASS,560X965X4MMTH,DOUBLE ME",
                                            "908421018": "-COVER,FIBERGLASS,278X140X3MM,COMMONMETER",
                                            "908421019": "-MOUNT,HDG-STL,370X215X1.5MM,METERANFMCCB",
                                            "908421020": "-MOUNT,STL,92X20X2MM,CABLEFIXINGFACILITY",
                                            "908421021": "-MOUNT,GALVANIZEDCOIL,300X195X1.5MM,QUADR",
                                            "908421022": "-MOUNT,GALVANIZEDCOIL,320X200X1.5MM",
                                            "908421023": "-LUG,TINNEDCOPPER,1XM12HOLE,35SQMM,TERMIN",
                                            "100008292": "-SPOUT,BUSBAR & CABLE,ER,#GCE8003899R0101",
                                            "100010763": "-FUSE:LOW VOLTAGE,1/4 A,250 VAC,FAST BLOW",
                                            "101021180": "-METER,ESERV,ANL,380/220/127V,50(200),3 P",
                                            "101811910": "-SET,TERMINAL BLOCK 185 SQMM AL/CU,#4ACB1",
                                            "101820230": "-BOX,M,1 CUST,M,220/380 VAC VOLT",
                                            "102012150": "-TFMR,DIST,200KVA,13800/4160 V AC,#T3SP20",
                                            "102361110": "-PLATFORM (PREFABRICATED)FOR TRANSFORMER",
                                            "103113420": "-SWITCHGEAR:METAL ENCLOSED,RMU, O/D, (1)",
                                            "103116400": "-METERING UNIT,13.8KV V,3 P,400A",
                                            "103116420": "-KIT,RING MAIN UNIT 13.8 KV,BUSBAR BAND J",
                                            "103117020": "-SWITCHGEAR:METAL ENCLOSED,RMU, O/D,",
                                            "103117030": "-SWITCHGEAR:METAL ENCLOSED,RMU, O/D,OIL,1",
                                            "103117150": "-KIT,BUSBAR END CAP,13.8 KV LUCY RMU",
                                            "103117160": "-KIT,RING MAIN UNIT 13.8 KV,BUSBAR COUPLI",
                                            "103209010": "-TFMR,PKG SUB,1MVA KVA,13.8KV,231Y/133 V",
                                            "103209040": "-TFMR,PKG SUB,1MVA KVA,13.8KV,400/231V",
                                            "104052250": "-CLAMP,STRAIN,AL,40 KN",
                                            "104103260": "-INSULATOR,POST,BROWN, CHOCOLATE GLAZE,#R",
                                            "104112080": "-PIN,GROUND WIRE,OVERHEAD LINE,19 MM DIA",
                                            "104186520": "-SWITCH,DISC,OVRHD,60HZ FREQ,400A,#D2SWIT",
                                            "110110540": "-WIRE:GROUNDING,CU,50 SQMM,1",
                                            "110110950": "-CABLE,ELEC,CU,PVC,95SQMM,1,19 STRAND,BLK",
                                            "115113020": "-CONN,ELEC,TERM,CU,300 SQMM STR CNDCTR",
                                            "115210950": "-CONN,ELEC,TERM,CU,95SQMM CNDCTR,#T9512",
                                            "115216310": "-CONN,ELEC,TERM,AL/CU,630SQMM CNDCTR,#T63",
                                            "115251850": "-CONN,ELEC,TERM,AL/CU,185SQMM CNDCTR,#AL1",
                                            "115253030": "-CONN,ELEC,TERM,AL,#BL3004 BIMETAL",
                                            "115431210": "-CONNECTOR:PARALLEL GROOVE,2X2 BOLTED,300",
                                            "116202630": "-TUBING,SHRINK,POLF,#MWTM3512YEJ502021816",
                                            "116410660": "-CAP,CABLE END,95 TO 115 MM DIA,33 KV,POL",
                                            "116410900": "-BOOT,INSUL,CABLE-BUSHING,ANG,33KV,#ESIB6",
                                            "116411700": "-SLEEVE,CABLE INSULATION REPAIR, WRAP-ARO",
                                            "116412500": "-SEALING END:INDOOR FOR 13.8KV,1X150MM2 X",
                                            "116418950": "-TUBING,SHRINK,POLF,154 MM ID,1M LG,33KV",
                                            "116425890": "-JOINT STRAIGHT TROUGHT 4X500 MM2,ALUM.",
                                            "116425900": "-SPLICE KIT:CABLE,HEAT SHRINK,DIRECT BURI",
                                            "116426130": "-TERMINAL,INDR,HEAT SHRINKABLE,2 TO 35 KV",
                                            "116426790": "-TERMINATION KIT:CABLE,HEATSHRINK,O/D,15",
                                            "116428600": "-GLAND,CBL,50 SQMM, 3 CORE CBL,#EAKT1605",
                                            "118051630": "-FUSE LINK:HRC 160 AMPS 500V,WEDGE SIZE",
                                            "118052010": "-FUSE:LOW VOLTAGE,200 A,415 VAC,SLOW BLOW",
                                            "118053100": "-FUSE:LOW VOLTAGE,300 A,500 V AC,120 KA,K",
                                            "118054020": "-FUSE:LOW VOLTAGE,400 A,415 VAC,80 KA,BOL",
                                            "119411010": "-BREAKER,CRCT,MC,400 VAC 60 HZ,3 POLE,#F1",
                                            "119411510": "-BREAKER,CRCT,MC,480 VAC, 50 TO 60 HZ,#GH",
                                            "119414760": "-BREAKER,CRCT,MC,220 TO 380 V 60 HZ,#S160",
                                            "119416070": "-BREAKER,CIRCUIT, 500AMP 600V 50/60HZ",
                                            "119421420": "-BREAKER,CRCT,MC,400/440 VDC,2 POLE,2 A",
                                            "905010041": "-GLOVES,ELEC,1KV,CL 1,RBR,9,360 MM LG,YEL",
                                            "905010056": "-HELMET:SAFETY,HIGH VOLTAGE PROTECTION,WH",
                                            "905010064": "-TAPE,WARNING,70 MM WD X 200 M LG",
                                            "905010065": "-CONE,TRAFFIC,SAFETY,30 SQCM BASE X 50 CM",
                                            "905020040": "-MASK:PROTECTION,WELDING MIST,HALF FACE",
                                            "905020070": "-GLOVES:WORKING,HEAVY DUTY,MEDIUM,HIGH PE",
                                            "905020071": "-GLOVES:WORKING,HEAVY DUTY,LARGE,HIGH PER",
                                            "905020076": "-GLOVES:WORKING,HEAVY DUTY,8,HIGH PERFORM",
                                            "905020077": "-GLOVES:WORKING,HEAVY DUTY,9,HIGH PERFORM",
                                            "905020085": "-GLOVES, NON-CHEMICAL, MEDIUM, PVC AND SI",
                                            "905020086": "-GLOVES, NON-CHEMICAL, LARGE, PVC AND SIL",
                                            "905030121": "-PROTECTOR,HEARING,FOAM",
                                            "908020202": "-LOCK,PAD,SAFETY,6MM,40MM,17.2MM,BRS/RED",
                                            "908021001": "-OIL,INSUL,TRANSFORMER,0.19 SPGR,30KV",
                                            "908111010": "-CABLE,CNTRL,750V,CU,12CORE,2.5SQMM,PVC",
                                            "908111011": "-CABLE,CNTRL,750V,CU,2CORE,2.5SQMM,PVC",
                                            "908113003": "-CABLE,PWR,15KV,AL,3C,300/35MM2,XPLE,ARM",
                                            "908113004": "-CABLE,PWR,15KV,AL,3C,70/16MM2,XPLE,ARM",
                                            "908114002": "-CABLE,PWR,15K,CU,3X300/35SQMM,XLPE/MDPE",
                                            "908121006": "-JOINT KIT,STR,15KV,3X300/35MM2,AL,AR",
                                            "908121007": "-JOINT KIT,STR,15KV,3X70/16MM2,AL,AR",
                                            "908121011": "-TERM KIT,STR,15KV,3X185/35MM2,CU.",
                                            "908121012": "-TERM KIT,STR,15KV,3X300/35MM2,CU.",
                                            "908121013": "-TERM KIT,STR,15KV,3X300/35MM2,AL",
                                            "908121015": "-JOINT KIT,TRANS,15KV,3X185/35MM2,AL",
                                            "908121020": "-CAP,CBL END,15KV,1X50/16MM2,CU,XLPE,UARM",
                                            "908121022": "-CAP,CBL END,15KV,3X300/35MM2,CU/AL,XLPE",
                                            "908121026": "-SLEEVE,REPAIR,3X185-240/35MM2,XLPE,ARM",
                                            "908121027": "-CAP,CBL END,3X185-500/35MM2,XLPE,ARM",
                                            "908121029": "-SLEEVE,REPAIR,1KV,4X70-185MM2,1500MM",
                                            "908121032": "-SLEEVE,REPAIR,15KV,1X50MM2,1500MM LG",
                                            "908121034": "-SLEEVE,REPAIR,15KV,3X300/35MM2,1500MM LG",
                                            "908121035": "-SLEEVE,REPAIR,15KV,3X70/16-3X185/35MM2",
                                            "908121036": "-TERM KIT,STR,36KV,1X50/16MM2,CU,I/D",
                                            "908121037": "-CONN,ELEC,EL,DB",
                                            "908121040": "-ELBOW,EC,15KV,400A,3X70/16MM2",
                                            "908121047": "-TERM KIT,STR,15KV,3X185/35MM2,CU",
                                            "908121050": "-TERM KIT,STR,15KV,3X300/35MM2,CU",
                                            "908121054": "-TERM KIT,RT,15KV,3X300/35MM2,AL",
                                            "908121069": "-BOOT,RT ANG,15KV,3X70/16MM2,AL,XLPE",
                                            "908121075": "-BOOT,RT ANG,36KV,1X50/16MM2,XLPE",
                                            "908121081": "-BOOT,STR,36KV,3X240/35MM2,CU,XLPE",
                                            "908121083": "-BOOT,STR,36KV,1X50/16MM2,CU,XLPE",
                                            "908121085": "-SPLICE KIT,LG,STR,36KV,3X185/35SQMM",
                                            "908121102": "-JOINT KIT,STR,PM,15KV,3X185/35MM2,CU",
                                            "908121103": "-JOINT KIT,STR,PM,15KV,3X300/35MM2,CU,AR",
                                            "908121106": "-TERM KIT,STR,PM,1KV,4X70MM2,AL",
                                            "908121107": "-TERM KIT,STR,PM,1KV,4X185MM2,AL",
                                            "908121109": "-TERM KIT,STR,PM,15KV,3X185/35MM2,CU,O/D",
                                            "908121110": "-TERM KIT,STR,PM,15KV,3X300/35MM2,CU,AR/O",
                                            "908121114": "-JOINT KIT,TRANS,PM,15KV,3X185-300,CU",
                                            "908121121": "-TERM KIT,ST,PM,15KV,50/16MM2,CU,I/D",
                                            "908121122": "-TERM KIT,RT,PM,15KV,50/16MM2,CU,I/D",
                                            "908121126": "-TERM KIT,STR,PM,15KV,3X300/35MM2,CU,AR/I",
                                            "908121127": "-TERM KIT,RT,PM,15KV,3X300/35MM2,CU,AR/I",
                                            "908121130": "-JOINT KIT,STR,PM,36KV,500/35MM2,CU",
                                            "908121131": "-JOINT KIT,STR,PM,36KV,3X240/35MM2,CU,AR",
                                            "908121132": "-JOINT KIT,STR,PM,36KV,3X185/35MM2,CU,AR",
                                            "908121134": "-TERM KIT,STR,PM,36KV,500/35MM2,CU,I/D",
                                            "908121143": "-TERM KIT,STR,HS,15KV,3X500/35MM2,AL,O/D",
                                            "908121144": "-TERM KIT,L,15KV,600A,3X500MM2,AL,XLPE,AR",
                                            "908121145": "-SLEEVE,RPR,HS,15KV,3X500/35MM2,AL,XLPE",
                                            "908121148": "-TERM KIT,STR,HS,15KV,3X500/35MM2,AL,I/D",
                                            "908121157": "-JOINT KIT,TRANS,HS,15KV,500-300/35MM2,AL",
                                            "908121158": "-JOINT KIT,TRANS,PM,15KV,500-300/35MM2,AL",
                                            "908121173": "-JOINT KIT,C/R,15KV,3CX300-500,AL ARM"
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