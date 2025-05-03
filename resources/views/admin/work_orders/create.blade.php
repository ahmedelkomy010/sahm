@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h3 class="mb-0 fs-4">إنشاء أمر عمل جديد</h3>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.work-orders.store') }}" class="custom-form" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="order_number" class="form-label fw-bold">رقم الطلب</label>
                                    <input id="order_number" type="text" class="form-control @error('order_number') is-invalid @enderror" name="order_number" value="{{ old('order_number') }}" autofocus>
                                    @error('order_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="work_type" class="form-label fw-bold">نوع العمل</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input id="work_type_number" type="number" min="1" max="999" class="form-control mb-2" placeholder="أدخل رقم نوع العمل">
                                        </div>
                                        <div class="col-md-8">
                                            <select id="work_type" class="form-select @error('work_type') is-invalid @enderror" name="work_type">
                                                <option value="">اختر نوع العمل</option>
                                                <option value="409" {{ old('work_type') == '409' ? 'selected' : '' }}> -ازالة-نقل شبكة على المشترك</option>
                                                <option value="408" {{ old('work_type') == '408' ? 'selected' : '' }}>-  ازاله عداد على المشترك</option>
                                                <option value="460" {{ old('work_type') == '460' ? 'selected' : '' }}>-  استبدال شبكات</option>
                                                <option value="901" {{ old('work_type') == '901' ? 'selected' : '' }}> -   اضافة  عداد  طاقة  شمسية</option>
                                                <option value="440" {{ old('work_type') == '440' ? 'selected' : '' }}> - الرفع المساحي</option>
                                                <option value="410" {{ old('work_type') == '410' ? 'selected' : '' }}>-  انشاء محطة/محول لمشترك/مشتركين </option>
                                                <option value="801" {{ old('work_type') == '801' ? 'selected' : '' }}>-  تركيب عداد  ايصال سريع </option>
                                                <option value="804" {{ old('work_type') == '804' ? 'selected' : '' }}> -  تركيب محطة ش ارضية VM ايصال سريع</option>
                                                <option value="805" {{ old('work_type') == '805' ? 'selected' : '' }}> - تركيب محطة ش هوائية VM ايصال سريع</option>
                                                <option value="480" {{ old('work_type') == '480' ? 'selected' : '' }}> -  (تسليم مفتاح) تمويل خارجي </option>
                                                <option value="441" {{ old('work_type') == '441' ? 'selected' : '' }}> -  تعزيز  شبكة  أرضية  ومحطات </option>
                                                <option value="442" {{ old('work_type') == '442' ? 'selected' : '' }}> -  تعزيز شبكة هوائية ومحطات </option>
                                                <option value="802" {{ old('work_type') == '802' ? 'selected' : '' }}> -  شبكة ارضية VL ايصال سريع</option>
                                                <option value="803" {{ old('work_type') == '803' ? 'selected' : '' }}> -  شبكة هوائية VL ايصال سريع</option>
                                                <option value="402" {{ old('work_type') == '402' ? 'selected' : '' }}>-  توصيل عداد بحفرية شبكة ارضيه </option>
                                                <option value="401" {{ old('work_type') == '401' ? 'selected' : '' }}> -  (عداد بدون حفرية ) أرضي/هوائي </option>
                                                <option value="404" {{ old('work_type') == '404' ? 'selected' : '' }}> -  عداد بمحطة شبكة ارضية VM</option>
                                                <option value="405" {{ old('work_type') == '405' ? 'selected' : '' }}> -  توصيل عداد بمحطة شبكة هوائية VM</option>
                                                <option value="430" {{ old('work_type') == '430' ? 'selected' : '' }}> -  مخططات منح  وزارة  البلدية </option>
                                                <option value="450" {{ old('work_type') == '450' ? 'selected' : '' }}>- مشاريع ربط محطات التحويل</option>
                                                <option value="403" {{ old('work_type') == '403' ? 'selected' : '' }}> -  توصيل عداد شبكة هوائية VL</option>
                                            </select>
                                        </div>
                                    </div>
                                    @error('work_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="work_description" class="form-label fw-bold">وصف العمل والتعليق</label>
                                    <textarea id="work_description" class="form-control @error('work_description') is-invalid @enderror" name="work_description" rows="5" placeholder="أدخل وصف العمل والتعليق هنا...">{{ old('work_description') }}</textarea>
                                    @error('work_description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="approval_date" class="form-label fw-bold">تاريخ الاعتماد</label>
                                    <input id="approval_date" type="date" class="form-control @error('approval_date') is-invalid @enderror" name="approval_date" value="{{ old('approval_date') }}">
                                    @error('approval_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="subscriber_name" class="form-label fw-bold">اسم المشترك</label>
                                    <input id="subscriber_name" type="text" class="form-control @error('subscriber_name') is-invalid @enderror" name="subscriber_name" value="{{ old('subscriber_name') }}">
                                    @error('subscriber_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="district" class="form-label fw-bold">الحي</label>
                                    <input id="district" type="text" class="form-control @error('district') is-invalid @enderror" name="district" value="{{ old('district') }}">
                                    @error('district')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="station_number" class="form-label fw-bold">رقم المحطة</label>
                                    <input id="station_number" type="text" class="form-control @error('station_number') is-invalid @enderror" name="station_number" value="{{ old('station_number') }}">
                                    @error('station_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="consultant_name" class="form-label fw-bold">اسم الاستشاري</label>
                                    <input id="consultant_name" type="text" class="form-control @error('consultant_name') is-invalid @enderror" name="consultant_name" value="{{ old('consultant_name') }}">
                                    @error('consultant_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="office" class="form-label fw-bold">المكتب</label>
                                    <select id="office" class="form-select @error('office') is-invalid @enderror" name="office">
                                        <option value="">اختر المكتب</option>
                                        <option value="خريص" {{ old('office') == 'خريص' ? 'selected' : '' }}>خريص</option>
                                        <option value="الشرق" {{ old('office') == 'الشرق' ? 'selected' : '' }}>الشرق</option>
                                        <option value="الشمال" {{ old('office') == 'الشمال' ? 'selected' : '' }}>الشمال</option>
                                        <option value="الجنوب" {{ old('office') == 'الجنوب' ? 'selected' : '' }}>الجنوب</option>
                                        <option value="الدرعية" {{ old('office') == 'الدرعية' ? 'selected' : '' }}>الدرعية</option>
                                    </select>
                                    @error('office')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="order_value_with_consultant" class="form-label fw-bold">قيمة أمر العمل شامل الاستشاري</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₪</span>
                                                <input id="order_value_with_consultant" type="number" step="0.01" class="form-control @error('order_value_with_consultant') is-invalid @enderror" name="order_value_with_consultant" value="{{ old('order_value_with_consultant') }}">
                                            </div>
                                            @error('order_value_with_consultant')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="order_value_without_consultant" class="form-label fw-bold">قيمة أمر العمل بدون استشاري</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₪</span>
                                                <input id="order_value_without_consultant" type="number" step="0.01" class="form-control @error('order_value_without_consultant') is-invalid @enderror" name="order_value_without_consultant" value="{{ old('order_value_without_consultant') }}">
                                            </div>
                                            @error('order_value_without_consultant')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="execution_status" class="form-label fw-bold">حالة تنفيذ أمر العمل</label>
                                    <select id="execution_status" class="form-select @error('execution_status') is-invalid @enderror" name="execution_status">
                                        
                                        <option value="2" {{ old('execution_status') == '' ? 'selected' : '' }}>تم تسليم المقاول ولم يتم الاستلام</option>
                                        <option value="1" {{ old('execution_status') == '2' ? 'selected' : '' }}>تم الاستلام من المقاول ولم تصدر شهادة الانجاز</option>
                                        <option value="3" {{ old('execution_status') == '3' ? 'selected' : '' }}>دخلت مستخلص ولم تصرف</option>
                                        <option value="4" {{ old('execution_status') == '4' ? 'selected' : '' }}>صدرت شهادة الانجاز ولم تعتمد</option>
                                        <option value="5" {{ old('execution_status') == '5' ? 'selected' : '' }}>منتهي</option>
                                        <option value="6" {{ old('execution_status') == '6' ? 'selected' : '' }}>مؤكد ولم تدخل مستخلص</option>
                                    </select>
                                    @error('execution_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="actual_execution_value" class="form-label fw-bold">قيمة التنفيذ الفعلي لأمر العمل</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₪</span>
                                        <input id="actual_execution_value" type="number" step="0.01" class="form-control @error('actual_execution_value') is-invalid @enderror" name="actual_execution_value" value="{{ old('actual_execution_value') }}">
                                    </div>
                                    @error('actual_execution_value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="procedure_155_delivery_date" class="form-label fw-bold">تاريخ تسليم إجراء 155</label>
                                            <input id="procedure_155_delivery_date" type="date" class="form-control @error('procedure_155_delivery_date') is-invalid @enderror" name="procedure_155_delivery_date" value="{{ old('procedure_155_delivery_date') }}">
                                            @error('procedure_155_delivery_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="procedure_211_date" class="form-label fw-bold">تاريخ إجراء 211</label>
                                            <input id="procedure_211_date" type="date" class="form-control @error('procedure_211_date') is-invalid @enderror" name="procedure_211_date" value="{{ old('procedure_211_date') }}">
                                            @error('procedure_211_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="partial_deletion" name="partial_deletion" value="1" {{ old('partial_deletion') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="partial_deletion">صرف جزئي</label>
                                    </div>
                                    @error('partial_deletion')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="partial_payment_value" class="form-label fw-bold">قيمة الصرف الجزئي</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₪</span>
                                        <input id="partial_payment_value" type="number" step="0.01" class="form-control @error('partial_payment_value') is-invalid @enderror" name="partial_payment_value" value="{{ old('partial_payment_value') }}">
                                    </div>
                                    @error('partial_payment_value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-section mb-4">
                                    <h4 class="section-title mb-3">معلومات المستخلص</h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="extract_number" class="form-label fw-bold">رقم المستخلص</label>
                                                <input id="extract_number" type="text" class="form-control @error('extract_number') is-invalid @enderror" name="extract_number" value="{{ old('extract_number') }}">
                                                @error('extract_number')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="extract_date" class="form-label fw-bold">تاريخ المستخلص</label>
                                                <input id="extract_date" type="date" class="form-control @error('extract_date') is-invalid @enderror" name="extract_date" value="{{ old('extract_date') }}">
                                                @error('extract_date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="extract_value" class="form-label fw-bold">قيمة المستخلص</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₪</span>
                                                    <input id="extract_value" type="number" step="0.01" class="form-control @error('extract_value') is-invalid @enderror" name="extract_value" value="{{ old('extract_value') }}">
                                                </div>
                                                @error('extract_value')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section mb-4">
                                    <h4 class="section-title mb-3">معلومات الفاتورة والشراء</h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="invoice_number" class="form-label fw-bold">رقم الفاتورة</label>
                                                <input id="invoice_number" type="text" class="form-control @error('invoice_number') is-invalid @enderror" name="invoice_number" value="{{ old('invoice_number') }}">
                                                @error('invoice_number')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="purchase_order_number" class="form-label fw-bold">رقم أمر الشراء</label>
                                                <input id="purchase_order_number" type="text" class="form-control @error('purchase_order_number') is-invalid @enderror" name="purchase_order_number" value="{{ old('purchase_order_number') }}">
                                                @error('purchase_order_number')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="tax_value" class="form-label fw-bold">قيمة الضريبة</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₪</span>
                                                    <input id="tax_value" type="number" step="0.01" class="form-control @error('tax_value') is-invalid @enderror" name="tax_value" value="{{ old('tax_value') }}">
                                                </div>
                                                @error('tax_value')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section mb-4">
                                    <h4 class="section-title mb-3">المرفقات</h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">استلام من الكهرباء</label>
                                            <input type="file" class="form-control @error('files.electricity_receipt') is-invalid @enderror" name="files[electricity_receipt]">
                                            @error('files.electricity_receipt')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">صرف المواد من الكهرباء</label>
                                            <input type="file" class="form-control @error('files.materials_receipt') is-invalid @enderror" name="files[materials_receipt]">
                                            @error('files.materials_receipt')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">رفع الرخصة</label>
                                            <input type="file" class="form-control @error('files.license') is-invalid @enderror" name="files[license]">
                                            @error('files.license')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">برنامج تنفيذ يومي</label>
                                            <input type="file" class="form-control @error('files.daily_program') is-invalid @enderror" name="files[daily_program]">
                                            @error('files.daily_program')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">ورقة قياس الاعمال اليومية</label>
                                            <input type="file" class="form-control @error('files.daily_measurement') is-invalid @enderror" name="files[daily_measurement]">
                                            @error('files.daily_measurement')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">الرسم الهندسي</label>
                                            <input type="file" class="form-control @error('files.engineering_drawing') is-invalid @enderror" name="files[engineering_drawing]">
                                            @error('files.engineering_drawing')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">اختيار الكابلات</label>
                                            <input type="file" class="form-control @error('files.cable_selection') is-invalid @enderror" name="files[cable_selection]">
                                            @error('files.cable_selection')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">155</label>
                                            <input type="file" class="form-control @error('files.procedure_155') is-invalid @enderror" name="files[procedure_155]">
                                            @error('files.procedure_155')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">صرف دفعة جزئية 50%</label>
                                            <input type="file" class="form-control @error('files.partial_payment') is-invalid @enderror" name="files[partial_payment]">
                                            @error('files.partial_payment')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">صرف وارجاع</label>
                                            <input type="file" class="form-control @error('files.payment_return') is-invalid @enderror" name="files[payment_return]">
                                            @error('files.payment_return')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">صرف دفعة نهائية 50%</label>
                                            <input type="file" class="form-control @error('files.final_payment') is-invalid @enderror" name="files[final_payment]">
                                            @error('files.final_payment')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">مرفق احتياطي 1</label>
                                            <input type="file" class="form-control @error('files.backup_1') is-invalid @enderror" name="files[backup_1]">
                                            @error('files.backup_1')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">مرفق احتياطي 2</label>
                                            <input type="file" class="form-control @error('files.backup_2') is-invalid @enderror" name="files[backup_2]">
                                            @error('files.backup_2')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">مرفق احتياطي 3</label>
                                            <input type="file" class="form-control @error('files.backup_3') is-invalid @enderror" name="files[backup_3]">
                                            @error('files.backup_3')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="alert alert-info mt-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        يمكنك رفع ملفات (PDF, JPG, PNG, DOC, DOCX, XLS, XLSX) - الحد الأقصى 20 ميجابايت لكل ملف
                                    </div>
                                </div>

                                <div class="form-group d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.work-orders.index') }}" class="btn btn-secondary px-4">
                                        <i class="fas fa-times me-2"></i> إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-save me-2"></i> إنشاء
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* تخصيص النموذج */
    .custom-form label {
        color: #333;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }
    
    .custom-form .form-control,
    .custom-form .form-select {
        border: 1px solid #ddd;
        padding: 0.6rem 0.75rem;
        transition: all 0.3s;
        border-radius: 4px;
    }
    
    .custom-form .form-control:focus,
    .custom-form .form-select:focus {
        border-color: #3490dc;
        box-shadow: 0 0 0 0.2rem rgba(52, 144, 220, 0.15);
    }
    
    .custom-form .input-group-text {
        background-color: #f8f9fa;
        border-color: #ddd;
    }
    
    /* تخصيص الأزرار */
    .custom-form .btn {
        border-radius: 4px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .custom-form .btn-primary {
        background-color: #3490dc;
        border-color: #3490dc;
    }
    
    .custom-form .btn-primary:hover {
        background-color: #2779bd;
        border-color: #2779bd;
    }
    
    .custom-form .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .custom-form .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
    }
    
    /* تنسيق البطاقة */
    .card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    
    .card-header {
        border-bottom: 0;
        font-weight: 600;
        padding: 1.5rem;
    }
    
    .card-body {
        padding: 2rem;
    }
    
    /* تصميم للاتجاه من اليمين إلى اليسار */
    body, .form-group {
        text-align: right;
    }
    
    .input-group .input-group-text {
        border-radius: 0 0.25rem 0.25rem 0;
    }
    
    .input-group .form-control {
        border-radius: 0.25rem 0 0 0.25rem;
    }
    
    /* تنسيق المرفقات */
    .form-check {
        margin-bottom: 0.5rem;
    }
    
    .form-check-input {
        margin-left: 0.5rem;
    }
    
    .alert {
        border-radius: 4px;
        padding: 1rem;
    }
    
    .alert-info {
        background-color: #e3f2fd;
        border-color: #90caf9;
        color: #0d47a1;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const workTypeSelect = document.getElementById('work_type');
    const workTypeNumber = document.getElementById('work_type_number');
    const workDescriptionInput = document.getElementById('work_description');
    
    // Function to load the description based on the work type
    function loadWorkDescription(workType) {
        if (workType) {
            fetch(`{{ url('admin/work-orders/descriptions') }}/${workType}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.description) {
                        workDescriptionInput.value = data.description;
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }
    
    // When the selector changes
    workTypeSelect.addEventListener('change', function() {
        const workType = this.value;
        if (workType) {
            // Update the number field
            workTypeNumber.value = workType;
            // Load the description
            loadWorkDescription(workType);
        }
    });
    
    // When the number field changes
    workTypeNumber.addEventListener('input', function() {
        const workType = this.value;
        if (workType) {
            // Check if a matching option exists
            let matchFound = false;
            for (let i = 0; i < workTypeSelect.options.length; i++) {
                if (workTypeSelect.options[i].value === workType) {
                    workTypeSelect.value = workType;
                    matchFound = true;
                    break;
                }
            }
            
            // Load the description regardless of match
            loadWorkDescription(workType);
        }
    });
    
    // Load the description with the initial value (if it exists)
    if (workTypeSelect.value) {
        loadWorkDescription(workTypeSelect.value);
    }
});
</script>

@endsection 