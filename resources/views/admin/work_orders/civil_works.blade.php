<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>الأعمال المدنية - {{ $workOrder->order_number }}</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Tajawal:400,500,700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
    body, html {
        font-family: 'Tajawal', sans-serif;
        background: #fff;
        direction: rtl;
        text-align: right;
    }
    
    /* أنماط التنسيق الأساسية */
    .section {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        height: 100%;
    }

    .section-title {
        color: #2c3e50;
        border-bottom: 2px solid #3498db;
        padding-bottom: 8px;
        margin-bottom: 15px;
        font-size: 1.1rem;
    }

    .subsection {
        background-color: white;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .subsection-title {
        color: #34495e;
        margin-bottom: 12px;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .table {
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        padding: 0.5rem;
        border: 2px solid #dee2e6;
        font-size: 0.85rem;
    }

    .table td {
        padding: 0.4rem;
        vertical-align: middle;
    }

    .input-group-sm {
        width: 100%;
    }

    .input-group-sm .form-control {
        height: calc(1.5em + 0.4rem + 2px);
        padding: 0.2rem 0.4rem;
        font-size: 0.85rem;
        border-radius: 0.2rem;
        text-align: left;
    }

    .input-group-sm .input-group-text {
        padding: 0.2rem 0.4rem;
        font-size: 0.85rem;
        border-radius: 0.2rem;
        background-color: #e9ecef;
        border-color: #ced4da;
        color: #495057;
    }

    .dimension-input {
        text-align: left;
        direction: ltr;
    }

    .dimension-input:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .align-middle {
        font-weight: 500;
        color: #495057;
    }

    .btn {
        font-size: 0.9rem;
        padding: 0.4rem 1rem;
    }

    @media (max-width: 768px) {
        .section {
            margin-bottom: 15px;
        }
        
        .subsection {
            padding: 10px;
            margin-bottom: 10px;
        }
        
        .table {
            font-size: 0.85rem;
        }
        
        .input-group-sm .form-control,
        .input-group-sm .input-group-text {
            font-size: 0.8rem;
        }
    }

    .total-input {
        background-color: #f8f9fa;
        font-weight: 500;
        color: #2c3e50;
        text-align: left;
        direction: ltr;
    }

    .total-input:read-only {
        cursor: default;
    }

    .total-input:focus {
        box-shadow: none;
        border-color: #ced4da;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }

    .btn-check:checked + .btn-outline-success {
        background-color: #28a745;
        color: white;
    }

    .btn-check:checked + .btn-outline-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-check:checked + .btn-outline-secondary {
        background-color: #6c757d;
        color: white;
    }

    .quantity-input:disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
    }

    .btn-group {
        display: flex;
        justify-content: space-between;
    }

    .btn-group .btn {
        flex: 1;
        margin: 0 2px;
    }

    .btn-group .btn:first-child {
        margin-right: 0;
    }

    .btn-group .btn:last-child {
        margin-left: 0;
    }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0" style="font-size:2.1rem; font-weight:800; color:#2c3e50; letter-spacing:1px; display:flex; align-items:center;">
                <i class="fas fa-hard-hat me-2" style="color:#007bff;"></i>
                الأعمال المدنية - {{ $workOrder->order_number }}
            </h2>
            <a href="{{ route('admin.work-orders.execution', $workOrder) }}" class="btn btn-outline-secondary">&larr; عودة</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger text-center">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.work-orders.civil-works.store', $workOrder) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <!-- كارد الحفريات الأساسية -->
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">الحفريات الأساسية</div>
                        <div class="card-body">
                            <!-- حفريات تربة ترابية غير مسفلتة -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-2">حفريات تربة ترابية غير مسفلتة</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 60%">نوع الكابل</th>
                                                <th style="width: 40%">الطول (متر)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach([ 'كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط', 'حفر مفتوح اكبر من 4 كابلات'] as $cable)
                                        <tr>
                                                <td class="align-middle">{{ $cable }}</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_unsurfaced_soil[{{ $loop->index }}]" 
                                                               value="{{ old('excavation_unsurfaced_soil.' . $loop->index) }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- حفريات تربة ترابية مسفلتة -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-2">حفريات تربة ترابية مسفلتة</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 60%">نوع الكابل</th>
                                                <th style="width: 40%">الطول (متر)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach([ 'كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط', 'حفر مفتوح اكبر من 4 كابلات'] as $cable)

                                                <td class="align-middle">{{ $cable }}</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_surfaced_soil[{{ $loop->index }}]" 
                                                               value="{{ old('excavation_surfaced_soil.' . $loop->index) }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- حفريات تربة صخرية غير مسفلتة -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-2">حفريات تربة صخرية غير مسفلتة</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 60%">نوع الكابل</th>
                                                <th style="width: 40%">الطول (متر)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach([ 'كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط', 'حفر مفتوح اكبر من 4 كابلات'] as $cable)

                                                <td class="align-middle">{{ $cable }}</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_unsurfaced_rock[{{ $loop->index }}]" 
                                                               value="{{ old('excavation_unsurfaced_rock.' . $loop->index) }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- حفريات تربة صخرية مسفلتة -->
                            <div class="subsection mb-3">
                                <div class="table-responsive">
                                    
                                </div>
                            </div>

                            <!-- حفريات دقيقة -->
                            <div class="subsection mb-3">
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- القسم الثاني: الحفر المفتوح -->
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-success text-white">الحفر المفتوح </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 35%">العنصر</th>
                                            <th style="width: 15%">الطول (متر)</th>
                                            <th style="width: 15%">العرض (متر)</th>
                                            <th style="width: 15%">العمق (متر)</th>
                                            <th style="width: 20%">الإجمالي (م³)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        
                                        <tr>
                                            <td class="align-middle">أسفلت طبقة أولى</td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.01" class="form-control dimension-input calculate-area" 
                                                           name="open_excavation[first_asphalt][length]" 
                                                           data-row="first_asphalt"
                                                           value="{{ old('open_excavation.first_asphalt.length') }}"
                                                           placeholder="0.00">
                                                    <span class="input-group-text">م</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.01" class="form-control dimension-input calculate-area" 
                                                           name="open_excavation[first_asphalt][width]" 
                                                           data-row="first_asphalt"
                                                           value="{{ old('open_excavation.first_asphalt.width') }}"
                                                           placeholder="0.00">
                                                    <span class="input-group-text">م</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" readonly>
                                                    <span class="input-group-text">-</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control total-input" 
                                                           id="total-first_asphalt" 
                                                           readonly 
                                                           value="0.00">
                                                    <span class="input-group-text">م²</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle">كشط واعادة السفلتة</td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.01" class="form-control dimension-input calculate-area" 
                                                           name="open_excavation[asphalt_scraping][length]" 
                                                           data-row="asphalt_scraping"
                                                           value="{{ old('open_excavation.asphalt_scraping.length') }}"
                                                           placeholder="0.00">
                                                    <span class="input-group-text">م</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="number" step="0.01" class="form-control dimension-input calculate-area" 
                                                           name="open_excavation[asphalt_scraping][width]" 
                                                           data-row="asphalt_scraping"
                                                           value="{{ old('open_excavation.asphalt_scraping.width') }}"
                                                           placeholder="0.00">
                                                    <span class="input-group-text">م</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" readonly>
                                                    <span class="input-group-text">-</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control total-input" 
                                                           id="total-asphalt_scraping" 
                                                           readonly 
                                                           value="0.00">
                                                    <span class="input-group-text">م²</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5"><hr class='my-2'></td>
                                    
                                    
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="subsection mb-3">
                                <h6 class="subsection-title">حفريات تربة صخرية مسفلتة</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th style="width: 60%">نوع الكابل</th>
                                                <th style="width: 40%">الطول (متر)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach([ 'كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '2 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط', 'حفر مفتوح اكبر من 4 كابلات'] as $cable)

                                                <td class="align-middle">{{ $cable }}</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_surfaced_rock[{{ $loop->index }}]" 
                                                               value="{{ old('excavation_surfaced_rock.' . $loop->index) }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- حفريات دقيقة -->
                            <div class="subsection mb-3">
                                <h6 class="subsection-title">حفريات دقيقة</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th style="width: 40%">نوع الحفر</th>
                                                <th style="width: 30%">الأبعاد</th>
                                                <th style="width: 30%">الطول (متر)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="align-middle">حفر متوسط</td>
                                                <td class="align-middle">20 × 80</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_precise[medium]" 
                                                               value="{{ old('excavation_precise.medium', $workOrder->excavation_precise['medium'] ?? '') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle">حفر منخفض</td>
                                                <td class="align-middle">20 × 56</td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" step="0.01" class="form-control dimension-input" 
                                                               name="excavation_precise[low]" 
                                                               value="{{ old('excavation_precise.low', $workOrder->excavation_precise['low'] ?? '') }}"
                                                               placeholder="0.00">
                                                        <span class="input-group-text">م</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- قسم رفع الصور -->
                <div class="col-12">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-info text-white">
                            <i class="fas fa-images me-2"></i>
                            صور الأعمال المدنية
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                يمكنك رفع حتى 70 صورة بحجم إجمالي لا يتجاوز 30 ميجابايت
                            </div>
                            
                            <div class="mb-3">
                                <label for="civil_works_images" class="form-label">اختر الصور</label>
                                <input type="file" 
                                       class="form-control" 
                                       id="civil_works_images" 
                                       name="civil_works_images[]" 
                                       multiple 
                                       accept="image/*"
                                       data-max-files="70"
                                       data-max-size="31457280">
                                <div class="form-text">يمكنك اختيار عدة صور عن طريق الضغط على Ctrl أثناء الاختيار</div>
                            </div>

                            <div id="image-preview" class="row g-3">
                                <!-- سيتم إضافة معاينات الصور هنا -->
                            </div>

                            <div id="upload-progress" class="progress d-none mt-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" 
                                     style="width: 0%">0%</div>
                            </div>

                            <div id="upload-status" class="alert d-none mt-3"></div>

                            <!-- عرض الصور المرفوعة -->
                            @if($workOrder->civilWorksFiles->count() > 0)
                                <div class="mt-4">
                                    <h5 class="mb-3">الصور المرفوعة</h5>
                                    <div class="row g-3">
                                        @foreach($workOrder->civilWorksFiles as $file)
                                            <div class="col-md-3">
                                                <div class="card h-100">
                                                    <img src="{{ asset('storage/' . $file->file_path) }}" 
                                                         class="card-img-top" 
                                                         alt="{{ $file->original_filename }}"
                                                         style="height: 200px; object-fit: cover;">
                                                    <div class="card-body">
                                                        <p class="card-text small text-truncate" title="{{ $file->original_filename }}">
                                                            {{ Str::limit($file->original_filename, 30) }}
                                                        </p>
                                                        <p class="card-text small text-muted">
                                                            {{ round($file->file_size / 1024 / 1024, 2) }} MB
                                                        </p>
                                                        <div class="btn-group w-100">
                                                            <a href="{{ asset('storage/' . $file->file_path) }}" 
                                                               class="btn btn-sm btn-info" 
                                                               target="_blank">
                                                                <i class="fas fa-eye"></i> عرض
                                                            </a>
                                                            <form action="{{ route('admin.work-orders.civil-works.delete-file', [$workOrder, $file]) }}" 
                                                                  method="POST" 
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">
                                                                    <i class="fas fa-trash"></i> حذف
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5 py-2 fs-5">حفظ الأعمال المدنية</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // دالة لحساب الإجمالي لكل صف (حجم)
        function calculateTotal(rowId) {
            const length = parseFloat(document.querySelector(`input[data-row="${rowId}"][name$="[length]"]`).value) || 0;
            const width = parseFloat(document.querySelector(`input[data-row="${rowId}"][name$="[width]"]`).value) || 0;
            const depth = parseFloat(document.querySelector(`input[data-row="${rowId}"][name$="[depth]"]`).value) || 0;
            
            const total = length * width * depth;
            document.getElementById(`total-${rowId}`).value = total.toFixed(2);
        }

        // دالة لحساب المساحة لكل صف (متر مربع)
        function calculateArea(rowId) {
            const length = parseFloat(document.querySelector(`input[data-row="${rowId}"][name$="[length]"]`).value) || 0;
            const width = parseFloat(document.querySelector(`input[data-row="${rowId}"][name$="[width]"]`).value) || 0;
            
            const total = length * width;
            document.getElementById(`total-${rowId}`).value = total.toFixed(2);
        }

        // إضافة مستمع الحدث لجميع حقول الإدخال (الحجم)
        document.querySelectorAll('.calculate-total').forEach(input => {
            input.addEventListener('input', function() {
                calculateTotal(this.dataset.row);
            });
        });

        // إضافة مستمع الحدث لجميع حقول الإدخال (المساحة)
        document.querySelectorAll('.calculate-area').forEach(input => {
            input.addEventListener('input', function() {
                calculateArea(this.dataset.row);
            });
        });

        // حساب الإجماليات الأولية عند تحميل الصفحة (الحجم)
        const volumeRows = ['medium', 'low', 'sand_under', 'sand_over', 'first_sibz', 'second_sibz', 'concrete'];
        volumeRows.forEach(row => calculateTotal(row));

        // حساب الإجماليات الأولية عند تحميل الصفحة (المساحة)
        const areaRows = ['first_asphalt', 'asphalt_scraping'];
        areaRows.forEach(row => calculateArea(row));
    });
    </script>

    <!-- إضافة JavaScript للتعامل مع رفع الصور -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('civil_works_images');
            const imagePreview = document.getElementById('image-preview');
            const uploadProgress = document.getElementById('upload-progress');
            const progressBar = uploadProgress.querySelector('.progress-bar');
            const uploadStatus = document.getElementById('upload-status');
            const maxFiles = parseInt(imageInput.dataset.maxFiles);
            const maxSize = parseInt(imageInput.dataset.maxSize);
            let totalSize = 0;

            // التحقق من حجم الملفات عند الاختيار
            imageInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                totalSize = files.reduce((sum, file) => sum + file.size, 0);

                // التحقق من عدد الملفات
                if (files.length > maxFiles) {
                    showError(`يمكنك رفع ${maxFiles} صور كحد أقصى`);
                    imageInput.value = '';
                    return;
                }

                // التحقق من الحجم الإجمالي
                if (totalSize > maxSize) {
                    showError(`الحجم الإجمالي للصور يجب أن لا يتجاوز 30 ميجابايت`);
                    imageInput.value = '';
                    return;
                }

                // عرض معاينة الصور
                imagePreview.innerHTML = '';
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 col-sm-4 col-6';
                        col.innerHTML = `
                            <div class="card h-100">
                                <img src="${e.target.result}" class="card-img-top" alt="معاينة الصورة ${index + 1}">
                                <div class="card-body p-2">
                                    <p class="card-text small text-muted mb-0">${formatFileSize(file.size)}</p>
                                </div>
                            </div>
                        `;
                        imagePreview.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                });

                // إخفاء رسائل الخطأ السابقة
                uploadStatus.classList.add('d-none');
            });

            // دالة لعرض رسائل الخطأ
            function showError(message) {
                uploadStatus.className = 'alert alert-danger mt-3';
                uploadStatus.textContent = message;
                uploadStatus.classList.remove('d-none');
            }

            // دالة لتنسيق حجم الملف
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // إرسال النموذج
            document.querySelector('form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const files = imageInput.files;
                
                if (files.length > 0) {
                    uploadProgress.classList.remove('d-none');
                    uploadStatus.classList.add('d-none');
                    
                    // إضافة الصور إلى FormData
                    Array.from(files).forEach((file, index) => {
                        formData.append(`civil_works_images[${index}]`, file);
                    });

                    // إرسال النموذج باستخدام AJAX
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(async response => {
                        let data;
                        try {
                            data = await response.json();
                        } catch (e) {
                            throw new Error('الاستجابة من السيرفر غير صالحة (ليست JSON). قد يكون هناك خطأ في السيرفر أو في الاتصال.');
                        }
                        if (!response.ok) {
                            throw new Error((data && data.message) ? data.message : 'حدث خطأ أثناء حفظ البيانات');
                        }
                        if (data.success) {
                            uploadStatus.className = 'alert alert-success mt-3';
                            uploadStatus.textContent = 'تم حفظ البيانات والصور بنجاح';
                            uploadStatus.classList.remove('d-none');
                            setTimeout(() => window.location.reload(), 2000);
                        } else {
                            throw new Error(data.message || 'حدث خطأ أثناء حفظ البيانات');
                        }
                    })
                    .catch(error => {
                        uploadStatus.className = 'alert alert-danger mt-3';
                        uploadStatus.textContent = error.message;
                        uploadStatus.classList.remove('d-none');
                    })
                    .finally(() => {
                        uploadProgress.classList.add('d-none');
                    });
                } else {
                    // إذا لم يتم اختيار صور، أرسل النموذج بشكل عادي
                    this.submit();
                }
            });
        });
    </script>
</body>
</html> 