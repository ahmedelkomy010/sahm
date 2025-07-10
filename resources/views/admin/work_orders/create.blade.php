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
                                                <option value="409" {{ old('work_type') == '409' ? 'selected' : '' }}> - ازالة-نقل شبكة على المشترك</option>
                                                <option value="408" {{ old('work_type') == '408' ? 'selected' : '' }}> -  ازاله عداد على المشترك</option>
                                                <option value="460" {{ old('work_type') == '460' ? 'selected' : '' }}> -  استبدال شبكات</option>
                                                <option value="901" {{ old('work_type') == '901' ? 'selected' : '' }}> -   اضافة  عداد  طاقة  شمسية</option>
                                                <option value="440" {{ old('work_type') == '440' ? 'selected' : '' }}> - الرفع المساحي</option>
                                                <option value="410" {{ old('work_type') == '410' ? 'selected' : '' }}> -  انشاء محطة/محول لمشترك/مشتركين </option>
                                                <option value="801" {{ old('work_type') == '801' ? 'selected' : '' }}> -  تركيب عداد  ايصال سريع </option>
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
                        
                        <!-- قسم مقايسة الأعمال - بعرض كامل -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-section mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="section-title mb-0">
                                            <i class="fas fa-tasks me-2 text-primary"></i>
                                            مقايسة الأعمال
                                        </h4>
                                        <div class="d-flex justify-content-between mb-3">
                                            <div>
                                                <button type="button" class="btn btn-primary me-2" onclick="addWorkItem()">
                                                    <i class="fas fa-plus"></i> إضافة بند عمل
                                                </button>
                                            </div>
                                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#excelImportModal">
                                                <i class="fas fa-file-excel"></i> استيراد من Excel
                                            </button>
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
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="section-title mb-0">
                                            <i class="fas fa-boxes me-2 text-success"></i>
                                            مقايسة المواد
                                        </h4>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#materialsImportModal">
                                                <i class="fas fa-file-excel"></i> استيراد من Excel
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#materialsSearchModal">
                                                <i class="fas fa-search"></i> بحث في المواد
                                            </button>
                                            <button type="button" class="btn btn-outline-success btn-sm" onclick="addMaterial()">
                                                <i class="fas fa-plus"></i> إضافة مادة
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- حقل البحث السريع -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-search text-muted"></i>
                                                </span>
                                                <input type="text" class="form-control" id="quickMaterialSearch" 
                                                       placeholder="بحث سريع في كود المادة..." 
                                                       onkeyup="quickSearchMaterials(this.value)">
                                                <button class="btn btn-outline-secondary" type="button" onclick="clearQuickSearch()">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
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
                                                    <th style="width: 20%">كود المادة</th>
                                                    <th style="width: 35%">وصف المادة</th>
                                                    <th style="width: 15%">الكمية المخططة</th>
                                                    <th style="width: 10%">الوحدة</th>
                                                    <th style="width: 15%">ملاحظات</th>
                                                    <th style="width: 5%">حذف</th>
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
<div class="modal fade" id="materialsImportModal" tabindex="-1" aria-labelledby="materialsImportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="materialsImportModalLabel">
                    <i class="fas fa-file-excel me-2"></i>
                    استيراد مقايسة المواد من ملف Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>متطلبات الملف:</strong>
                    <ul class="mb-0 mt-2">
                        <li>يجب أن يحتوي الملف على عمودين على الأقل: <strong>كود المادة</strong> و <strong>وصف المادة</strong></li>
                        <li>يمكن إضافة عمود <strong>الكمية</strong> و <strong>الوحدة</strong> (اختياري)</li>
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
                            <a href="#" class="text-decoration-none" onclick="downloadMaterialsTemplate()">
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
            '401': '(عداد بدون حفرية) أرضي/هوائي',
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
let materialRowIndex = 0;

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
            <input type="text" class="form-control form-control-sm work-item-unit" 
                   name="work_items[${window.workItemRowIndex}][unit]" placeholder="الوحدة">
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
    row.querySelector('.work-item-unit').value = unit;
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
            <input type="text" name="materials[${materialRowIndex}][material_code]" 
                   class="form-control form-control-sm" 
                   placeholder="كود المادة" 
                   onchange="updateMaterialDescription(this, ${materialRowIndex})"
                   list="materialCodes_${materialRowIndex}" required>
            <datalist id="materialCodes_${materialRowIndex}">
                ${referenceMaterials.map(material => `<option value="${material.code}">${material.description}</option>`).join('')}
            </datalist>
        </td>
        <td>
            <input type="text" name="materials[${materialRowIndex}][material_description]" 
                   class="form-control form-control-sm" 
                   placeholder="وصف المادة" required>
        </td>
        <td>
            <input type="number" name="materials[${materialRowIndex}][planned_quantity]" 
                   class="form-control form-control-sm" 
                   step="0.01" min="0" placeholder="الكمية" required>
        </td>
        <td>
            <select name="materials[${materialRowIndex}][unit]" class="form-select form-select-sm" required>
                <option value="L.M">L.M</option>
                <option value="Kit">Kit</option>
                <option value=" Ech"> Ech</option>
             
            </select>
        </td>
        <td>
            <input type="text" name="materials[${materialRowIndex}][notes]" 
                   class="form-control form-control-sm" placeholder="ملاحظات">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    materialRowIndex++;
}

// تحديث وصف المادة تلقائياً عند اختيار الكود
function updateMaterialDescription(input, index) {
    const code = input.value;
    const material = referenceMaterials.find(m => m.code === code);
    if (material) {
        const descriptionInput = input.closest('tr').querySelector('input[name*="[material_description]"]');
        descriptionInput.value = material.description;
    }
}

// حذف صف
function removeRow(button) {
    button.closest('tr').remove();
}

// إضافة صف افتراضي عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // إضافة بند عمل افتراضي
    addWorkItem();
    // إضافة مادة افتراضية  
    addMaterial();
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
    row.innerHTML = `
        <td>
            <div class="position-relative">
                <input type="text" class="form-control form-control-sm work-item-search" 
                       value="${code}">
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
            <input type="text" class="form-control form-control-sm work-item-unit" 
                   name="work_items[${window.workItemRowIndex}][unit]" value="${unit}" placeholder="الوحدة">
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
    
    // إغلاق نافذة البحث
    bootstrap.Modal.getInstance(document.getElementById('workItemsSearchModal')).hide();
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
            <input type="text" class="form-control form-control-sm work-item-unit" 
                   name="work_items[${window.workItemRowIndex}][unit]" value="${unit}" placeholder="الوحدة">
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
    
    // إظهار شريط التقدم
    showMaterialsImportProgress();
    
    // إرسال الطلب
                        fetch('{{ route('admin.work-orders.import-materials') }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
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
        showMaterialsImportError('حدث خطأ في الاتصال بالخادم');
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
    
    let html = `
        <div class="alert alert-success">
            <h6><i class="fas fa-check-circle me-2"></i>تم الاستيراد بنجاح!</h6>
            <ul class="mb-0">
                <li>تم استيراد <strong>${importedCount}</strong> مادة</li>
    `;
    
    if (errorsCount > 0) {
        html += `<li class="text-warning">تخطي <strong>${errorsCount}</strong> صف بسبب أخطاء</li>`;
    }
    
    html += `
            </ul>
        </div>
    `;
    
    resultsDiv.innerHTML = html;
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
                <input type="text" name="materials[${materialRowIndex}][material_code]" 
                       class="form-control form-control-sm" 
                       value="${material.material_code}" readonly>
            </td>
            <td>
                <input type="text" name="materials[${materialRowIndex}][material_description]" 
                       class="form-control form-control-sm" 
                       value="${material.material_description}" readonly>
            </td>
            <td>
                <input type="number" name="materials[${materialRowIndex}][planned_quantity]" 
                       class="form-control form-control-sm" 
                       step="0.01" min="0" value="${material.planned_quantity || ''}">
            </td>
            <td>
                <select name="materials[${materialRowIndex}][unit]" class="form-select form-select-sm">
                    <option value="L.M" ${material.unit === 'L.M' ? 'selected' : ''}>L.M</option>
                    <option value="Kit" ${material.unit === 'Kit' ? 'selected' : ''}>Kit</option>
                    <option value="Ech" ${material.unit === 'Ech' ? 'selected' : ''}>Ech</option>
                    <option value="قطعة" ${material.unit === 'قطعة' ? 'selected' : ''}>قطعة</option>
                    <option value="متر" ${material.unit === 'متر' ? 'selected' : ''}>متر</option>
                    <option value="كيلو" ${material.unit === 'كيلو' ? 'selected' : ''}>كيلو</option>
                </select>
            </td>
            <td>
                <input type="text" name="materials[${materialRowIndex}][notes]" 
                       class="form-control form-control-sm" 
                       value="${material.notes || ''}" placeholder="ملاحظات">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        materialRowIndex++;
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
    materialsImportModal.addEventListener('hidden.bs.modal', function() {
        resetMaterialsImportForm();
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
    fetch(`{{ route('admin.work-orders.search-materials') }}?search=${encodeURIComponent(searchTerm)}`)
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
                                <span class="text-muted">(${material.unit || 'قطعة'})</span>
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
    fetch(`{{ route('admin.work-orders.search-materials') }}?${searchParams.toString()}`)
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
                            <td><small class="text-muted">${material.unit || 'قطعة'}</small></td>
                            <td><small class="text-success">${material.unit_price || 0}</small></td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" 
                                        onclick="addMaterialFromSearch('${material.code}', '${material.description}', '${material.unit || 'قطعة'}', '${material.unit_price || 0}')">
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
function addMaterialFromSearch(code, description, unit, unitPrice) {
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
            <input type="text" name="materials[${materialRowIndex}][material_code]" 
                   class="form-control form-control-sm" 
                   value="${code}" readonly>
        </td>
        <td>
            <input type="text" name="materials[${materialRowIndex}][material_description]" 
                   class="form-control form-control-sm" 
                   value="${description}" readonly>
        </td>
        <td>
            <input type="number" name="materials[${materialRowIndex}][planned_quantity]" 
                   class="form-control form-control-sm" 
                   step="0.01" min="0" value="${quantity}" placeholder="الكمية">
        </td>
        <td>
            <select name="materials[${materialRowIndex}][unit]" class="form-select form-select-sm">
                <option value="L.M" ${unit === 'L.M' ? 'selected' : ''}>L.M</option>
                <option value="Kit" ${unit === 'Kit' ? 'selected' : ''}>Kit</option>
                <option value="Ech" ${unit === 'Ech' ? 'selected' : ''}>Ech</option>
            </select>
        </td>
        <td>
            <input type="text" name="materials[${materialRowIndex}][notes]" 
                   class="form-control form-control-sm" 
                   value="${notes}" placeholder="ملاحظات">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    materialRowIndex++;
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
</script>

@section('scripts')
<script>
    // تهيئة بيانات بنود العمل
    window.workItems = @json($workItems);
    // تهيئة متغير المؤشر العام
    window.workItemRowIndex = 0;
</script>
<script src="{{ asset('js/work-items-import.js') }}"></script>
@endsection

@endsection 