@extends('layouts.admin')

@section('title', 'تعديل المادة - أمر العمل رقم ' . $workOrder->order_number)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">تعديل المادة</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.work-orders.index') }}">أوامر العمل</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.work-orders.show', $workOrder) }}">أمر العمل {{ $workOrder->order_number }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.work-orders.materials.index', $workOrder) }}">المواد</a></li>
                            <li class="breadcrumb-item active" aria-current="page">تعديل المادة</li>
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

/* تحسين عرض الملفات الحالية */
.text-success a {
    text-decoration: none;
    transition: all 0.3s ease;
}

.text-success a:hover {
    color: #198754 !important;
    transform: scale(1.05);
}

/* تحسين العناوين الفرعية */
h6 {
    border-bottom: 2px solid;
    padding-bottom: 0.5rem;
}

h6.text-primary {
    border-color: #0d6efd;
}

h6.text-success {
    border-color: #198754;
}

h6.text-info {
    border-color: #0dcaf0;
}

/* مؤشر التحميل لحقل الكود */
.loading {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23007bff' viewBox='0 0 16 16'%3e%3cpath d='M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM7 4a1 1 0 1 0 2 0 1 1 0 0 0-2 0zm1.5 2.5a.5.5 0 0 0-1 0v3a.5.5 0 0 0 1 0v-3z'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right calc(0.375em + 0.1875rem) center !important;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* تحسين زر البحث */
#search-material-btn {
    transition: all 0.3s ease;
}

#search-material-btn:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    transform: translateY(-1px);
}

#search-material-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* تحسين responsive */
@media (max-width: 768px) {
    .form-label {
        font-size: 0.9rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    h6 {
        font-size: 1rem;
    }
}
</style>
@endpush
@endsection 