@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex flex-column min-vh-100" style="min-height:100vh;">
    <div class="row justify-content-center flex-grow-1 align-items-start" style="flex:1 0 auto; margin-bottom:0 !important;">
        <div class="col-md-12 mb-0" style="margin-bottom:0 !important;">
            <div class="card mb-0" style="margin-bottom:0 !important;">
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                    <h4 class="mb-0">الأعمال المدنية - {{ $workOrder->order_number }}</h4>
                    <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right ml-1"></i>
                        عودة
                    </a>
                </div>
                <div class="card-body pb-2" style="padding-bottom:0.5rem !important;">
                    <form action="{{ route('admin.work-orders.civil-works.store', $workOrder) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- القسم الأول: الحفريات الأساسية -->
                            <div class="col-md-6">
                                <div class="section mb-4">
                                    <h5 class="section-title mb-3">الحفريات الأساسية</h5>

                                    <!-- حفريات تربة ترابية غير مسفلتة -->
                                    <div class="subsection mb-3">
                                        <h6 class="subsection-title">حفريات تربة ترابية غير مسفلتة</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 60%">نوع الكابل</th>
                                                        <th style="width: 40%">الطول (متر)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach(['1 كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط'] as $cable)
                                                    <tr>
                                                        <td class="align-middle">{{ $cable }}</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" class="form-control dimension-input" 
                                                                       name="excavation_unsurfaced_soil[{{ $loop->index }}]" 
                                                                       value="{{ old('excavation_unsurfaced_soil.' . $loop->index) }}"
                                                                       placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">م</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- حفريات تربة ترابية مسفلتة -->
                                    <div class="subsection mb-3">
                                        <h6 class="subsection-title">حفريات تربة ترابية مسفلتة</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 60%">نوع الكابل</th>
                                                        <th style="width: 40%">الطول (متر)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach(['1 كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط'] as $cable)
                                                    <tr>
                                                        <td class="align-middle">{{ $cable }}</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" class="form-control dimension-input" 
                                                                       name="excavation_surfaced_soil[{{ $loop->index }}]" 
                                                                       value="{{ old('excavation_surfaced_soil.' . $loop->index) }}"
                                                                       placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">م</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- حفريات تربة صخرية غير مسفلتة -->
                                    <div class="subsection mb-3">
                                        <h6 class="subsection-title">حفريات تربة صخرية غير مسفلتة</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 60%">نوع الكابل</th>
                                                        <th style="width: 40%">الطول (متر)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach(['1 كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط'] as $cable)
                                                    <tr>
                                                        <td class="align-middle">{{ $cable }}</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" class="form-control dimension-input" 
                                                                       name="excavation_unsurfaced_rock[{{ $loop->index }}]" 
                                                                       value="{{ old('excavation_unsurfaced_rock.' . $loop->index) }}"
                                                                       placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">م</span>
                                                                </div>
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

                            <!-- القسم الثاني: الحفر المفتوح -->
                            <div class="col-md-6">
                                <div class="section mb-4">
                                    <h5 class="section-title mb-3">حفر مفتوح بالمتر المكعب</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
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
                                                    <td class="align-middle">حفر متوسط</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[medium][length]" 
                                                                   data-row="medium"
                                                                   value="{{ old('open_excavation.medium.length') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[medium][width]" 
                                                                   data-row="medium"
                                                                   value="{{ old('open_excavation.medium.width') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[medium][depth]" 
                                                                   data-row="medium"
                                                                   value="{{ old('open_excavation.medium.depth') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control total-input" 
                                                                   id="total-medium" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م³</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle">حفر منخفض</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[low][length]" 
                                                                   data-row="low"
                                                                   value="{{ old('open_excavation.low.length') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[low][width]" 
                                                                   data-row="low"
                                                                   value="{{ old('open_excavation.low.width') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[low][depth]" 
                                                                   data-row="low"
                                                                   value="{{ old('open_excavation.low.depth') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control total-input" 
                                                                   id="total-low" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م³</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle">توريد وتنفيذ طبقة رمل تحت الكابلات</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[sand_under][length]" 
                                                                   data-row="sand_under"
                                                                   value="{{ old('open_excavation.sand_under.length') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[sand_under][width]" 
                                                                   data-row="sand_under"
                                                                   value="{{ old('open_excavation.sand_under.width') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[sand_under][depth]" 
                                                                   data-row="sand_under"
                                                                   value="{{ old('open_excavation.sand_under.depth') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control total-input" 
                                                                   id="total-sand_under" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م³</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle">توريد وتنفيذ طبقة رمل فوق الكابلات</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[sand_over][length]" 
                                                                   data-row="sand_over"
                                                                   value="{{ old('open_excavation.sand_over.length') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[sand_over][width]" 
                                                                   data-row="sand_over"
                                                                   value="{{ old('open_excavation.sand_over.width') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[sand_over][depth]" 
                                                                   data-row="sand_over"
                                                                   value="{{ old('open_excavation.sand_over.depth') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control total-input" 
                                                                   id="total-sand_over" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م³</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle">توريد وتنفيذ طبقة أولى صيبز</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[first_sibz][length]" 
                                                                   data-row="first_sibz"
                                                                   value="{{ old('open_excavation.first_sibz.length') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[first_sibz][width]" 
                                                                   data-row="first_sibz"
                                                                   value="{{ old('open_excavation.first_sibz.width') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[first_sibz][depth]" 
                                                                   data-row="first_sibz"
                                                                   value="{{ old('open_excavation.first_sibz.depth') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control total-input" 
                                                                   id="total-first_sibz" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م³</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle">توريد وتنفيذ طبقة ثانية صيبز</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[second_sibz][length]" 
                                                                   data-row="second_sibz"
                                                                   value="{{ old('open_excavation.second_sibz.length') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[second_sibz][width]" 
                                                                   data-row="second_sibz"
                                                                   value="{{ old('open_excavation.second_sibz.width') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[second_sibz][depth]" 
                                                                   data-row="second_sibz"
                                                                   value="{{ old('open_excavation.second_sibz.depth') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control total-input" 
                                                                   id="total-second_sibz" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م³</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle">توريد وتنفيذ طبقة خرسانية</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[concrete][length]" 
                                                                   data-row="concrete"
                                                                   value="{{ old('open_excavation.concrete.length') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[concrete][width]" 
                                                                   data-row="concrete"
                                                                   value="{{ old('open_excavation.concrete.width') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-total" 
                                                                   name="open_excavation[concrete][depth]" 
                                                                   data-row="concrete"
                                                                   value="{{ old('open_excavation.concrete.depth') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control total-input" 
                                                                   id="total-concrete" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م³</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle">أسفلت طبقة أولى</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-area" 
                                                                   name="open_excavation[first_asphalt][length]" 
                                                                   data-row="first_asphalt"
                                                                   value="{{ old('open_excavation.first_asphalt.length') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-area" 
                                                                   name="open_excavation[first_asphalt][width]" 
                                                                   data-row="first_asphalt"
                                                                   value="{{ old('open_excavation.first_asphalt.width') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">-</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control total-input" 
                                                                   id="total-first_asphalt" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م²</span>
                                                            </div>
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
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input calculate-area" 
                                                                   name="open_excavation[asphalt_scraping][width]" 
                                                                   data-row="asphalt_scraping"
                                                                   value="{{ old('open_excavation.asphalt_scraping.width') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">-</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control total-input" 
                                                                   id="total-asphalt_scraping" 
                                                                   readonly 
                                                                   value="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م²</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5"><hr class='my-2'></td>
                                                </tr>
                                                <tr>
                                                    <th style="width: 35%">العنصر</th>
                                                    <th style="width: 15%">العدد</th>
                                                    <th style="width: 15%">الطول (متر)</th>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle">توريد وتركيب شريط كزيري</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="1" class="form-control dimension-input" 
                                                                   name="open_excavation[kerb_strip][quantity]" 
                                                                   value="{{ old('open_excavation.kerb_strip.quantity') }}"
                                                                   placeholder="0">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">قطعة</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input" 
                                                                   name="open_excavation[kerb_strip][length]" 
                                                                   value="{{ old('open_excavation.kerb_strip.length') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-middle">توريد وتركيب بلاط حماية</td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="1" class="form-control dimension-input" 
                                                                   name="open_excavation[protection_tile][quantity]" 
                                                                   value="{{ old('open_excavation.protection_tile.quantity') }}"
                                                                   placeholder="0">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">قطعة</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="0.01" class="form-control dimension-input" 
                                                                   name="open_excavation[protection_tile][length]" 
                                                                   value="{{ old('open_excavation.protection_tile.length') }}"
                                                                   placeholder="0.00">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">م</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
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
                                                    @foreach(['1 كابل منخفض', '2 كابل منخفض', '3 كابل منخفض', '4 كابل منخفض', '1 كابل متوسط', '3 كابل متوسط', '4 كابل متوسط'] as $cable)
                                                    <tr>
                                                        <td class="align-middle">{{ $cable }}</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" class="form-control dimension-input" 
                                                                       name="excavation_surfaced_rock[{{ $loop->index }}]" 
                                                                       value="{{ old('excavation_surfaced_rock.' . $loop->index) }}"
                                                                       placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">م</span>
                                                                </div>
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
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">م</span>
                                                                </div>
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
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">م</span>
                                                                </div>
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
                            </div>
                        </div>

                        <div class="form-group text-center mt-3 mb-0" style="margin-bottom:0 !important; padding-bottom:0 !important;">
                            <button type="submit" class="btn btn-primary px-4">حفظ البيانات</button>
                        </div>
                    </form>
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.work-orders.execution', $workOrder) }}" class="btn btn-lg btn-info">
                            <i class="fas fa-arrow-right ml-1"></i>
                            عودة إلى صفحة التنفيذ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
    border: 1px solid #dee2e6;
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

.container-fluid, .row, .col-md-12, .card, .card-body {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

body, html {
    height: 100%;
    min-height: 100vh;
}

.container-fluid.d-flex {
    min-height: 100vh;
    padding-bottom: 0 !important;
}

.card {
    margin-bottom: 0 !important;
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

@section('scripts')
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
@endsection 