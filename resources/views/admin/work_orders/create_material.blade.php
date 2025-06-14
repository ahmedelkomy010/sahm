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
                                <label for="line" class="form-label">السطر</label>
                                <input type="text" class="form-control @error('line') is-invalid @enderror" 
                                       id="line" name="line" value="{{ old('line') }}" 
                                       placeholder=" السطر">
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
                                <label for="executed_quantity" class="form-label">الكمية المنفذة</label>
                                <input type="number" step="0.01" class="form-control @error('executed_quantity') is-invalid @enderror" 
                                       id="executed_quantity" name="executed_quantity" value="{{ old('executed_quantity', 0) }}" 
                                       placeholder="0.00" min="0">
                                @error('executed_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="quantity_difference" class="form-label">الفرق</label>
                                <input type="number" step="0.01" class="form-control" 
                                       id="quantity_difference" name="quantity_difference" value="0.00" 
                                       placeholder="0.00" readonly>
                                <small class="text-muted">يحسب تلقائياً (المخططة - المنفذة)</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="spent_quantity" class="form-label">الكمية المصروفة</label>
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
                                    <option value="L.M" {{ old('unit') == 'L.M' ? 'selected' : '' }}>L.M</option>
                                    <option value="Ech" {{ old('unit') == 'Ech' ? 'selected' : '' }}>Ech</option>
                                    <option value="Kit" {{ old('unit') == 'Kit' ? 'selected' : '' }}>Kit</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="date_gatepass" class="form-label">DATE GATEPASS</label>
                                <input type="date" class="form-control @error('date_gatepass') is-invalid @enderror" 
                                       id="date_gatepass" name="date_gatepass" value="{{ old('date_gatepass') }}">
                                @error('date_gatepass')
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
                            
                        
                           
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="check_in_file" class="form-label">
                                    <i class="fas fa-list-check me-2 text-primary"></i>
                                    CHECK LIST
                                </label>
                                <input type="file" class="form-control @error('check_in_file') is-invalid @enderror" 
                                       id="check_in_file" name="check_in_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('check_in_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="gate_pass_file" class="form-label">
                                    <i class="fas fa-id-card me-2 text-success"></i>
                                    GATE PASS
                                </label>
                                <input type="file" class="form-control @error('gate_pass_file') is-invalid @enderror" 
                                       id="gate_pass_file" name="gate_pass_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('gate_pass_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB)</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="stock_in_file" class="form-label">
                                    <i class="fas fa-sign-in-alt me-2 text-info"></i>
                                    STORE IN
                                </label>
                                <input type="file" class="form-control @error('stock_in_file') is-invalid @enderror" 
                                       id="stock_in_file" name="stock_in_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('stock_in_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stock_out_file" class="form-label">
                                    <i class="fas fa-sign-out-alt me-2 text-warning"></i>
                                    STORE OUT
                                </label>
                                <input type="file" class="form-control @error('stock_out_file') is-invalid @enderror" 
                                       id="stock_out_file" name="stock_out_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('stock_out_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB)</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ddo_file" class="form-label">
                                    <i class="fas fa-file-alt me-2 text-primary"></i>
                                    DDO FILE
                                </label>
                                <input type="file" class="form-control @error('ddo_file') is-invalid @enderror" 
                                       id="ddo_file" name="ddo_file" 
                                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('ddo_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, JPG, PNG, DOC (حد أقصى 10MB)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body d-flex align-items-center justify-content-center">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                                            <p class="small mb-0">يمكنك إضافة المزيد من الملفات<br>بعد حفظ المادة</p>
                                        </div>
                                    </div>
                                </div>
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

    // حساب الفرق بين الكمية المخططة والمنفذة
    function calculateQuantityDifference() {
        var plannedQuantity = parseFloat($('#planned_quantity').val()) || 0;
        var executedQuantity = parseFloat($('#executed_quantity').val()) || 0;
        var difference = plannedQuantity - executedQuantity;
        
        $('#quantity_difference').val(difference.toFixed(2));
        
        // تغيير لون الحقل حسب النتيجة
        var diffField = $('#quantity_difference');
        diffField.removeClass('text-success text-warning text-danger');
        
        if (difference > 0) {
            diffField.addClass('text-warning');
            diffField.attr('title', 'يوجد كمية مخططة لم يتم تنفيذها');
        } else if (difference < 0) {
            diffField.addClass('text-danger');
            diffField.attr('title', 'تم تنفيذ كمية أكثر من المخطط لها');
        } else {
            diffField.addClass('text-success');
            diffField.attr('title', 'الكمية المنفذة مطابقة للمخططة');
        }
    }

    // ربط الأحداث لحساب الفرق عند تغيير القيم
    $('#planned_quantity, #executed_quantity').on('input change', function() {
        calculateQuantityDifference();
    });

    // حساب الفرق عند تحميل الصفحة
    calculateQuantityDifference();

    // تحسين تجربة رفع الملفات
    $('input[type="file"]').on('change', function() {
        var file = this.files[0];
        var $input = $(this);
        var $label = $input.siblings('label');
        
        if (file) {
            var fileSize = (file.size / 1024 / 1024).toFixed(2);
            var fileName = file.name;
            
            if (fileSize > 10) {
                toastr.error('حجم الملف كبير جداً. الحد الأقصى 10 ميجابايت');
                $input.val('');
                $input.removeClass('is-valid').addClass('is-invalid');
                return;
            }
            
            // تحديد نوع الأيقونة حسب امتداد الملف
            var extension = fileName.split('.').pop().toLowerCase();
            var icon = 'fas fa-file';
            var color = 'text-primary';
            
            switch(extension) {
                case 'pdf':
                    icon = 'fas fa-file-pdf';
                    color = 'text-danger';
                    break;
                case 'doc':
                case 'docx':
                    icon = 'fas fa-file-word';
                    color = 'text-primary';
                    break;
                case 'jpg':
                case 'jpeg':
                case 'png':
                    icon = 'fas fa-file-image';
                    color = 'text-info';
                    break;
            }
            
            // تحديث الأيقونة في التسمية
            var originalIcon = $label.find('i').attr('class').split(' ')[1]; // الحصول على الأيقونة الأصلية
            $label.find('i').removeClass().addClass(icon + ' me-2 ' + color);
            
            var labelText = $label.text().trim();
            toastr.success('تم اختيار ملف ' + labelText + ': ' + fileName + ' (' + fileSize + ' MB)');
            
            $input.removeClass('is-invalid').addClass('is-valid');
        } else {
            // إعادة الأيقونة الأصلية عند إلغاء التحديد
            $input.removeClass('is-valid is-invalid');
        }
    });

    // تحسين عرض منطقة المرفقات
    $('.card').hover(
        function() {
            $(this).addClass('shadow-lg').css('transform', 'translateY(-2px)');
        },
        function() {
            $(this).removeClass('shadow-lg').css('transform', 'translateY(0)');
        }
    );

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

<style>
/* تحسين مظهر قسم المرفقات */
.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-label i {
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-control.is-valid {
    border-color: #28a745;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='m2.3 6.73.75-.04L5.95 4.2l-.75-.74L3.33 5.32 2.07 4.07l-.74.75z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.form-control.is-invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 2.4 2.4M5.8 7 8.2 4.6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,.125);
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,.1);
}

/* تحسين عرض منطقة إسقاط الملفات */
input[type="file"] {
    transition: all 0.3s ease;
}

input[type="file"]:hover {
    background-color: #f8f9fa;
}

/* تحسين البطاقة المعلوماتية */
.card-body .text-muted {
    transition: color 0.3s ease;
}

.card:hover .text-muted {
    color: #495057 !important;
}

/* تحسين الأيقونات */
.fa-2x {
    transition: transform 0.3s ease;
}

.card:hover .fa-2x {
    transform: scale(1.1);
}

/* تحسين responsive */
@media (max-width: 768px) {
    .form-label {
        font-size: 0.9rem;
    }
    
    .card-body {
        padding: 1rem;
    }
}
</style>
@endpush
@endsection