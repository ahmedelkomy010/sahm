@extends('layouts.admin')

@section('title', 'تعديل المادة - أمر العمل رقم ' . $workOrder->order_number)

@push('head')
<style>
    /* تنسيق عام */
    .card {
        border: none;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        transition: all 0.3s ease;
    }
    
    /* تنسيق معلومات أمر العمل */
    .info-card {
        background: linear-gradient(to right, #f8f9fa, #ffffff);
        border-radius: 15px;
        padding: 20px;
    }
    .info-item {
        padding: 10px;
        border-radius: 8px;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 10px;
    }
    .info-item strong {
        color: #4e73df;
        font-size: 0.9rem;
    }
    
    /* تنسيق النموذج */
    .form-section {
        background: #ffffff;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
    }
    .form-section h5 {
        color: #4e73df;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e3e6f0;
    }
    .form-label {
        color: #5a5c69;
        font-weight: 500;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e3e6f0;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    /* تنسيق حقول الكميات */
    .quantity-input {
        position: relative;
    }
    .quantity-input .form-control {
        padding-right: 60px;
    }
    .quantity-input::after {
        content: attr(data-unit);
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #858796;
        font-size: 0.9rem;
    }
    
    /* تنسيق حقول الفروق */
    .difference-field {
        background-color: #f8f9fa;
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        padding: 10px 15px;
        color: #858796;
        font-weight: 500;
    }
    .difference-positive {
        color: #1cc88a;
    }
    .difference-negative {
        color: #e74a3b;
    }
    .difference-zero {
        color: #858796;
    }
    
    /* تنسيق الأزرار */
    .btn {
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-primary {
        background: linear-gradient(45deg, #4e73df, #2e59d9);
        border: none;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(46, 89, 217, 0.2);
    }
    .btn-secondary {
        background: #858796;
        border: none;
    }
    .btn-secondary:hover {
        background: #6e707e;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">تعديل المادة</h1>
                   
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
            <div class="card info-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-item">
                                <strong><i class="fas fa-hashtag me-2"></i>رقم أمر العمل:</strong>
                                <span class="ms-2">{{ $workOrder->order_number }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <strong><i class="fas fa-user me-2"></i>اسم المشترك:</strong>
                                <span class="ms-2">{{ $workOrder->subscriber_name }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <strong><i class="fas fa-tasks me-2"></i>نوع العمل:</strong>
                                <span class="ms-2">{{ $workOrder->work_type }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-item">
                                <strong><i class="fas fa-check-circle me-2"></i>حالة أمر العمل:</strong>
                                <span class="badge bg-primary ms-2">{{ $workOrder->status ?? 'نشط' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Material Form -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">تعديل بيانات المادة</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.work-orders.materials.update', [$workOrder, $material]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
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
                                           id="code" name="code" value="{{ old('code', $material->code) }}" 
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
                                <label for="line" class="form-label">السطر </label>
                                <input type="text" class="form-control @error('line') is-invalid @enderror" 
                                       id="line" name="line" value="{{ old('line', $material->line) }}" 
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
                                          placeholder="وصف المادة" required>{{ old('description', $material->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- الصف الأول: الكمية المخططة، المصروفة، والفرق بينهما -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="planned_quantity" class="form-label">الكمية المخططة</label>
                                <input type="number" step="0.01" class="form-control @error('planned_quantity') is-invalid @enderror" 
                                       id="planned_quantity" name="planned_quantity" value="{{ old('planned_quantity', $material->planned_quantity) }}" 
                                       placeholder="0.00" min="0">
                                @error('planned_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="spent_quantity" class="form-label">الكمية المصروفة</label>
                                <input type="number" step="0.01" class="form-control @error('spent_quantity') is-invalid @enderror" 
                                       id="spent_quantity" name="spent_quantity" value="{{ old('spent_quantity', $material->spent_quantity) }}" 
                                       placeholder="0.00" min="0">
                                @error('spent_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="planned_spent_difference" class="form-label">الفرق (المخططة - المصروفة)</label>
                                <input type="number" step="0.01" class="form-control" 
                                       id="planned_spent_difference" name="planned_spent_difference" value="{{ $material->planned_spent_difference ?? 0 }}" 
                                       placeholder="0.00" readonly>
                                <small class="text-muted">يحسب تلقائياً</small>
                            </div>
                        </div>

                        <!-- الصف الثاني: الوحدة، الكمية المنفذة، والفرق بين المنفذة والمصروفة -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="unit" class="form-label">الوحدة</label>
                                <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit">
                                    <option value="">اختر الوحدة</option>
                                    <option value="L.M" {{ old('unit', $material->unit) == 'L.M' ? 'selected' : '' }}>L.M</option>
                                    <option value="Ech" {{ old('unit', $material->unit) == 'Ech' ? 'selected' : '' }}>Ech</option>
                                    <option value="Kit" {{ old('unit', $material->unit) == 'Kit' ? 'selected' : '' }}>Kit</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="executed_quantity" class="form-label">الكمية المنفذة</label>
                                <input type="number" step="0.01" class="form-control @error('executed_quantity') is-invalid @enderror" 
                                       id="executed_quantity" name="executed_quantity" value="{{ old('executed_quantity', $material->executed_quantity ?? 0) }}" 
                                       placeholder="0.00" min="0">
                                @error('executed_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="executed_spent_difference" class="form-label">الفرق (المنفذة - المصروفة)</label>
                                <input type="number" step="0.01" class="form-control" 
                                       id="executed_spent_difference" name="executed_spent_difference" value="{{ $material->executed_spent_difference ?? 0 }}" 
                                       placeholder="0.00" readonly>
                                <small class="text-muted">يحسب تلقائياً</small>
                            </div>
                        </div>



                        <!-- أزرار الحفظ -->
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ التعديلات
                                    </button>
                                    <a href="{{ route('admin.work-orders.materials.index', $workOrder) }}" class="btn btn-secondary btn-lg ms-2">
                                        <i class="fas fa-times me-2"></i>
                                        إلغاء
                                    </a>

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
    // البحث الديناميكي عن وصف المادة
    $('#code').on('input', function() {
        var code = $(this).val().trim();
        var $descriptionField = $('#description');
        
        if (code.length >= 3) { // البحث عند إدخال 3 أحرف على الأقل
            // إظهار مؤشر التحميل
            $(this).addClass('loading');
            
            $.ajax({
                url: '{{ route("admin.materials.description", ":code") }}'.replace(':code', code),
                method: 'GET',
                success: function(response) {
                    $('#code').removeClass('loading');
                    
                    if (response.success) {
                        $descriptionField.val(response.description);
                        $descriptionField.addClass('is-valid').removeClass('is-invalid');
                        toastr.success('تم العثور على وصف المادة');
                    } else {
                        $descriptionField.removeClass('is-valid');
                        // لا نمسح الوصف إذا لم نجد المادة، قد يكون المستخدم يريد إدخال وصف جديد
                    }
                },
                error: function() {
                    $('#code').removeClass('loading');
                    toastr.error('حدث خطأ أثناء البحث عن وصف المادة');
                }
            });
        } else {
            // إذا كان الكود أقل من 3 أحرف، لا نبحث
            $descriptionField.removeClass('is-valid is-invalid');
        }
    });

    // البحث عند النقر على زر البحث
    $('#search-material-btn').on('click', function() {
        var code = $('#code').val().trim();
        var $descriptionField = $('#description');
        
        if (!code) {
            toastr.warning('يرجى إدخال كود المادة أولاً');
            $('#code').focus();
            return;
        }
        
        // إظهار مؤشر التحميل على الزر
        var $btn = $(this);
        var originalHtml = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
        
        $.ajax({
            url: '{{ route("admin.materials.description", ":code") }}'.replace(':code', code),
            method: 'GET',
            success: function(response) {
                $btn.html(originalHtml).prop('disabled', false);
                
                if (response.success) {
                    $descriptionField.val(response.description);
                    $descriptionField.addClass('is-valid').removeClass('is-invalid');
                    toastr.success('تم العثور على وصف المادة');
                } else {
                    $descriptionField.removeClass('is-valid');
                    toastr.info('لم يتم العثور على المادة في قاعدة البيانات. يمكنك إدخال الوصف يدوياً');
                }
            },
            error: function() {
                $btn.html(originalHtml).prop('disabled', false);
                toastr.error('حدث خطأ أثناء البحث عن وصف المادة');
            }
        });
    });

    // حساب الفرق بين الكمية المخططة والمصروفة
    function calculatePlannedSpentDifference() {
        var plannedQuantity = parseFloat($('#planned_quantity').val()) || 0;
        var spentQuantity = parseFloat($('#spent_quantity').val()) || 0;
        var difference = plannedQuantity - spentQuantity;
        
        $('#planned_spent_difference').val(difference.toFixed(2));
        
        // تغيير لون الحقل حسب النتيجة
        var diffField = $('#planned_spent_difference');
        diffField.removeClass('text-success text-warning text-danger');
        
        if (difference > 0) {
            diffField.addClass('text-warning');
            diffField.attr('title', 'يوجد كمية مخططة لم يتم صرفها');
        } else if (difference < 0) {
            diffField.addClass('text-danger');
            diffField.attr('title', 'تم صرف كمية أكثر من المخطط لها');
        } else {
            diffField.addClass('text-success');
            diffField.attr('title', 'الكمية المصروفة مطابقة للمخططة');
        }
    }

    // حساب الفرق بين الكمية المنفذة والمصروفة
    function calculateExecutedSpentDifference() {
        var executedQuantity = parseFloat($('#executed_quantity').val()) || 0;
        var spentQuantity = parseFloat($('#spent_quantity').val()) || 0;
        var difference = executedQuantity - spentQuantity;
        
        $('#executed_spent_difference').val(difference.toFixed(2));
        
        // تغيير لون الحقل حسب النتيجة
        var diffField = $('#executed_spent_difference');
        diffField.removeClass('text-success text-warning text-danger');
        
        if (difference > 0) {
            diffField.addClass('text-warning');
            diffField.attr('title', 'تم تنفيذ كمية أكثر من المصروفة');
        } else if (difference < 0) {
            diffField.addClass('text-danger');
            diffField.attr('title', 'تم صرف كمية أكثر من المنفذة');
        } else {
            diffField.addClass('text-success');
            diffField.attr('title', 'الكمية المنفذة مطابقة للمصروفة');
        }
    }

    // ربط الأحداث لحساب الفروقات عند تغيير القيم
    $('#planned_quantity, #spent_quantity').on('input change', function() {
        calculatePlannedSpentDifference();
        calculateExecutedSpentDifference();
    });

    $('#executed_quantity').on('input change', function() {
        calculateExecutedSpentDifference();
    });

    // حساب الفروقات عند تحميل الصفحة
    calculatePlannedSpentDifference();
    calculateExecutedSpentDifference();

    // البحث الديناميكي عن وصف المادة
    $('#code').on('input', function() {
        var code = $(this).val().trim();
        var $descriptionField = $('#description');
        
        if (code.length >= 3) { // البحث عند إدخال 3 أحرف على الأقل
            $.ajax({
                url: '{{ route("admin.materials.description", ":code") }}'.replace(':code', code),
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $descriptionField.val(response.description);
                    }
                },
                error: function() {
                    // تجاهل الأخطاء بصمت
                }
            });
        }
    });

    // البحث عند النقر على زر البحث
    $('#search-material-btn').on('click', function() {
        var code = $('#code').val().trim();
        var $descriptionField = $('#description');
        
        if (!code) {
            return;
        }
        
        $.ajax({
            url: '{{ route("admin.materials.description", ":code") }}'.replace(':code', code),
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    $descriptionField.val(response.description);
                }
            },
            error: function() {
                // تجاهل الأخطاء بصمت
            }
        });
    });

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
            $label.find('i').removeClass().addClass(icon + ' me-2 ' + color);
            
            var labelText = $label.text().trim();
            toastr.success('تم اختيار ملف جديد ' + labelText + ': ' + fileName + ' (' + fileSize + ' MB)');
            
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
        toastr.info('جاري حفظ التعديلات...');
    });

    document.addEventListener('DOMContentLoaded', function() {
        // الحقول المطلوبة للحساب
        const plannedQuantityInput = document.getElementById('planned_quantity');
        const spentQuantityInput = document.getElementById('spent_quantity');
        const executedQuantityInput = document.getElementById('executed_quantity');
        const plannedSpentDifferenceInput = document.getElementById('planned_spent_difference');
        const executedSpentDifferenceInput = document.getElementById('executed_spent_difference');

        // دالة لحساب الفروق
        function calculateDifferences() {
            // حساب الفرق بين المخططة والمصروفة
            const plannedQuantity = parseFloat(plannedQuantityInput.value) || 0;
            const spentQuantity = parseFloat(spentQuantityInput.value) || 0;
            const plannedSpentDiff = plannedQuantity - spentQuantity;
            plannedSpentDifferenceInput.value = plannedSpentDiff.toFixed(2);

            // تغيير لون الفرق بين المخططة والمصروفة
            if (plannedSpentDiff < 0) {
                plannedSpentDifferenceInput.style.color = 'red';
            } else if (plannedSpentDiff > 0) {
                plannedSpentDifferenceInput.style.color = 'green';
            } else {
                plannedSpentDifferenceInput.style.color = 'black';
            }

            // حساب الفرق بين المنفذة والمصروفة
            const executedQuantity = parseFloat(executedQuantityInput.value) || 0;
            const executedSpentDiff = executedQuantity - spentQuantity;
            executedSpentDifferenceInput.value = executedSpentDiff.toFixed(2);

            // تغيير لون الفرق بين المنفذة والمصروفة
            if (executedSpentDiff < 0) {
                executedSpentDifferenceInput.style.color = 'red';
            } else if (executedSpentDiff > 0) {
                executedSpentDifferenceInput.style.color = 'green';
            } else {
                executedSpentDifferenceInput.style.color = 'black';
            }
        }

        // إضافة مستمعي الأحداث للحقول
        plannedQuantityInput.addEventListener('input', calculateDifferences);
        spentQuantityInput.addEventListener('input', calculateDifferences);
        executedQuantityInput.addEventListener('input', calculateDifferences);

        // حساب الفروق عند تحميل الصفحة
        calculateDifferences();
    });
});
</script>
@endpush
@endsection 