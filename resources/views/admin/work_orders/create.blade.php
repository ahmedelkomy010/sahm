@extends('layouts.app')

@push('head')
<meta name="import-work-items-url" content="{{ route('admin.work-orders.import-work-items') }}">
<meta name="import-materials-url" content="{{ route('admin.work-orders.import-materials') }}">
<meta name="project" content="{{ $project ?? 'riyadh' }}">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 fs-4">إنشاء أمر عمل جديد</h3>
                        <div class="d-flex align-items-center gap-3">
                            @if(isset($project))
                                @if($project == 'riyadh')
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-city me-1"></i>
                                    مشروع الرياض
                                </span>
                                @elseif($project == 'madinah')
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-mosque me-1"></i>
                                    مشروع المدينة المنورة
                                </span>
                                @endif
                            @endif
                            <a href="{{ route('admin.work-orders.index', ['project' => $project ?? '']) }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                العودة إلى أوامر العمل
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.work-orders.store', ['project' => $project]) }}" class="custom-form" enctype="multipart/form-data">
                        @csrf
                        @if(isset($project))
                            @if($project == 'riyadh')
                            <input type="hidden" name="city" value="الرياض">
                            @elseif($project == 'madinah')
                            <input type="hidden" name="city" value="المدينة المنورة">
                            @endif
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="order_number" class="form-label fw-bold">رقم أمر العمل</label>
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
                                                <option value="409" {{ old('work_type') == '409' ? 'selected' : '' }}> - ازالة-نقل شبكة على المشترك</option>
                                                <option value="408" {{ old('work_type') == '408' ? 'selected' : '' }}> -  ازاله عداد على المشترك</option>
                                                <option value="460" {{ old('work_type') == '460' ? 'selected' : '' }}> -  استبدال شبكات</option>
                                                <option value="901" {{ old('work_type') == '901' ? 'selected' : '' }}> -   اضافة  عداد  طاقة  شمسية</option>
                                                <option value="440" {{ old('work_type') == '440' ? 'selected' : '' }}> - الرفع المساحي</option>
                                                <option value="410" {{ old('work_type') == '410' ? 'selected' : '' }}> -  انشاء محطة/محول لمشترك/مشتركين </option>
                                                <option value="801" {{ old('work_type') == '801' ? 'selected' : '' }}> -  تركيب عداد ايصال سريع </option>
                                                <option value="804" {{ old('work_type') == '804' ? 'selected' : '' }}> -  تركيب محطة ش ارضية VM ايصال سريع</option>
                                                <option value="805" {{ old('work_type') == '805' ? 'selected' : '' }}> - تركيب محطة ش هوائية VM ايصال سريع</option>
                                                <option value="480" {{ old('work_type') == '480' ? 'selected' : '' }}> -  (تسليم مفتاح) تمويل خارجي </option>
                                                <option value="441" {{ old('work_type') == '441' ? 'selected' : '' }}> -  تعزيز  شبكة  أرضية  ومحطات </option>
                                                <option value="442" {{ old('work_type') == '442' ? 'selected' : '' }}> -  تعزيز شبكة هوائية ومحطات </option>
                                                <option value="802" {{ old('work_type') == '802' ? 'selected' : '' }}> -  شبكة ارضية VL ايصال سريع</option>
                                                <option value="803" {{ old('work_type') == '803' ? 'selected' : '' }}> -  شبكة هوائية VL ايصال سريع</option>
                                                <option value="402" {{ old('work_type') == '402' ? 'selected' : '' }}> -  توصيل عداد بحفرية شبكة ارضيه </option>
                                                <option value="401" {{ old('work_type') == '401' ? 'selected' : '' }}> -  (عداد بدون حفرية ) أرضي/هوائي </option>
                                                <option value="404" {{ old('work_type') == '404' ? 'selected' : '' }}> -  عداد بمحطة شبكة ارضية VM</option>
                                                <option value="405" {{ old('work_type') == '405' ? 'selected' : '' }}> -  توصيل عداد بمحطة شبكة هوائية VM</option>
                                                <option value="430" {{ old('work_type') == '430' ? 'selected' : '' }}> -  مخططات منح  وزارة  البلدية </option>
                                                <option value="450" {{ old('work_type') == '450' ? 'selected' : '' }}> - مشاريع ربط محطات التحويل</option>
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

                                <!-- حقل المدينة مخفي ويتم تعيينه تلقائياً حسب المشروع -->
                                @if(isset($project))
                                    @if($project == 'riyadh')
                                    <input type="hidden" name="city" value="الرياض">
                                    @elseif($project == 'madinah')
                                    <input type="hidden" name="city" value="المدينة المنورة">
                                    @endif
                                @endif

                                <div class="form-group mb-3">
                                    <label for="municipality" class="form-label fw-bold">البلدية</label>
                                    <input id="municipality" type="text" class="form-control @error('municipality') is-invalid @enderror" name="municipality" value="{{ old('municipality') }}"required>
                                    @error('municipality')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="station_number" class="form-label fw-bold">رقم المحطة</label>
                                    <input id="station_number" type="text" class="form-control @error('station_number') is-invalid @enderror" name="station_number" value="{{ old('station_number') }}"required>
                                    @error('station_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="consultant_name" class="form-label fw-bold">اسم الاستشاري</label>
                                    <input id="consultant_name" type="text" class="form-control @error('consultant_name') is-invalid @enderror" name="consultant_name" value="{{ old('consultant_name') }}"required>
                                    @error('consultant_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="office" class="form-label fw-bold">المكتب</label>
                                    <select id="office" class="form-select @error('office') is-invalid @enderror" name="office"required>
                                        <option value="">اختر المكتب</option>
                                        @if(isset($project))
                                            @if($project == 'riyadh')
                                                <option value="خريص" {{ old('office') == 'خريص' ? 'selected' : '' }}>خريص</option>
                                                <option value="الشرق" {{ old('office') == 'الشرق' ? 'selected' : '' }}>الشرق</option>
                                                <option value="الشمال" {{ old('office') == 'الشمال' ? 'selected' : '' }}>الشمال</option>
                                                <option value="الجنوب" {{ old('office') == 'الجنوب' ? 'selected' : '' }}>الجنوب</option>
                                                <option value="الدرعية" {{ old('office') == 'الدرعية' ? 'selected' : '' }}>الدرعية</option>
                                            @elseif($project == 'madinah')
                                                <option value="المدينة المنورة" {{ old('office') == 'المدينة المنورة' ? 'selected' : '' }}>المدينة المنورة</option>
                                                <option value="ينبع" {{ old('office') == 'ينبع' ? 'selected' : '' }}>ينبع</option>
                                                <option value="خيبر" {{ old('office') == 'خيبر' ? 'selected' : '' }}>خيبر</option>
                                                <option value="مهد الذهب" {{ old('office') == 'مهد-الذهب' ? 'selected' : '' }}>مهد الذهب</option>
                                                <option value="بدر" {{ old('office') == 'بدر' ? 'selected' : '' }}>بدر</option>
                                                <option value="الحناكية" {{ old('office') == 'الحناكية' ? 'selected' : '' }}>الحناكية</option>
                                                <option value="العلا" {{ old('office') == 'العلا' ? 'selected' : '' }}>العلا</option>
                                            @endif
                                        @endif
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
                                            <label for="order_value_with_consultant" class="form-label fw-bold">قيمة أمر العمل المبدئية شامل الاستشاري</label>
                                            <div class="input-group">
                                                <span class="input-group-text">﷼</span>
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
                                            <label for="order_value_without_consultant" class="form-label fw-bold">قيمة أمر العمل المبدئية بدون استشاري</label>
                                            <div class="input-group">
                                                <span class="input-group-text">﷼</span>
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
                                        <option value="1" {{ old('execution_status', '1') == '1' ? 'selected' : '' }}>جاري العمل ...</option>
                                        
                                    </select>
                                    @error('execution_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-section mb-4">
                                    <h4 class="section-title mb-3">المرفقات</h4>
                                    <div class="row">
                                        
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-paperclip text-secondary me-2"></i>
                                                رفع مرفق
                                            </label>
                                            <input type="file" class="form-control @error('files.backup_1') is-invalid @enderror" name="files[backup_1]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                            <div class="form-text">
                                                PDF, Word, Excel, أو صورة - الحد الأقصى 20 ميجابايت
                                            </div>
                                            @error('files.backup_1')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="alert alert-info mt-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        - الحد الأقصى 20 ميجابايت  
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- قسم مقايسة الأعمال -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-section mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="section-title mb-0">
                                            <i class="fas fa-tasks me-2 text-primary"></i>
                                            مقايسة الأعمال
                                        </h4>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-primary" onclick="addWorkItem()">
                                                <i class="fas fa-plus"></i> إضافة بند عمل
                                            </button>
                                            @if(isset($project))
                                                @if($project == 'riyadh')
                                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#riyadhExcelImportModal">
                                                    <i class="fas fa-file-excel"></i> استيراد من Excel 
                                                </button>
                                                @elseif($project == 'madinah')
                                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#madinahExcelImportModal">
                                                    <i class="fas fa-file-excel"></i> استيراد من Excel 
                                                </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- حقل البحث عن بند العقد -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="searchContractItem" 
                                                       placeholder="البحث عن بند العقد بالكود أو الوصف..." 
                                                       onkeyup="searchContractItems(this.value)">
                                                <button class="btn btn-outline-secondary" type="button" onclick="clearContractSearch()">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <button class="btn btn-outline-primary" type="button" onclick="searchContractItems(document.getElementById('searchContractItem').value)">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="searchResults" class="text-muted small">
                                                <!-- نتائج البحث ستظهر هنا -->
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- جدول بنود العمل -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="workItemsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 25%">بند العقد</th>
                                                    <th style="width: 30%">الوصف</th>
                                                    <th style="width: 15%">الكمية المخططة</th>
                                                    <th style="width: 10%">الوحدة</th>
                                                    <th style="width: 10%">سعر الوحدة</th>
                                                    <th style="width: 10%">إجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody id="workItemsBody">
                                                <!-- سيتم إضافة الصفوف هنا ديناميكياً -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- قسم مقايسة المواد -->
                                <div class="form-section mb-4">
                                    @if($errors->any())
                                        <div class="alert alert-danger mb-3">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <ul class="mb-0">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="section-title mb-0">
                                            <i class="fas fa-boxes me-2 text-success"></i>
                                            مقايسة المواد
                                        </h4>
                                        <div class="d-flex gap-2">
                                            @if(isset($project))
                                                @if($project == 'riyadh')
                                                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#riyadhMaterialsImportModal">
                                                    <i class="fas fa-file-excel"></i> استيراد من Excel (الرياض)
                                                </button>
                                                @elseif($project == 'madinah')
                                                <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#madinahMaterialsImportModal">
                                                    <i class="fas fa-file-excel"></i> استيراد من Excel (المدينة)
                                                </button>
                                                @endif
                                            @endif
                                            <button type="button" class="btn btn-outline-success btn-sm" onclick="addMaterial()">
                                                <i class="fas fa-plus"></i> إضافة مادة
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- حقل البحث السريع -->
                                    <div class="row mb-3">
                                        
                                        <div class="col-md-6">
                                            <div id="quickSearchResults" class="position-relative">
                                                <!-- نتائج البحث السريع ستظهر هنا -->
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="materialsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 25%">كود المادة</th>
                                                    <th style="width: 35%">وصف المادة</th>
                                                    <th style="width: 15%">الكمية المخططة</th>
                                                    <th style="width: 15%">الوحدة</th>
                                                    <th style="width: 10%">إجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody id="materialsBody">
                                                <!-- سيتم إضافة الصفوف هنا ديناميكياً -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('admin.work-orders.index') }}" class="btn btn-secondary px-4">
                                        <i class="fas fa-times me-2"></i> إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4" onclick="return validateForm()">
                                        <i class="fas fa-save me-2"></i> إنشاء
                                    </button>
                                </div>

                                <script>
                                    function validateForm() {
                                        // التحقق من وجود المواد
                                        const materialsBody = document.getElementById('materialsBody');
                                        if (!materialsBody || materialsBody.children.length === 0) {
                                            alert('يجب إضافة مادة واحدة على الأقل');
                                            return false;
                                        }

                                        // التحقق من كل حقول المواد
                                        let isValid = true;
                                        const rows = materialsBody.getElementsByTagName('tr');
                                        for (let i = 0; i < rows.length; i++) {
                                            const row = rows[i];
                                            
                                            // التحقق من كود المادة
                                            const materialCode = row.querySelector('input[name*="[material_code]"]');
                                            if (!materialCode.value.trim()) {
                                                alert('يجب إدخال كود المادة في السطر ' + (i + 1));
                                                materialCode.focus();
                                                return false;
                                            }

                                            // التحقق من وصف المادة
                                            const materialDesc = row.querySelector('input[name*="[material_description]"]');
                                            if (!materialDesc.value.trim()) {
                                                alert('يجب إدخال وصف المادة في السطر ' + (i + 1));
                                                materialDesc.focus();
                                                return false;
                                            }

                                            // التحقق من الكمية المخططة
                                            const quantity = row.querySelector('input[name*="[planned_quantity]"]');
                                            if (!quantity.value || quantity.value <= 0) {
                                                alert('يجب إدخال كمية صحيحة أكبر من صفر في السطر ' + (i + 1));
                                                quantity.focus();
                                                return false;
                                            }

                                            // التحقق من الوحدة
                                            const unit = row.querySelector('select[name*="[unit]"]');
                                            if (!unit.value) {
                                                alert('يجب اختيار الوحدة في السطر ' + (i + 1));
                                                unit.focus();
                                                return false;
                                            }


                                        }

                                        return true;
                                    }
                                </script>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Excel Import -->
<div class="modal fade" id="excelImportModal" tabindex="-1" aria-labelledby="excelImportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="excelImportModalLabel">استيراد بنود العمل من ملف Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="excelImportForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">اختر ملف Excel</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx,.xls,.csv">
                        <div class="form-text">يجب أن يحتوي الملف على الأعمدة التالية: الكود، الوصف، الوحدة، السعر</div>
                    </div>
                    <div id="uploadProgress" class="progress mb-3 d-none">
                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="importResults"></div>
                    <button type="submit" class="btn btn-primary">رفع الملف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Materials Excel Import -->
<div class="modal fade" 
    id="materialsImportModal" 
    tabindex="-1" 
    role="dialog"
    aria-labelledby="materialsImportModalLabel"
    data-bs-backdrop="static"
    data-bs-keyboard="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="materialsImportModalLabel">
                    <i class="fas fa-file-excel me-2"></i>
                    استيراد مقايسة المواد من ملف Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>متطلبات الملف:</strong>
                    <ul class="mb-0 mt-2">
                        <li>يجب أن يحتوي الملف على عمودين فقط: <strong>كود المادة</strong> و <strong>وصف المادة</strong></li>
                        <li>يدعم ملفات Excel (.xlsx, .xls) و CSV (.csv)</li>
                        <li>الحد الأقصى لحجم الملف: 10 ميجا بايت</li>
                    </ul>
                </div>
                
                <form id="materialsImportForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="materialsFile" class="form-label fw-bold">اختر ملف المواد</label>
                        <input type="file" class="form-control form-control-lg" id="materialsFile" name="file" 
                               accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">
                            <i class="fas fa-download me-1"></i>
                            <a href="{{ route('admin.work-orders.download-materials-template') }}" class="text-decoration-none" target="_blank">
                                تحميل نموذج Excel للمواد
                            </a>
                        </div>
                    </div>
                    
                    <div id="materialsUploadProgress" class="progress mb-3 d-none">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                             role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    
                    <div id="materialsImportResults" class="mb-3"></div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> إلغاء
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload me-1"></i> رفع واستيراد
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal البحث في البنود -->
<div class="modal fade" id="workItemsSearchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-search text-info me-2"></i>
                    البحث في بنود العمل
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="searchWorkItems" 
                               placeholder="ابحث في الكود، الوصف، الوحدة، أو الملاحظات...">
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-info w-100" onclick="searchWorkItems()">
                            <i class="fas fa-search me-1"></i>بحث
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>الكود</th>
                                <th>الوصف</th>
                                <th>الوحدة</th>
                                <th>سعر الوحدة</th>
                                <th>إضافة</th>
                            </tr>
                        </thead>
                        <tbody id="workItemsSearchResults">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    ابدأ بالبحث لعرض النتائج
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div id="searchPagination" class="d-flex justify-content-center mt-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal البحث في المواد -->
<div class="modal fade" id="materialsSearchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-search me-2"></i>
                    البحث في المواد المرجعية
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">البحث بالكود</label>
                        <input type="text" class="form-control" id="searchMaterialCode" 
                               placeholder="كود المادة">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">البحث بالوصف</label>
                        <input type="text" class="form-control" id="searchMaterialDescription" 
                               placeholder="وصف المادة">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-warning w-100" onclick="searchMaterials()">
                            <i class="fas fa-search me-1"></i>بحث
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20%">الكود</th>
                                <th style="width: 40%">الوصف</th>
                                <th style="width: 15%">الوحدة</th>
                                <th style="width: 15%">السعر</th>
                                <th style="width: 10%">إضافة</th>
                            </tr>
                        </thead>
                        <tbody id="materialsSearchResults">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-search fa-2x mb-2 d-block"></i>
                                    ابدأ بالبحث لعرض النتائج
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div id="materialsPagination" class="d-flex justify-content-center mt-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Riyadh Excel Import -->
<div class="modal fade" id="riyadhExcelImportModal" tabindex="-1" aria-labelledby="riyadhExcelImportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="riyadhExcelImportModalLabel">
                    <i class="fas fa-file-excel me-2"></i>
                    استيراد بنود العمل من Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="riyadhExcelImportForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="city" value="riyadh">
                    <div class="mb-3">
                        <label for="riyadhFile" class="form-label">اختر ملف Excel</label>
                        <input type="file" class="form-control" id="riyadhFile" name="file" accept=".xlsx,.xls,.csv">
                        <div class="form-text">
                            <strong>مخصص لمشروع الرياض فقط:</strong><br>
                            يجب أن يحتوي الملف على الأعمدة التالية: الكود، الوصف، الوحدة، السعر<br>
                            <small class="text-info">ملاحظة: البيانات المستوردة ستكون منفصلة عن بيانات المدينة المنورة</small>
                        </div>
                    </div>
                    <div id="riyadhUploadProgress" class="progress mb-3 d-none">
                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="riyadhImportResults"></div>
                    <button type="submit" class="btn btn-primary">رفع الملف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Madinah Excel Import -->
<div class="modal fade" id="madinahExcelImportModal" tabindex="-1" aria-labelledby="madinahExcelImportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="madinahExcelImportModalLabel">
                    <i class="fas fa-file-excel me-2"></i>
                    استيراد بنود العمل من Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="madinahExcelImportForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="city" value="madinah">
                    <div class="mb-3">
                        <label for="madinahFile" class="form-label">اختر ملف Excel</label>
                        <input type="file" class="form-control" id="madinahFile" name="file" accept=".xlsx,.xls,.csv">
                        <div class="form-text">
                            <strong>مخصص لمشروع المدينة المنورة فقط:</strong><br>
                            يجب أن يحتوي الملف على الأعمدة التالية: الكود، الوصف، الوحدة، السعر<br>
                            <small class="text-info">ملاحظة: البيانات المستوردة ستكون منفصلة عن بيانات الرياض</small>
                        </div>
                    </div>
                    <div id="madinahUploadProgress" class="progress mb-3 d-none">
                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="madinahImportResults"></div>
                    <button type="submit" class="btn btn-success">رفع الملف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Riyadh Materials Import -->
<div class="modal fade" id="riyadhMaterialsImportModal" tabindex="-1" aria-labelledby="riyadhMaterialsImportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="riyadhMaterialsImportModalLabel">
                    <i class="fas fa-file-excel me-2"></i>
                    استيراد مقايسة المواد من Excel 
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="riyadhMaterialsImportForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="city" value="riyadh">
                    <div class="mb-3">
                        <label for="riyadhMaterialsFile" class="form-label">اختر ملف Excel</label>
                        <input type="file" class="form-control" id="riyadhMaterialsFile" name="file" accept=".xlsx,.xls,.csv">
                        <div class="form-text">
                            <strong>مخصص لمشروع الرياض فقط:</strong><br>
                            يجب أن يحتوي الملف على عمودين: كود المادة والوصف<br>
                            <small class="text-info">ملاحظة: المواد المستوردة ستكون منفصلة عن مواد المدينة المنورة</small>
                        </div>
                    </div>
                    <div id="riyadhMaterialsUploadProgress" class="progress mb-3 d-none">
                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="riyadhMaterialsImportResults"></div>
                    <button type="submit" class="btn btn-primary">رفع الملف</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Madinah Materials Import -->
<div class="modal fade" id="madinahMaterialsImportModal" tabindex="-1" aria-labelledby="madinahMaterialsImportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="madinahMaterialsImportModalLabel">
                    <i class="fas fa-file-excel me-2"></i>
                    استيراد مقايسة المواد من Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="madinahMaterialsImportForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="city" value="madinah">
                    <div class="mb-3">
                        <label for="madinahMaterialsFile" class="form-label">اختر ملف Excel</label>
                        <input type="file" class="form-control" id="madinahMaterialsFile" name="file" accept=".xlsx,.xls,.csv">
                        <div class="form-text">
                            <strong>مخصص لمشروع المدينة المنورة فقط:</strong><br>
                            يجب أن يحتوي الملف على عمودين: كود المادة والوصف<br>
                            <small class="text-info">ملاحظة: المواد المستوردة ستكون منفصلة عن مواد الرياض</small>
                        </div>
                    </div>
                    <div id="madinahMaterialsUploadProgress" class="progress mb-3 d-none">
                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div id="madinahMaterialsImportResults"></div>
                    <button type="submit" class="btn btn-success">رفع الملف</button>
                </form>
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
    
    /* تنسيق أقسام المقايسة */
    .form-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        border: 1px solid #e9ecef;
    }
    
    .section-title {
        color: #495057;
        font-weight: 600;
    }
    
    .table-responsive {
        border-radius: 6px;
        overflow: hidden;
    }
    
    .table th {
        background-color: #e9ecef;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 12px 8px;
        border: 1px solid #dee2e6;
    }
    
    .table td {
        padding: 8px;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }
    
    .form-control-sm, .form-select-sm {
        font-size: 0.875rem;
        padding: 0.4rem 0.6rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    /* تنسيق البحث السريع */
    #quickSearchResults {
        z-index: 1000;
    }
    
    #quickSearchResults .cursor-pointer:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    
    .alert-sm {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
    
    /* تحسينات للتصاميم */
    .modal-xl .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const workTypeSelect = document.getElementById('work_type');
    const workTypeNumber = document.getElementById('work_type_number');
    const workDescriptionInput = document.getElementById('work_description');
    
    // Function to get work type description
    function getWorkTypeDescription(workType) {
        const descriptions = {
            '409': 'ازالة-نقل شبكة على المشترك',
            '408': 'ازاله عداد على المشترك',
            '460': 'استبدال شبكات',
            '901': 'اضافة عداد طاقة شمسية',
            '440': 'الرفع المساحي',
            '410': 'انشاء محطة/محول لمشترك/مشتركين',
            '801': 'تركيب عداد ايصال سريع',
            '804': 'تركيب محطة ش ارضية VM ايصال سريع',
            '805': 'تركيب محطة ش هوائية VM ايصال سريع',
            '480': '(تسليم مفتاح) تمويل خارجي',
            '441': 'تعزيز شبكة أرضية ومحطات',
            '442': 'تعزيز شبكة هوائية ومحطات',
            '802': 'شبكة ارضية VL ايصال سريع',
            '803': 'شبكة هوائية VL ايصال سريع',
            '402': 'توصيل عداد بحفرية شبكة ارضيه',
            '401': '(عداد بدون حفرية ) أرضي/هوائي',
            '404': 'عداد بمحطة شبكة ارضية VM',
            '405': 'توصيل عداد بمحطة شبكة هوائية VM',
            '430': 'مخططات منح وزارة البلدية',
            '450': 'مشاريع ربط محطات التحويل',
            '403': 'توصيل عداد شبكة هوائية VL'
        };
        return descriptions[workType] || '';
    }
    
    // Function to update work description
    function updateWorkDescription(workType) {
        if (workType) {
            const description = getWorkTypeDescription(workType);
            if (description) {
                // دائماً قم بتعيين الوصف الجديد مباشرة
                workDescriptionInput.value = description;
            }
        }
    }
    
    // When the selector changes
    workTypeSelect.addEventListener('change', function() {
        const workType = this.value;
        if (workType) {
            // Update the number field
            workTypeNumber.value = workType;
            // Update the description
            updateWorkDescription(workType);
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
            
            // Update the description regardless of match
            updateWorkDescription(workType);
        }
    });
    
    // Update description with initial value if it exists
    if (workTypeSelect.value) {
        updateWorkDescription(workTypeSelect.value);
    }
});

// بيانات بنود العمل - من قاعدة البيانات
const workItems = @json($workItems ?? []);

// بيانات المواد المرجعية - من قاعدة البيانات  
const referenceMaterials = @json($referenceMaterials ?? []);

// استخدام المتغير العام المعرف أعلاه
window.materialRowIndex = 0;

// إضافة بند عمل جديد
function addWorkItem() {
    const tbody = document.getElementById('workItemsBody');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <div class="position-relative">
                <input type="text" class="form-control form-control-sm work-item-search" 
                       placeholder="ابحث عن بند العمل..." 
                       onkeyup="searchWorkItem(this, ${window.workItemRowIndex})"
                       onclick="showWorkItemDropdown(this, ${window.workItemRowIndex})">
                <input type="hidden" name="work_items[${window.workItemRowIndex}][work_item_id]" class="work-item-id">
                <div class="dropdown-menu work-item-dropdown" id="dropdown_${window.workItemRowIndex}" style="display: none; position: absolute; top: 100%; left: 0; right: 0; z-index: 1000; max-height: 200px; overflow-y: auto;">
                </div>
            </div>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm work-item-description" 
                   name="work_items[${window.workItemRowIndex}][description]" placeholder="وصف العمل">
        </td>
        <td>
            <input type="number" name="work_items[${window.workItemRowIndex}][planned_quantity]" 
                   class="form-control form-control-sm" step="0.01" min="0" placeholder="الكمية" required>
        </td>
        <td>
            <select name="work_items[${window.workItemRowIndex}][unit]" class="form-select form-select-sm work-item-unit">
                <option value="EA">EA</option>
                <option value="M">M</option>
                <option value="KIT">KIT</option>
                <option value="ST">ST</option>
                <option value="M2">M2</option>
                <option value="%">%</option>
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm work-item-price" 
                   name="work_items[${window.workItemRowIndex}][unit_price]" step="0.01" min="0" placeholder="سعر الوحدة">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    window.workItemRowIndex++;
}

// البحث في بنود العمل
function searchWorkItem(input, index) {
    const searchTerm = input.value.toLowerCase();
    const dropdown = document.getElementById(`dropdown_${index}`);
    
    if (searchTerm.length < 2) {
        dropdown.style.display = 'none';
        return;
    }
    
    const filteredItems = workItems.filter(item => 
        item.code.toLowerCase().includes(searchTerm) || 
        item.description.toLowerCase().includes(searchTerm)
    );
    
    if (filteredItems.length > 0) {
        dropdown.innerHTML = filteredItems.map(item => `
            <div class="dropdown-item" style="cursor: pointer; padding: 8px 12px; border-bottom: 1px solid #eee;" 
                 onclick="selectWorkItem(${index}, '${item.id}', '${item.code}', '${item.description}', '${item.unit}', '${item.unit_price || 0}')">
                <div><strong>${item.code}</strong></div>
                <div style="font-size: 0.9em; color: #666;">${item.description}</div>
                <div style="font-size: 0.8em; color: #888;">${item.unit} - ${item.unit_price ? parseFloat(item.unit_price).toFixed(2) + ' ﷼' : 'غير محدد'}</div>
            </div>
        `).join('');
        dropdown.style.display = 'block';
    } else {
        dropdown.innerHTML = '<div class="dropdown-item" style="padding: 8px 12px; color: #999;">لا توجد نتائج</div>';
        dropdown.style.display = 'block';
    }
}

// إظهار قائمة بنود العمل
function showWorkItemDropdown(input, index) {
    const dropdown = document.getElementById(`dropdown_${index}`);
    
    if (workItems.length > 0) {
        dropdown.innerHTML = workItems.slice(0, 10).map(item => `
            <div class="dropdown-item" style="cursor: pointer; padding: 8px 12px; border-bottom: 1px solid #eee;" 
                 onclick="selectWorkItem(${index}, '${item.id}', '${item.code}', '${item.description}', '${item.unit}', '${item.unit_price || 0}')">
                <div><strong>${item.code}</strong></div>
                <div style="font-size: 0.9em; color: #666;">${item.description}</div>
                <div style="font-size: 0.8em; color: #888;">${item.unit} - ${item.unit_price ? parseFloat(item.unit_price).toFixed(2) + ' ﷼' : 'غير محدد'}</div>
            </div>
        `).join('');
        dropdown.style.display = 'block';
    }
}

// اختيار بند العمل
function selectWorkItem(index, id, code, description, unit, price) {
    const row = document.querySelector(`#dropdown_${index}`).closest('tr');
    
    // تحديث الحقول
    row.querySelector('.work-item-search').value = code;
    row.querySelector('.work-item-id').value = id;
    row.querySelector('.work-item-description').value = description;
    
    // تحديث قائمة الوحدات
    const unitSelect = row.querySelector('.work-item-unit');
    const validUnits = ['EA', 'M', 'KIT', 'ST', 'M2', '%'];
    const defaultUnit = 'EA';
    
    // تحديد الوحدة المناسبة
    unitSelect.value = validUnits.includes(unit) ? unit : defaultUnit;
    
    row.querySelector('.work-item-price').value = price ? parseFloat(price).toFixed(2) : '';
    
    // إخفاء القائمة
    document.getElementById(`dropdown_${index}`).style.display = 'none';
}

// إضافة مادة جديدة
function addMaterial() {
    const tbody = document.getElementById('materialsBody');

    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
                                                            <input type="text" name="materials[${window.materialRowIndex}][material_code]" 
                                                       class="form-control form-control-sm material-code @error('materials.*.material_code') is-invalid @enderror" 
                                                       onchange="handleMaterialCodeChange(this)"
                                                       placeholder="أدخل كود المادة" required>
                                                @error('materials.*.material_code')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
        </td>
        <td>
                                                            <input type="text" name="materials[${window.materialRowIndex}][material_description]" 
                                                       class="form-control form-control-sm material-description @error('materials.*.material_description') is-invalid @enderror"
                                                       onchange="handleMaterialDescriptionChange(this)"
                                                       placeholder="أدخل وصف المادة" required>
                                                @error('materials.*.material_description')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
        </td>
        <td>
                                                            <input type="number" name="materials[${window.materialRowIndex}][planned_quantity]" 
                                                       class="form-control form-control-sm @error('materials.*.planned_quantity') is-invalid @enderror" 
                                                       value="1" min="1" step="1" required
                                                       onchange="handleQuantityChange(this)">
                                                @error('materials.*.planned_quantity')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
        </td>
        <td>
            <select name="materials[${window.materialRowIndex}][unit]" class="form-select form-select-sm material-unit @error('materials.*.unit') is-invalid @enderror" required>
                <option value="L.M">L.M</option>
                <option value="Kit">Kit</option>
                <option value="Ech">Ech</option>
            </select>
            @error('materials.*.unit')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeMaterialRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    window.materialRowIndex++;
}

// معالجة تغيير كود المادة
async function handleMaterialCodeChange(input) {
    const row = input.closest('tr');
    const code = input.value.trim();
    const descriptionInput = row.querySelector('.material-description');
    const unitInput = row.querySelector('.material-unit');


    // التحقق من صحة الكود
    if (!code) {
        alert('يجب إدخال كود المادة');
        input.focus();
        return;
    }

    try {
        // البحث عن المادة في قاعدة البيانات
        const response = await fetch(`/admin/materials/search?code=${encodeURIComponent(code)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success && data.material) {
            // تعبئة البيانات الموجودة
            descriptionInput.value = data.material.description;
            unitInput.value = data.material.unit || 'قطعة';

        } else {
            // إذا لم يتم العثور على المادة
            alert('لم يتم العثور على المادة. يرجى التأكد من الكود.');
            input.focus();
        }
    } catch (error) {
        console.error('Error searching material:', error);
        alert('حدث خطأ أثناء البحث عن المادة');
    }
}

// معالجة تغيير وصف المادة
async function handleMaterialDescriptionChange(input) {
    const row = input.closest('tr');
    const code = row.querySelector('.material-code').value.trim();
    const description = input.value.trim();
    const unit = row.querySelector('.material-unit').value.trim();


    // التحقق من صحة البيانات
    if (!description) {
        alert('يجب إدخال وصف المادة');
        input.focus();
        return;
    }

    if (!code) {
        alert('يجب إدخال كود المادة أولاً');
        row.querySelector('.material-code').focus();
        return;
    }

    if (!unit) {
        alert('يجب اختيار الوحدة');
        row.querySelector('.material-unit').focus();
        return;
    }



    try {
        // حفظ المادة في قاعدة البيانات
        const response = await fetch('/admin/materials/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                code: code,
                description: description,
                unit: unit
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // إظهار رسالة نجاح صغيرة
            showToast('success', 'تم حفظ المادة بنجاح');
        } else {
            // إظهار رسالة خطأ
            showToast('error', data.message || 'حدث خطأ أثناء حفظ المادة');
        }
    } catch (error) {
        console.error('Error saving material:', error);
        showToast('error', 'حدث خطأ أثناء حفظ المادة');
    }
}

// معالجة تغيير الكمية
function handleQuantityChange(input) {
    const quantity = parseFloat(input.value);
    if (!quantity || quantity <= 0) {
        alert('يجب إدخال كمية صحيحة أكبر من صفر');
        input.value = 1;
        input.focus();
        return;
    }
}

// إظهار رسالة توست
function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 position-fixed bottom-0 end-0 m-3`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // حذف التوست بعد إغلاقه
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

// حذف صف مادة
function removeMaterialRow(button) {
    const tbody = document.getElementById('materialsBody');
    if (tbody.children.length <= 1) {
        alert('يجب أن يكون هناك مادة واحدة على الأقل');
        return;
    }

    if (confirm('هل أنت متأكد من حذف هذه المادة؟')) {
        const row = button.closest('tr');
        if (row) {
            row.remove();
            
            // إضافة صف جديد إذا كان الجدول فارغاً
            if (tbody && tbody.children.length === 0) {
                addMaterial();
            }
        }
    }
}

// تهيئة المتغيرات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // تهيئة مؤشر صفوف المواد إذا لم يكن موجوداً
    if (typeof window.materialRowIndex === 'undefined') {
        window.materialRowIndex = 0;
    }
    
    // تهيئة مؤشر صفوف بنود العمل إذا لم يكن موجوداً  
    if (typeof window.workItemRowIndex === 'undefined') {
        window.workItemRowIndex = 0;
    }
    
    // إضافة صف افتراضي إذا كان الجدول فارغاً
    const tbody = document.getElementById('materialsBody');
    if (tbody && tbody.children.length === 0) {
        addMaterial();
    }
});

// وظائف رفع ملف Excel
function showExcelImportModal() {
    const modal = new bootstrap.Modal(document.getElementById('excelImportModal'));
    modal.show();
}

function previewExcelFile(input) {
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        filePreview.classList.remove('d-none');
    } else {
        filePreview.classList.add('d-none');
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// تحميل ملف Excel
document.getElementById('excelImportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // إضافة معامل المشروع
    const project = '{{ $project ?? "riyadh" }}';
    formData.append('project', project);
    
    fetch('{{ route("admin.work-orders.import-work-items") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            toastr.success(response.message);
            const modal = bootstrap.Modal.getInstance(document.getElementById('excelImportModal'));
            modal.hide();
            
            // إضافة البنود المستوردة للجدول
            if (response.imported_items && response.imported_items.length > 0) {
                response.imported_items.forEach(function(item) {
                    addWorkItemToTable(item);
                });
            }
        } else {
            toastr.error(response.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('حدث خطأ أثناء رفع الملف');
    });
});

function addWorkItemToTable(item) {
    const tbody = document.getElementById('workItemsBody');
    const row = document.createElement('tr');
    
    row.innerHTML = `
        <td>
            <div class="position-relative">
                <input type="text" class="form-control form-control-sm work-item-search" 
                       value="${item.code}">
                <input type="hidden" name="work_items[${tbody.children.length}][work_item_id]" class="work-item-id" value="${item.id}">
            </div>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm work-item-description" 
                   name="work_items[${tbody.children.length}][description]" value="${item.description}" placeholder="وصف العمل">
        </td>
        <td>
            <input type="number" name="work_items[${tbody.children.length}][planned_quantity]" 
                   class="form-control form-control-sm" step="0.01" min="0" placeholder="الكمية" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm work-item-unit" 
                   name="work_items[${tbody.children.length}][unit]" value="${item.unit}" placeholder="الوحدة">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm work-item-price" 
                   name="work_items[${tbody.children.length}][unit_price]" value="${item.unit_price || ''}" step="0.01" min="0" placeholder="سعر الوحدة">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
}

// وظائف البحث في البنود
function showWorkItemsSearch() {
    const modal = new bootstrap.Modal(document.getElementById('workItemsSearchModal'));
    modal.show();
    
    // البحث التلقائي عند فتح النافذة
    searchWorkItems();
}

function searchWorkItems() {
    const searchTerm = document.getElementById('searchWorkItems').value;
    
    fetch(`{{ route("admin.work-orders.work-items") }}?search=${encodeURIComponent(searchTerm)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displaySearchResults(data.data);
            displayPagination(data.pagination);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('workItemsSearchResults').innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-danger py-4">
                    حدث خطأ أثناء البحث
                </td>
            </tr>
        `;
    });
}

function displaySearchResults(items) {
    const tbody = document.getElementById('workItemsSearchResults');
    
    if (items.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    لا توجد نتائج للبحث
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = items.map(item => `
        <tr>
            <td>${item.code}</td>
            <td>${item.description}</td>
            <td>${item.unit}</td>
                            <td>${item.unit_price ? parseFloat(item.unit_price).toFixed(2) + ' ﷼' : '-'}</td>
            <td>
                <button type="button" class="btn btn-sm btn-primary" 
                        onclick="addWorkItemFromSearch('${item.id}', '${item.code}', '${item.description}', '${item.unit}', '${item.unit_price || 0}')">
                    <i class="fas fa-plus"></i> إضافة
                </button>
            </td>
        </tr>
    `).join('');
}

function displayPagination(pagination) {
    // يمكن إضافة pagination هنا إذا لزم الأمر
    document.getElementById('searchPagination').innerHTML = `
        <small class="text-muted">
            عرض ${pagination.current_page} من ${pagination.last_page} 
            (إجمالي ${pagination.total} عنصر)
        </small>
    `;
}

function addWorkItemFromSearch(id, code, description, unit, price) {
    const tbody = document.getElementById('workItemsBody');
    const row = document.createElement('tr');
    
    // تحديد الوحدة المناسبة
    const validUnits = ['EA', 'M', 'KIT', 'ST', 'M2', '%'];
    const defaultUnit = 'EA';
    const selectedUnit = validUnits.includes(unit) ? unit : defaultUnit;
    
    row.innerHTML = `
        <td>
            <div class="position-relative">
                <input type="text" class="form-control form-control-sm work-item-search" 
                       value="${code}" readonly>
                <input type="hidden" name="work_items[${window.workItemRowIndex}][work_item_id]" class="work-item-id" value="${id}">
            </div>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm work-item-description" 
                   name="work_items[${window.workItemRowIndex}][description]" value="${description}" placeholder="وصف العمل">
        </td>
        <td>
            <input type="number" name="work_items[${window.workItemRowIndex}][planned_quantity]" 
                   class="form-control form-control-sm" step="0.01" min="0" placeholder="الكمية" required>
        </td>
        <td>
            <select name="work_items[${window.workItemRowIndex}][unit]" class="form-select form-select-sm work-item-unit">
                <option value="EA" ${selectedUnit === 'EA' ? 'selected' : ''}>EA</option>
                <option value="M" ${selectedUnit === 'M' ? 'selected' : ''}>M</option>
                <option value="KIT" ${selectedUnit === 'KIT' ? 'selected' : ''}>KIT</option>
                <option value="ST" ${selectedUnit === 'ST' ? 'selected' : ''}>ST</option>
                <option value="M2" ${selectedUnit === 'M2' ? 'selected' : ''}>M2</option>
                <option value="%" ${selectedUnit === '%' ? 'selected' : ''}>%</option>
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm work-item-price" 
                   name="work_items[${window.workItemRowIndex}][unit_price]" value="${price || ''}" step="0.01" min="0" placeholder="سعر الوحدة">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    window.workItemRowIndex++;
    
    // إظهار رسالة نجاح
    toastr.success(`تم إضافة البند: ${code}`);
    
    // مسح البحث
    clearContractSearch();
}

function refreshWorkItemsDropdowns(newItems) {
    // تحديث جميع القوائم المنسدلة لبنود العمل
    const selects = document.querySelectorAll('select[name*="work_item_id"]');
    selects.forEach(select => {
        newItems.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = `${item.code} - ${item.description}`;
            option.setAttribute('data-unit', item.unit);
            select.appendChild(option);
        });
    });
}

// البحث المباشر عند الكتابة
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchWorkItems');
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                searchWorkItems();
            }
        });
    }
    
    // إضافة مستمع لإعادة تعيين النموذج عند إغلاق النافذة
    const excelModal = document.getElementById('excelImportModal');
    if (excelModal) {
        excelModal.addEventListener('hidden.bs.modal', function() {
            // إعادة تعيين النموذج
            const form = document.getElementById('excelImportForm');
            const preview = document.getElementById('filePreview');
            const results = document.getElementById('importResults');
            
            if (form) form.reset();
            if (preview) preview.classList.add('d-none');
            if (results) results.innerHTML = '';
            // تم حذف hideImportProgress لأنها غير موجودة
        });
    }
});

// إضافة مستمع الأحداث للبحث في البنود المستوردة
document.addEventListener('keyup', function(e) {
    if (e.target.id === 'searchImportedItems' && e.key === 'Enter') {
        searchInImportedItems();
    }
});

// إضافة مستمع لإغلاق النافذة المنبثقة للاستيراد
$('#excelImportModal').on('hidden.bs.modal', function() {
    resetImportModal();
});

// إضافة مستمع لإغلاق قوائم البحث عند النقر خارجها
document.addEventListener('click', function(e) {
    // إغلاق جميع قوائم البحث في بنود العمل
    const dropdowns = document.querySelectorAll('.work-item-dropdown');
    dropdowns.forEach(dropdown => {
        if (!dropdown.contains(e.target) && !dropdown.previousElementSibling.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
});

// منع إغلاق القائمة عند النقر داخلها
document.addEventListener('click', function(e) {
    if (e.target.closest('.work-item-dropdown')) {
        e.stopPropagation();
    }
});

// وظائف البحث في بنود العقد
function searchContractItems(searchTerm) {
    const resultsDiv = document.getElementById('searchResults');
    
    if (!searchTerm || searchTerm.length < 2) {
        resultsDiv.innerHTML = '';
        return;
    }
    
    // البحث في بنود العمل المتاحة
    const filteredItems = workItems.filter(item => 
        item.code.toLowerCase().includes(searchTerm.toLowerCase()) || 
        item.description.toLowerCase().includes(searchTerm.toLowerCase())
    );
    
    if (filteredItems.length > 0) {
        resultsDiv.innerHTML = `
            <div class="search-results-container">
                <div class="mb-2"><strong>نتائج البحث (${filteredItems.length} عنصر):</strong></div>
                <div class="search-results-list" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px; background: white;">
                    ${filteredItems.slice(0, 10).map(item => `
                        <div class="search-result-item p-2 border-bottom" style="cursor: pointer;" 
                             onclick="addItemFromSearch('${item.id}', '${item.code}', '${item.description}', '${item.unit}', '${item.unit_price || 0}')"
                             onmouseover="this.style.backgroundColor='#f8f9fa'" 
                             onmouseout="this.style.backgroundColor='white'">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong class="text-primary">${item.code}</strong>
                                    <div class="text-muted small">${item.description}</div>
                                </div>
                                <div class="text-end">
                                    <div class="badge bg-secondary">${item.unit}</div>
                                    <div class="text-success small">${item.unit_price ? parseFloat(item.unit_price).toFixed(2) + ' ﷼' : 'غير محدد'}</div>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                    ${filteredItems.length > 10 ? `<div class="p-2 text-center text-muted small">وعرض ${filteredItems.length - 10} نتيجة أخرى...</div>` : ''}
                </div>
            </div>
        `;
    } else {
        resultsDiv.innerHTML = `
            <div class="alert alert-info small mb-0">
                <i class="fas fa-info-circle me-1"></i>
                لا توجد نتائج للبحث عن "${searchTerm}"
            </div>
        `;
    }
}

// إضافة بند من نتائج البحث
function addItemFromSearch(id, code, description, unit, price) {
    const tbody = document.getElementById('workItemsBody');
    const row = document.createElement('tr');
    
    // تحديد الوحدة المناسبة
    const validUnits = ['EA', 'M', 'KIT', 'ST', 'M2', '%'];
    const defaultUnit = 'EA';
    const selectedUnit = validUnits.includes(unit) ? unit : defaultUnit;
    
    row.innerHTML = `
        <td>
            <div class="position-relative">
                <input type="text" class="form-control form-control-sm work-item-search" 
                       value="${code}" readonly>
                <input type="hidden" name="work_items[${window.workItemRowIndex}][work_item_id]" class="work-item-id" value="${id}">
            </div>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm work-item-description" 
                   name="work_items[${window.workItemRowIndex}][description]" value="${description}" placeholder="وصف العمل">
        </td>
        <td>
            <input type="number" name="work_items[${window.workItemRowIndex}][planned_quantity]" 
                   class="form-control form-control-sm" step="0.01" min="0" placeholder="الكمية" required>
        </td>
        <td>
            <select name="work_items[${window.workItemRowIndex}][unit]" class="form-select form-select-sm work-item-unit">
                <option value="EA" ${selectedUnit === 'EA' ? 'selected' : ''}>EA</option>
                <option value="M" ${selectedUnit === 'M' ? 'selected' : ''}>M</option>
                <option value="KIT" ${selectedUnit === 'KIT' ? 'selected' : ''}>KIT</option>
                <option value="ST" ${selectedUnit === 'ST' ? 'selected' : ''}>ST</option>
                <option value="M2" ${selectedUnit === 'M2' ? 'selected' : ''}>M2</option>
                <option value="%" ${selectedUnit === '%' ? 'selected' : ''}>%</option>
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm work-item-price" 
                   name="work_items[${window.workItemRowIndex}][unit_price]" value="${price || ''}" step="0.01" min="0" placeholder="سعر الوحدة">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    window.workItemRowIndex++;
    
    // إظهار رسالة نجاح
    toastr.success(`تم إضافة البند: ${code}`);
    
    // مسح البحث
    clearContractSearch();
}

// مسح البحث
function clearContractSearch() {
    document.getElementById('searchContractItem').value = '';
    document.getElementById('searchResults').innerHTML = '';
}

// ================================
// وظائف استيراد المواد من Excel
// ================================

// معالجة نموذج استيراد المواد
document.getElementById('materialsImportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const fileInput = document.getElementById('materialsFile');
    const file = fileInput.files[0];
    
    if (!file) {
        showMaterialsImportError('يرجى اختيار ملف أولاً');
        return;
    }
    
    // التحقق من نوع الملف
    const allowedTypes = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel',
        'text/csv'
    ];
    
    if (!allowedTypes.includes(file.type)) {
        showMaterialsImportError('نوع الملف غير مدعوم. يرجى اختيار ملف Excel أو CSV');
        return;
    }
    
    // التحقق من حجم الملف (10 ميجا بايت)
    if (file.size > 10 * 1024 * 1024) {
        showMaterialsImportError('حجم الملف كبير جداً. الحد الأقصى المسموح 10 ميجا بايت');
        return;
    }
    
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // إضافة معامل المشروع
    const project = '{{ $project ?? "riyadh" }}';
    formData.append('project', project);
    
    // إظهار شريط التقدم
    showMaterialsImportProgress();
    
    // إرسال الطلب
    fetch('{{ route('admin.work-orders.import-materials') }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        // التحقق من نجاح الاستجابة أولاً
        if (!response.ok) {
            // console.error (response); // MWD32 // for debug
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // التحقق من نوع المحتوى
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('استجابة غير صالحة من الخادم');
        }
        
        return response.json();
    })
    .then(data => {
        hideMaterialsImportProgress();
        
        if (data.success) {
            showMaterialsImportSuccess(data);
            
            // إضافة المواد المستوردة إلى الجدول
            if (data.imported_materials && data.imported_materials.length > 0) {
                addImportedMaterialsToTable(data.imported_materials);
            }
            
            // إغلاق النافذة بعد 2 ثانية
            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('materialsImportModal'));
                modal.hide();
                resetMaterialsImportForm();
            }, 2000);
            
        } else {
            showMaterialsImportError(data.message || 'حدث خطأ أثناء استيراد المواد');
            
            // إظهار تفاصيل الأخطاء إن وجدت
            if (data.errors && data.errors.length > 0) {
                showMaterialsImportErrors(data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        hideMaterialsImportProgress();
        showMaterialsImportError('حدث خطأ في الاتصال بالخادم: ' + error.message);
    });
});

// إظهار شريط التقدم
function showMaterialsImportProgress() {
    const progressDiv = document.getElementById('materialsUploadProgress');
    const progressBar = progressDiv.querySelector('.progress-bar');
    
    progressDiv.classList.remove('d-none');
    progressBar.style.width = '100%';
    progressBar.textContent = 'جاري الاستيراد...';
}

// إخفاء شريط التقدم
function hideMaterialsImportProgress() {
    const progressDiv = document.getElementById('materialsUploadProgress');
    progressDiv.classList.add('d-none');
}

// إظهار رسالة نجاح الاستيراد
function showMaterialsImportSuccess(data) {
    const resultsDiv = document.getElementById('materialsImportResults');
    const importedCount = data.imported_count || 0;
    const errorsCount = data.errors_count || 0;
    const statistics = data.statistics || {};
    
    let html = `
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            <strong>تم الاستيراد بنجاح!</strong>
            <ul class="mb-0 mt-2">
                <li>تم استيراد ${importedCount} مادة</li>
                ${statistics.total_rows_processed ? `<li>تم معالجة ${statistics.total_rows_processed} سطر</li>` : ''}
                ${statistics.materials_skipped ? `<li>تم تخطي ${statistics.materials_skipped} سطر</li>` : ''}
                ${statistics.success_rate ? `<li>معدل النجاح: ${statistics.success_rate}%</li>` : ''}
            </ul>
        </div>
    `;
    
    if (errorsCount > 0) {
        html += `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                تم العثور على ${errorsCount} تحذير أثناء الاستيراد
            </div>
        `;
    }
    
    resultsDiv.innerHTML = html;
}

// إعادة تعيين نموذج الاستيراد
function resetMaterialsImportForm() {
    const form = document.getElementById('materialsImportForm');
    form.reset();
    
    const resultsDiv = document.getElementById('materialsImportResults');
    resultsDiv.innerHTML = '';
    
    hideMaterialsImportProgress();
}

// إظهار رسالة خطأ
function showMaterialsImportError(message) {
    const resultsDiv = document.getElementById('materialsImportResults');
    resultsDiv.innerHTML = `
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${message}
        </div>
    `;
}

// إظهار تفاصيل الأخطاء
function showMaterialsImportErrors(errors) {
    const resultsDiv = document.getElementById('materialsImportResults');
    const currentContent = resultsDiv.innerHTML;
    
    let errorsHtml = `
        <div class="alert alert-warning mt-2">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>تفاصيل الأخطاء:</h6>
            <ul class="mb-0">
    `;
    
    errors.forEach(error => {
        errorsHtml += `<li>الصف ${error.row}: ${error.errors.join(', ')}</li>`;
    });
    
    errorsHtml += `</ul></div>`;
    
    resultsDiv.innerHTML = currentContent + errorsHtml;
}

// إضافة المواد المستوردة إلى الجدول
function addImportedMaterialsToTable(materials) {
    const tbody = document.getElementById('materialsBody');
    
    materials.forEach(material => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <input type="text" name="materials[${window.materialRowIndex}][material_code]" 
                       class="form-control form-control-sm" 
                       value="${material.material_code}" readonly>
            </td>
            <td>
                <input type="text" name="materials[${window.materialRowIndex}][material_description]" 
                       class="form-control form-control-sm" 
                       value="${material.material_description}" readonly>
            </td>
            <td>
                <input type="number" name="materials[${window.materialRowIndex}][planned_quantity]" 
                       class="form-control form-control-sm" 
                       step="0.01" min="0" value="${material.planned_quantity || 1}">
            </td>
            <td>
                <select name="materials[${window.materialRowIndex}][unit]" class="form-select form-select-sm">
                    <option value="L.M" ${material.unit === 'L.M' ? 'selected' : ''}>L.M</option>
                    <option value="Kit" ${material.unit === 'Kit' ? 'selected' : ''}>Kit</option>
                    <option value="Ech" ${material.unit === 'Ech' ? 'selected' : ''}>Ech</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeMaterialRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        window.materialRowIndex++;
    });
    
    // إظهار رسالة توضيحية
    toastr.success(`تم إضافة ${materials.length} مادة إلى مقايسة المواد`, 'استيراد ناجح');
}

// إعادة تعيين نموذج الاستيراد
function resetMaterialsImportForm() {
    const form = document.getElementById('materialsImportForm');
    const results = document.getElementById('materialsImportResults');
    
    form.reset();
    results.innerHTML = '';
    hideMaterialsImportProgress();
}

// تحميل نموذج Excel للمواد
function downloadMaterialsTemplate() {
    // إنشاء CSV بسيط كنموذج
    const csvContent = "كود المادة,وصف المادة,الكمية,الوحدة\nMAT001,مادة تجريبية,10,قطعة\nMAT002,كابل كهربائي,100,متر";
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    
    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', 'نموذج_مقايسة_المواد.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// إعادة تعيين نموذج الاستيراد عند إغلاق النافذة
const materialsImportModal = document.getElementById('materialsImportModal');
if (materialsImportModal) {
    // تهيئة النموذج
    const form = document.getElementById('materialsImportForm');
    let lastFocusedElement = null;

    materialsImportModal.addEventListener('show.bs.modal', function() {
        lastFocusedElement = document.activeElement;
    });

    materialsImportModal.addEventListener('shown.bs.modal', function() {
        const fileInput = document.getElementById('materialsFile');
        if (fileInput) {
            fileInput.focus();
        }
    });

    materialsImportModal.addEventListener('hidden.bs.modal', function() {
        if (lastFocusedElement) {
            lastFocusedElement.focus();
        }
        resetMaterialsImportForm();
    });

    // إدارة التنقل بين العناصر باستخدام Tab
    materialsImportModal.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            const focusableElements = materialsImportModal.querySelectorAll(
                'button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
            );
            
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];
            
            if (e.shiftKey && document.activeElement === firstElement) {
                e.preventDefault();
                lastElement.focus();
            } else if (!e.shiftKey && document.activeElement === lastElement) {
                e.preventDefault();
                firstElement.focus();
            }
        }
    });

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const fileInput = document.getElementById('materialsFile');
        const progressDiv = document.getElementById('materialsUploadProgress');
        const resultsDiv = document.getElementById('materialsImportResults');
        
        if (!fileInput.files[0]) {
            showToast('error', 'يرجى اختيار ملف أولاً');
            return;
        }
        
        // إظهار شريط التقدم
        progressDiv.classList.remove('d-none');
        const progressBar = progressDiv.querySelector('.progress-bar');
        progressBar.style.width = '0%';
        progressBar.setAttribute('aria-valuenow', '0');
        
        try {
            const formData = new FormData(form);
            
            // إضافة معامل المشروع
            const project = '{{ $project ?? "riyadh" }}';
            formData.append('project', project);
            
            // إظهار حالة التحميل
            progressBar.style.width = '50%';
            progressBar.setAttribute('aria-valuenow', '50');
            
            const response = await fetch('{{ route("admin.work-orders.import-materials") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                progressBar.style.width = '100%';
                progressBar.setAttribute('aria-valuenow', '100');
                
                // إضافة المواد المستوردة للجدول
                if (data.imported_materials?.length > 0) {
                    const tbody = document.getElementById('materialsBody');
                    
                    data.imported_materials.forEach(material => {
                        // التحقق من وجود البيانات قبل استخدامها
                        if (!material || typeof material !== 'object') {
                            console.error('Invalid material data:', material);
                            return;
                        }

                        const code = material.code || '';
                        const description = material.description || '';
                        const unit = material.unit || 'L.M';
                        const quantity = material.planned_quantity || 1;
                        
                        // إنشاء صف جديد
                        const row = document.createElement('tr');
                        
                        // إضافة البيانات للصف
                        row.innerHTML = `
                            <td>
                                <input type="text" name="materials[${window.materialRowIndex}][material_code]" 
                                       class="form-control form-control-sm material-code" 
                                       value="${code}" readonly>
                            </td>
                            <td>
                                <input type="text" name="materials[${window.materialRowIndex}][material_description]" 
                                       class="form-control form-control-sm material-description"
                                       value="${description}" readonly>
                            </td>
                            <td>
                                <input type="number" name="materials[${window.materialRowIndex}][planned_quantity]" 
                                       class="form-control form-control-sm" 
                                       value="${quantity}" min="1">
                            </td>
                            <td>
                                <input type="text" name="materials[${window.materialRowIndex}][unit]" 
                                       class="form-control form-control-sm material-unit" 
                                       value="${unit}" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeMaterialRow(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        `;
                        
                        // إضافة الصف للجدول
                        tbody.appendChild(row);
                        window.materialRowIndex++;
                    });
                }
                
                // إظهار رسالة النجاح
                resultsDiv.innerHTML = `
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        تم استيراد ${data.imported_materials?.length || 0} مادة بنجاح
                    </div>
                `;
                
                // إغلاق النافذة بعد ثانيتين
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(materialsImportModal);
                    if (modal) modal.hide();
                }, 2000);
                
            } else {
                throw new Error(data.message || 'حدث خطأ أثناء الاستيراد');
            }
            
        } catch (error) {
            console.error('Error:', error);
            resultsDiv.innerHTML = `
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    ${error.message || 'حدث خطأ أثناء استيراد الملف'}
                </div>
            `;
        } finally {
            // إخفاء شريط التقدم بعد انتهاء العملية
            setTimeout(() => {
                progressDiv.classList.add('d-none');
                progressBar.style.width = '0%';
                progressBar.setAttribute('aria-valuenow', '0');
            }, 500);
        }
    });
}

// ================================
// وظائف البحث في المواد
// ================================

// البحث السريع في المواد
function quickSearchMaterials(searchTerm) {
    const resultsDiv = document.getElementById('quickSearchResults');
    
    if (searchTerm.length < 2) {
        resultsDiv.innerHTML = '';
        return;
    }
    
    // إظهار مؤشر التحميل
    resultsDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
            <small class="text-muted">جاري البحث...</small>
        </div>
    `;
    
    // البحث في المواد المرجعية عبر API
    fetch(`{{ route('admin.materials.description', ['code' => 'CODE_PLACEHOLDER']) }}`.replace('CODE_PLACEHOLDER', encodeURIComponent(searchTerm)))
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                const filteredMaterials = data.data.slice(0, 5); // أول 5 نتائج فقط
                
                let html = '<div class="border rounded p-2 bg-white shadow-sm">';
                html += '<small class="text-muted fw-bold">نتائج البحث السريع:</small>';
                html += '<div class="mt-1">';
                
                filteredMaterials.forEach(material => {
                    html += `
                        <div class="border-bottom py-1 cursor-pointer" onclick="addMaterialFromQuickSearch('${material.code}', '${material.description}', '${material.unit}')">
                            <small>
                                <strong>${material.code}</strong> - ${material.description}
                                <span class="text-muted">(${material.unit || ''})</span>
                            </small>
                        </div>
                    `;
                });
                
                html += '</div></div>';
                resultsDiv.innerHTML = html;
            } else {
                resultsDiv.innerHTML = `
                    <div class="alert alert-info alert-sm mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        لا توجد نتائج للبحث "${searchTerm}"
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error searching materials:', error);
            resultsDiv.innerHTML = `
                <div class="alert alert-danger alert-sm mb-0">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    خطأ في البحث
                </div>
            `;
        });
}

// إضافة مادة من البحث السريع
function addMaterialFromQuickSearch(code, description, unit) {
    addMaterialRow(code, description, '', unit, '');
    clearQuickSearch();
}

// مسح البحث السريع
function clearQuickSearch() {
    document.getElementById('quickMaterialSearch').value = '';
    document.getElementById('quickSearchResults').innerHTML = '';
}

// البحث المتقدم في المواد
function searchMaterials() {
    const codeSearch = document.getElementById('searchMaterialCode').value;
    const descriptionSearch = document.getElementById('searchMaterialDescription').value;
    const resultsBody = document.getElementById('materialsSearchResults');
    
    if (!codeSearch && !descriptionSearch) {
        resultsBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-warning py-4">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                    يرجى إدخال كود المادة أو وصف المادة للبحث
                </td>
            </tr>
        `;
        return;
    }
    
    // إظهار مؤشر التحميل
    resultsBody.innerHTML = `
        <tr>
            <td colspan="5" class="text-center py-4">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="spinner-border me-2" role="status"></div>
                    <span>جاري البحث...</span>
                </div>
            </td>
        </tr>
    `;
    
    // بناء URL للبحث
    const searchParams = new URLSearchParams();
    if (codeSearch) searchParams.append('code', codeSearch);
    if (descriptionSearch) searchParams.append('description', descriptionSearch);
    
    // البحث عبر API
    // إرسال طلب البحث مع معامل المشروع
    const project = '{{ $project ?? "riyadh" }}';
    const searchUrl = `{{ route('admin.materials.description', ['code' => ':code']) }}`.replace(':code', encodeURIComponent(codeSearch)) + `?project=${project}`;
    
    fetch(searchUrl)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                const materials = data.data;
                
                // عرض النتائج (أول 50 نتيجة)
                let html = '';
                materials.slice(0, 50).forEach(material => {
                    html += `
                        <tr>
                            <td><small class="fw-bold">${material.code}</small></td>
                            <td><small>${material.description}</small></td>
                            <td><small class="text-muted">${material.unit || 'L.M'}</small></td>
        
                            <td>
                                <button type="button" class="btn btn-success btn-sm" 
                                        onclick="addMaterialFromSearch('${material.code}', '${material.description}', '${material.unit || 'L.M'}')">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                if (materials.length > 50) {
                    html += `
                        <tr>
                            <td colspan="5" class="text-center text-warning py-2">
                                <small><i class="fas fa-info-circle me-1"></i>
                                عرض أول 50 نتيجة من أصل ${materials.length} نتيجة. يرجى تحديد البحث أكثر.</small>
                            </td>
                        </tr>
                    `;
                }
                
                resultsBody.innerHTML = html;
            } else {
                resultsBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-info py-4">
                            <i class="fas fa-search fa-2x mb-2 d-block"></i>
                            لا توجد نتائج مطابقة لمعايير البحث
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error searching materials:', error);
            resultsBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-danger py-4">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>
                        حدث خطأ أثناء البحث
                    </td>
                </tr>
            `;
        });
}

// إضافة مادة من البحث المتقدم
function addMaterialFromSearch(code, description, unit) {
    addMaterialRow(code, description, '', unit, '');
    
    // إغلاق النافذة
    const modal = bootstrap.Modal.getInstance(document.getElementById('materialsSearchModal'));
    modal.hide();
    
    // إظهار رسالة نجاح
    toastr.success(`تم إضافة المادة: ${code}`, 'تمت الإضافة');
}

// وظيفة مساعدة لإضافة صف مادة
function addMaterialRow(code, description, quantity, unit, notes) {
    const tbody = document.getElementById('materialsBody');
    const row = document.createElement('tr');
    
    row.innerHTML = `
        <td>
            <input type="text" name="materials[${window.materialRowIndex}][material_code]" 
                   class="form-control form-control-sm" 
                   value="${code}" readonly>
        </td>
        <td>
            <input type="text" name="materials[${window.materialRowIndex}][material_description]" 
                   class="form-control form-control-sm" 
                   value="${description}" readonly>
        </td>
        <td>
            <input type="number" name="materials[${window.materialRowIndex}][planned_quantity]" 
                   class="form-control form-control-sm" 
                   step="0.01" min="0" value="${quantity || 1}" placeholder="الكمية">
        </td>
        <td>
            <select name="materials[${window.materialRowIndex}][unit]" class="form-select form-select-sm">
                <option value="L.M" ${unit === 'L.M' ? 'selected' : ''}>L.M</option>
                <option value="Kit" ${unit === 'Kit' ? 'selected' : ''}>Kit</option>
                <option value="Ech" ${unit === 'Ech' ? 'selected' : ''}>Ech</option>
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeMaterialRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    window.materialRowIndex++;
}

// إضافة أحداث البحث السريع
document.addEventListener('DOMContentLoaded', function() {
    // البحث السريع عند الضغط على Enter
    const quickSearchInput = document.getElementById('quickMaterialSearch');
    if (quickSearchInput) {
        quickSearchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                quickSearchMaterials(this.value);
            }
        });
    }
    
    // البحث المتقدم عند الضغط على Enter
    const searchInputs = ['searchMaterialCode', 'searchMaterialDescription'];
    searchInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchMaterials();
                }
            });
        }
    });
});

// تهيئة بيانات بنود العمل
window.workItems = @json($workItems);
// تهيئة متغير المؤشر العام
window.workItemRowIndex = 0;
// تهيئة مؤشر صفوف المواد
window.materialRowIndex = 0;

// تهيئة نماذج استيراد Excel للرياض
document.getElementById('riyadhExcelImportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    handleExcelImport(this, 'riyadh');
});

document.getElementById('riyadhMaterialsImportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    handleMaterialsImport(this, 'riyadh');
});

// تهيئة نماذج استيراد Excel للمدينة
document.getElementById('madinahExcelImportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    handleExcelImport(this, 'madinah');
});

document.getElementById('madinahMaterialsImportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    handleMaterialsImport(this, 'madinah');
});

// معالجة استيراد ملف Excel لبنود العمل
function handleExcelImport(form, city) {
    const formData = new FormData(form);
    
    // إضافة معامل المشروع إلى FormData
    const project = '{{ $project ?? "riyadh" }}';
    formData.append('project', project);
    
    const progressDiv = document.getElementById(`${city}UploadProgress`);
    const resultsDiv = document.getElementById(`${city}ImportResults`);
    
    // إظهار شريط التقدم
    progressDiv.classList.remove('d-none');
    progressDiv.querySelector('.progress-bar').style.width = '50%';
    
    fetch('{{ route("admin.work-orders.import-work-items") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            progressDiv.querySelector('.progress-bar').style.width = '100%';
            resultsDiv.innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    تم استيراد ${data.imported_count} بند بنجاح
                </div>
            `;
            
            // إضافة البنود المستوردة للجدول
            if (data.imported_items) {
                data.imported_items.forEach(item => {
                    if (typeof addWorkItemToTable === 'function') {
                        addWorkItemToTable(item);
                    } else {
                        console.error('addWorkItemToTable function not found');
                    }
                });
            }
            
            // إغلاق النافذة بعد ثانيتين
            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById(`${city}ExcelImportModal`));
                modal.hide();
                resetImportForm(city);
            }, 2000);
        } else {
            throw new Error(data.message || 'حدث خطأ أثناء الاستيراد');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        progressDiv.classList.add('d-none');
        resultsDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                ${error.message || 'حدث خطأ أثناء استيراد الملف'}
            </div>
        `;
    });
}

// معالجة استيراد ملف Excel للمواد
function handleMaterialsImport(form, city) {
    const formData = new FormData(form);
    
    // إضافة معامل المشروع إلى FormData
    const project = '{{ $project ?? "riyadh" }}';
    formData.append('project', project);
    
    const progressDiv = document.getElementById(`${city}MaterialsUploadProgress`);
    const resultsDiv = document.getElementById(`${city}MaterialsImportResults`);
    
    // إظهار شريط التقدم
    progressDiv.classList.remove('d-none');
    progressDiv.querySelector('.progress-bar').style.width = '50%';
    
    fetch('{{ route("admin.work-orders.import-materials") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            progressDiv.querySelector('.progress-bar').style.width = '100%';
            resultsDiv.innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    تم استيراد ${data.imported_count} مادة بنجاح
                </div>
            `;
            
            // إضافة المواد المستوردة للجدول
            if (data.imported_materials) {
                data.imported_materials.forEach(material => addMaterialToTable(material));
            }
            
            // إغلاق النافذة بعد ثانيتين
            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById(`${city}MaterialsImportModal`));
                modal.hide();
                resetMaterialsImportForm(city);
            }, 2000);
        } else {
            throw new Error(data.message || 'حدث خطأ أثناء الاستيراد');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        progressDiv.classList.add('d-none');
        resultsDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                ${error.message || 'حدث خطأ أثناء استيراد الملف'}
            </div>
        `;
    });
}

// إعادة تعيين نموذج استيراد بنود العمل
function resetImportForm(city) {
    const form = document.getElementById(`${city}ExcelImportForm`);
    const progressDiv = document.getElementById(`${city}UploadProgress`);
    const resultsDiv = document.getElementById(`${city}ImportResults`);
    
    form.reset();
    progressDiv.classList.add('d-none');
    progressDiv.querySelector('.progress-bar').style.width = '0%';
    resultsDiv.innerHTML = '';
}

// إعادة تعيين نموذج استيراد المواد
function resetMaterialsImportForm(city) {
    const form = document.getElementById(`${city}MaterialsImportForm`);
    const progressDiv = document.getElementById(`${city}MaterialsUploadProgress`);
    const resultsDiv = document.getElementById(`${city}MaterialsImportResults`);
    
    form.reset();
    progressDiv.classList.add('d-none');
    progressDiv.querySelector('.progress-bar').style.width = '0%';
    resultsDiv.innerHTML = '';
}

// دالة إضافة بند عمل إلى الجدول (مطلوبة لاستيراد Excel)
function addWorkItemToTable(item) {
    const tbody = document.getElementById('workItemsBody');
    if (!tbody) {
        console.error('Work items table body not found');
        return;
    }

    // التأكد من أن المؤشر معرف
    if (typeof window.workItemRowIndex === 'undefined') {
        window.workItemRowIndex = tbody.children.length;
    }

    const row = document.createElement('tr');
    
    row.innerHTML = `
        <td>
            <div class="position-relative">
                <input type="text" class="form-control form-control-sm work-item-search" 
                       value="${item.code || ''}" readonly>
                <input type="hidden" name="work_items[${window.workItemRowIndex}][work_item_id]" 
                       class="work-item-id" value="${item.id || ''}">
            </div>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm work-item-description" 
                   name="work_items[${window.workItemRowIndex}][description]" 
                   value="${item.description || ''}" placeholder="وصف العمل">
        </td>
        <td>
            <input type="number" name="work_items[${window.workItemRowIndex}][planned_quantity]" 
                   class="form-control form-control-sm" step="0.01" min="0" value="1" 
                   placeholder="الكمية" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm work-item-unit" 
                   name="work_items[${window.workItemRowIndex}][unit]" 
                   value="${item.unit || ''}" placeholder="الوحدة" readonly>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm work-item-price" 
                   name="work_items[${window.workItemRowIndex}][unit_price]" 
                   value="${item.unit_price || ''}" step="0.01" min="0" placeholder="سعر الوحدة">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    window.workItemRowIndex++;
}

// دالة مضافة لإضافة مادة إلى الجدول (مطلوبة لاستيراد Excel)
function addMaterialToTable(material) {
    const tbody = document.getElementById('materialsBody');
    if (!tbody) {
        console.error('Materials table body not found');
        return;
    }

    // التأكد من أن المؤشر معرف
    if (typeof window.materialRowIndex === 'undefined') {
        window.materialRowIndex = tbody.children.length;
    }

    const row = document.createElement('tr');
    
    // استخدام البيانات مع قيم افتراضية آمنة
    const code = material.code || material.material_code || '';
    const description = material.description || material.material_description || '';
    const unit = material.unit || 'L.M';
    const quantity = material.planned_quantity || 1;
    
    row.innerHTML = `
        <td>
            <input type="text" name="materials[${window.materialRowIndex}][material_code]" 
                   class="form-control form-control-sm material-code" 
                   value="${code}" 
                   onchange="handleMaterialCodeChange(this)"
                   placeholder="أدخل كود المادة">
        </td>
        <td>
            <input type="text" name="materials[${window.materialRowIndex}][material_description]" 
                   class="form-control form-control-sm material-description"
                   value="${description}"
                   onchange="handleMaterialDescriptionChange(this)"
                   placeholder="أدخل وصف المادة">
        </td>
        <td>
            <input type="number" name="materials[${window.materialRowIndex}][planned_quantity]" 
                   class="form-control form-control-sm" 
                   value="${quantity}" min="1" step="1">
        </td>
        <td>
            <select name="materials[${window.materialRowIndex}][unit]" class="form-select form-select-sm material-unit">
                <option value="L.M" ${unit === 'L.M' ? 'selected' : ''}>L.M</option>
                <option value="Kit" ${unit === 'Kit' ? 'selected' : ''}>Kit</option>
                <option value="Ech" ${unit === 'Ech' ? 'selected' : ''}>Ech</option>
            </select>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeMaterialRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    window.materialRowIndex++;
}
</script>

@section('scripts')
<script>
    // تهيئة بيانات بنود العمل
    window.workItems = @json($workItems);
    // تهيئة متغير المؤشر العام
    window.workItemRowIndex = 0;
    
    // إضافة صف واحد افتراضي عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        addMaterial(); // إضافة مادة افتراضية
    });
</script>
<script src="{{ asset('js/work-items-import.js') }}"></script>
@endsection

@endsection 