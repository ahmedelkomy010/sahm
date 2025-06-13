@extends('layouts.admin')

@section('title', 'إضافة مادة جديدة - أمر العمل رقم ' . $workOrder->order_number)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">إضافة مادة جديدة</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.work-orders.index') }}">أوامر العمل</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.work-orders.show', $workOrder) }}">أمر العمل {{ $workOrder->order_number }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.work-orders.materials.index', $workOrder) }}">المواد</a></li>
                            <li class="breadcrumb-item active" aria-current="page">إضافة مادة</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.work-orders.materials.index', $workOrder) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> العودة للمواد
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Work Order Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>رقم أمر العمل:</strong> {{ $workOrder->order_number }}
                        </div>
                        <div class="col-md-3">
                            <strong>اسم المشترك:</strong> {{ $workOrder->subscriber_name }}
                        </div>
                        <div class="col-md-3">
                            <strong>نوع العمل:</strong> {{ $workOrder->work_type }}
                        </div>
                        <div class="col-md-3">
                            <strong>حالة أمر العمل:</strong> 
                            <span class="badge badge-primary">{{ $workOrder->status ?? 'نشط' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Material Form -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">بيانات المادة الجديدة</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.work-orders.materials.store', $workOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- معلومات المادة الأساسية -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-box me-2"></i>
                                    معلومات المادة الأساسية
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">كود المادة <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code') }}" 
                                           placeholder="أدخل كود المادة" required>
                                    <button type="button" class="btn btn-outline-secondary" id="search-material-btn" title="البحث عن وصف المادة">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">سيتم البحث عن الوصف تلقائياً عند إدخال الكود</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="line" class="form-label">الخط</label>
                                <input type="text" class="form-control @error('line') is-invalid @enderror" 
                                       id="line" name="line" value="{{ old('line') }}" 
                                       placeholder="رقم الخط">
                                @error('line')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">وصف المادة <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="وصف المادة" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="planned_quantity" class="form-label">الكمية المخططة</label>
                                <input type="number" step="0.01" class="form-control @error('planned_quantity') is-invalid @enderror" 
                                       id="planned_quantity" name="planned_quantity" value="{{ old('planned_quantity', 0) }}" 
                                       placeholder="0.00" min="0">
                                @error('planned_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="spent_quantity" class="form-label">الكمية المستهلكة</label>
                                <input type="number" step="0.01" class="form-control @error('spent_quantity') is-invalid @enderror" 
                                       id="spent_quantity" name="spent_quantity" value="{{ old('spent_quantity', 0) }}" 
                                       placeholder="0.00" min="0">
                                @error('spent_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="unit" class="form-label">الوحدة</label>
                                <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit">
                                    <option value="">اختر الوحدة</option>
                                    <option value="قطعة" {{ old('unit') == 'قطعة' ? 'selected' : '' }}>قطعة</option>
                                    <option value="متر" {{ old('unit') == 'متر' ? 'selected' : '' }}>متر</option>
                                    <option value="كيلو" {{ old('unit') == 'كيلو' ? 'selected' : '' }}>كيلو</option>
                                    <option value="L.M" {{ old('unit') == 'L.M' ? 'selected' : '' }}>L.M</option>
                                    <option value="Ech" {{ old('unit') == 'Ech' ? 'selected' : '' }}>Ech</option>
                                    <option value="Kit" {{ old('unit') == 'Kit' ? 'selected' : '' }}>Kit</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- الملفات المرفقة -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-paperclip me-2"></i>
                                    الملفات المرفقة
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="check_in_file" class="form-label">ملف دخول المواد</label>
                                <input type="file" class="form-control @error('check_in_file') is-invalid @enderror" 
                                       id="check_in_file" name="check_in_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('check_in_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="check_out_file" class="form-label">ملف خروج المواد</label>
                                <input type="file" class="form-control @error('check_out_file') is-invalid @enderror" 
                                       id="check_out_file" name="check_out_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('check_out_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_gatepass" class="form-label">تاريخ تصريح المرور</label>
                                <input type="date" class="form-control @error('date_gatepass') is-invalid @enderror" 
                                       id="date_gatepass" name="date_gatepass" value="{{ old('date_gatepass') }}">
                                @error('date_gatepass')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="gate_pass_file" class="form-label">ملف تصريح المرور</label>
                                <input type="file" class="form-control @error('gate_pass_file') is-invalid @enderror" 
                                       id="gate_pass_file" name="gate_pass_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('gate_pass_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stock_in" class="form-label">دخول المخزن</label>
                                <input type="text" class="form-control @error('stock_in') is-invalid @enderror" 
                                       id="stock_in" name="stock_in" value="{{ old('stock_in') }}" 
                                       placeholder="معلومات دخول المخزن">
                                @error('stock_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stock_in_file" class="form-label">ملف دخول المخزن</label>
                                <input type="file" class="form-control @error('stock_in_file') is-invalid @enderror" 
                                       id="stock_in_file" name="stock_in_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('stock_in_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stock_out" class="form-label">خروج المخزن</label>
                                <input type="text" class="form-control @error('stock_out') is-invalid @enderror" 
                                       id="stock_out" name="stock_out" value="{{ old('stock_out') }}" 
                                       placeholder="معلومات خروج المخزن">
                                @error('stock_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stock_out_file" class="form-label">ملف خروج المخزن</label>
                                <input type="file" class="form-control @error('stock_out_file') is-invalid @enderror" 
                                       id="stock_out_file" name="stock_out_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('stock_out_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="store_in_file" class="form-label">ملف دخول المتجر</label>
                                <input type="file" class="form-control @error('store_in_file') is-invalid @enderror" 
                                       id="store_in_file" name="store_in_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('store_in_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="store_out_file" class="form-label">ملف خروج المتجر</label>
                                <input type="file" class="form-control @error('store_out_file') is-invalid @enderror" 
                                       id="store_out_file" name="store_out_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('store_out_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ddo_file" class="form-label">ملف DDO</label>
                                <input type="file" class="form-control @error('ddo_file') is-invalid @enderror" 
                                       id="ddo_file" name="ddo_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('ddo_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ المادة
                                    </button>
                                    <a href="{{ route('admin.work-orders.materials.index', $workOrder) }}" class="btn btn-secondary btn-lg ms-2">
                                        <i class="fas fa-times me-2"></i>
                                        إلغاء
                                    </a>
                                    <button type="reset" class="btn btn-outline-secondary btn-lg ms-2">
                                        <i class="fas fa-undo me-2"></i>
                                        إعادة تعيين
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

@push('scripts')
<script>
$(document).ready(function() {
    // البحث عن وصف المادة عند تغيير الكود
    $('#search-material-btn').click(function() {
        var code = $('#code').val();
        if (code) {
            $.ajax({
                url: '/admin/materials/description/' + code,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#description').val(response.description);
                        toastr.success('تم العثور على وصف المادة');
                    } else {
                        toastr.warning('لم يتم العثور على وصف للمادة');
                    }
                },
                error: function() {
                    toastr.error('حدث خطأ أثناء البحث عن المادة');
                }
            });
        } else {
            toastr.warning('يرجى إدخال كود المادة أولاً');
        }
    });

    // البحث التلقائي عند إدخال الكود
    $('#code').on('blur', function() {
        var code = $(this).val();
        if (code && $('#description').val() === '') {
            $('#search-material-btn').click();
        }
    });

    // تحسين تجربة رفع الملفات
    $('input[type="file"]').on('change', function() {
        var file = this.files[0];
        if (file) {
            var fileSize = (file.size / 1024 / 1024).toFixed(2);
            var fileName = file.name;
            
            if (fileSize > 10) {
                toastr.error('حجم الملف كبير جداً. الحد الأقصى 10 ميجابايت');
                $(this).val('');
                return;
            }
            
            var label = $(this).siblings('label').text();
            toastr.info('تم اختيار ملف ' + label + ': ' + fileName + ' (' + fileSize + ' MB)');
        }
    });

    // التحقق من صحة النموذج قبل الإرسال
    $('form').on('submit', function(e) {
        var code = $('#code').val();
        var description = $('#description').val();
        
        if (!code) {
            e.preventDefault();
            toastr.error('يرجى إدخال كود المادة');
            $('#code').focus();
            return false;
        }
        
        if (!description) {
            e.preventDefault();
            toastr.error('يرجى إدخال وصف المادة');
            $('#description').focus();
            return false;
        }
        
        // إظهار رسالة تحميل
        toastr.info('جاري حفظ المادة...');
    });
});
</script>
@endpush
@endsection