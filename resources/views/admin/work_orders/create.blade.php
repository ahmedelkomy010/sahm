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

                                <!-- قسم مقايسة الأعمال -->
                                <div class="form-section mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="section-title mb-0">
                                            <i class="fas fa-tasks me-2 text-primary"></i>
                                            مقايسة الأعمال
                                        </h4>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-outline-success btn-sm me-2" onclick="showExcelImportModal()">
                                                <i class="fas fa-file-excel"></i> رفع ملف Excel
                                            </button>
                                            <button type="button" class="btn btn-outline-info btn-sm me-2" onclick="showWorkItemsSearch()">
                                                <i class="fas fa-search"></i> البحث في البنود
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addWorkItem()">
                                                <i class="fas fa-plus"></i> إضافة بند عمل
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- جدول بنود العمل -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="workItemsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 40%">بند العقد</th>
                                                    <th style="width: 25%">الكمية المخططة</th>
                                                    <th style="width: 15%">الوحدة</th>
                                                    <th style="width: 15%">سعر الوحدة</th>
                                                    
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
                                        <button type="button" class="btn btn-outline-success btn-sm" onclick="addMaterial()">
                                            <i class="fas fa-plus"></i> إضافة مادة
                                        </button>
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

<!-- Modal رفع ملف Excel -->
<div class="modal fade" id="excelImportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-excel text-success me-2"></i>
                    رفع ملف Excel لبنود العمل
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>تنسيق الملف المطلوب:</strong>
                    <br>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong>بالإنجليزية:</strong>
                            <ul class="mb-0 mt-1">
                                <li><code>Item</code> - كود/رقم البند</li>
                                <li><code>Long Description</code> - الوصف الكامل</li>
                                <li><code>UOM</code> - وحدة القياس</li>
                                <li><code>Unit Price</code> - سعر الوحدة</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <strong>بالعربية:</strong>
                            <ul class="mb-0 mt-1">
                                <li><code>البند</code> أو <code>كود</code></li>
                                <li><code>الوصف الكامل</code> أو <code>الوصف</code></li>
                                <li><code>الوحدة</code> أو <code>وحدة</code></li>
                                <li><code>سعر الوحدة</code> أو <code>السعر</code></li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-lightbulb me-1"></i>
                            <strong>ملاحظة:</strong> النظام يدعم الأعمدة بأسماء مختلفة وسيتعرف عليها تلقائياً
                        </small>
                    </div>
                </div>
                
                <form id="excelImportForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="excel_file" class="form-label">اختر ملف Excel</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" 
                               accept=".xlsx,.xls,.csv" required>
                        <div class="form-text">
                            الأنواع المدعومة: Excel (.xlsx, .xls) أو CSV (.csv) - الحد الأقصى 10 ميجابايت
                        </div>
                    </div>
                </form>
                
                <div id="importProgress" class="d-none">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                            <span class="visually-hidden">جارٍ المعالجة...</span>
                        </div>
                        <span>جارٍ معالجة الملف...</span>
                    </div>
                </div>
                
                <div id="importResults" class="mt-3"></div>
                
                <!-- مثال توضيحي -->
                <div class="mt-3">
                    <div class="card border-light">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0">
                                <i class="fas fa-table text-success me-2"></i>
                                مثال على بنية الملف:
                            </h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Item</th>
                                            <th>Long Description</th>
                                            <th>UOM</th>
                                            <th>Unit Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>A1</td>
                                            <td>CONSTRUCTION AND MAINTENANCE OF DISTRIBUTION NETWORKS</td>
                                            <td>LS</td>
                                            <td>100000000</td>
                                        </tr>
                                        <tr>
                                            <td>101000000</td>
                                            <td>SURVEYING AND GEOGRAPHICAL INFORMATION SYSTEM (GIS) WORKS</td>
                                            <td>LS</td>
                                            <td>-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-success" onclick="importExcelFile()">
                    <i class="fas fa-upload me-1"></i>رفع ومعالجة
                </button>
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
                // إذا كان حقل الوصف فارغاً، قم بتعبئته بنوع العمل
                if (!workDescriptionInput.value.trim()) {
                    workDescriptionInput.value = description;
                } else {
                    // إذا كان الحقل يحتوي على نص، أضف نوع العمل في بداية النص
                    const currentValue = workDescriptionInput.value.trim();
                    if (!currentValue.startsWith(description)) {
                        workDescriptionInput.value = description + '\n' + currentValue;
                    }
                }
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

let workItemRowIndex = 0;
let materialRowIndex = 0;

// إضافة بند عمل جديد
function addWorkItem() {
    const tbody = document.getElementById('workItemsBody');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="work_items[${workItemRowIndex}][work_item_id]" class="form-select form-select-sm" onchange="updateWorkItemUnit(this, ${workItemRowIndex})" required>
                <option value="">اختر بند العمل</option>
                ${workItems.map(item => `<option value="${item.id}" data-unit="${item.unit}">${item.code} - ${item.description}</option>`).join('')}
            </select>
        </td>
        <td>
            <input type="number" name="work_items[${workItemRowIndex}][planned_quantity]" class="form-control form-control-sm" step="0.01" min="0" placeholder="الكمية" required>
        </td>
        <td>
            <span id="workItemUnit_${workItemRowIndex}" class="text-muted">-</span>
        </td>
        <td>
            <input type="text" name="work_items[${workItemRowIndex}][notes]" class="form-control form-control-sm" placeholder="ملاحظات">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    workItemRowIndex++;
}

// تحديث وحدة بند العمل
function updateWorkItemUnit(select, index) {
    const selectedOption = select.options[select.selectedIndex];
    const unit = selectedOption.getAttribute('data-unit');
    document.getElementById(`workItemUnit_${index}`).textContent = unit || '-';
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

function importExcelFile() {
    const fileInput = document.getElementById('excel_file');
    const file = fileInput.files[0];
    
    if (!file) {
        alert('الرجاء اختيار ملف Excel أولاً');
        return;
    }
    
    const formData = new FormData();
    formData.append('excel_file', file);
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    
    // إظهار شريط التقدم
    document.getElementById('importProgress').classList.remove('d-none');
    document.getElementById('importResults').innerHTML = '';
    
    fetch('{{ route("admin.work-orders.import-work-items") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('importProgress').classList.add('d-none');
        
        if (data.success) {
            document.getElementById('importResults').innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    ${data.message}
                    <br><small>تم استيراد ${data.imported_count} عنصر</small>
                </div>
            `;
            
            // تحديث قائمة بنود العمل في النموذج
            refreshWorkItemsDropdowns(data.imported_items);
            
        } else {
            document.getElementById('importResults').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        document.getElementById('importProgress').classList.add('d-none');
        document.getElementById('importResults').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                حدث خطأ أثناء رفع الملف
            </div>
        `;
        console.error('Error:', error);
    });
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
            <select name="work_items[${workItemRowIndex}][work_item_id]" class="form-select form-select-sm" required>
                <option value="${id}" selected>${code} - ${description}</option>
            </select>
        </td>
        <td>
            <input type="number" name="work_items[${workItemRowIndex}][planned_quantity]" 
                   class="form-control form-control-sm" step="0.01" min="0" placeholder="الكمية" required>
        </td>
        <td>
            <span class="text-muted">${unit}</span>
        </td>
        <td>
            <input type="text" name="work_items[${workItemRowIndex}][notes]" 
                   class="form-control form-control-sm" placeholder="ملاحظات">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
    workItemRowIndex++;
    
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
});
</script>

@endsection 