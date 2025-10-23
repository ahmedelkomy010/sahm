@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-primary text-white py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1 fs-3">
                                <i class="fas fa-tachometer-alt me-3"></i>
                                لوحة التحكم التحليلية (Dashboard)
                            </h2>
                            <p class="mb-0 text-white-75">
                                @if(isset($project))
                                    @if($project == 'riyadh')
                                        <i class="fas fa-city me-2"></i><strong>مشروع الرياض</strong> - بيانات مخصصة لمدينة الرياض
                                    @elseif($project == 'madinah')
                                        <i class="fas fa-mosque me-2"></i><strong>مشروع المدينة المنورة</strong> - بيانات مخصصة للمدينة المنورة
                                    @else
                                        <i class="fas fa-chart-line me-2"></i>نظام التحليلات والتقارير المتقدمة
                                    @endif
                                @else
                                    <i class="fas fa-chart-line me-2"></i>نظام التحليلات والتقارير المتقدمة
                                @endif
                            </p>
                        </div>
                        <div class="text-end">
                            <div class="d-flex flex-column align-items-end gap-2">
                                <div class="badge bg-light text-dark fs-6 px-3 py-2">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    {{ now()->format('Y/m/d') }}
                                </div>
                                
                                <!-- Export Button -->
                                <a href="{{ route('admin.work-orders.productivity.export', ['project' => $project ?? 'riyadh']) }}" 
                                   class="btn btn-sm btn-success">
                                    <i class="fas fa-file-excel me-2"></i>تصدير Excel
                                </a>
                                
                                <!-- Project Switcher -->
                                <div class="btn-group" role="group">
                                    <a href="{{ url('/admin/work-orders/productivity/riyadh') }}" 
                                       class="btn btn-sm {{ (isset($project) && $project == 'riyadh') ? 'btn-light' : 'btn-outline-light' }}">
                                        <i class="fas fa-city me-1"></i>الرياض
                                    </a>
                                    <a href="{{ url('/admin/work-orders/productivity/madinah') }}" 
                                       class="btn btn-sm {{ (isset($project) && $project == 'madinah') ? 'btn-light' : 'btn-outline-light' }}">
                                        <i class="fas fa-mosque me-1"></i>المدينة المنورة
                                    </a>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

            <!-- Date Filter Section -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="filter_start_date" class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>
                                تاريخ البداية
                            </label>
                            <input type="date" class="form-control" id="filter_start_date" name="start_date">
                        </div>
                        
                        <div class="col-md-3">
                            <label for="filter_end_date" class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>
                                تاريخ النهاية
                            </label>
                            <input type="date" class="form-control" id="filter_end_date" name="end_date">
                                                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-clock me-1"></i>
                                فترات زمنية سريعة
                            </label>
                            <div class="row g-2">
                                    <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setQuickDateRange('day')">
                                        <i class="fas fa-calendar-day me-1"></i>
                                        يوم
                                    </button>
                                                    </div>
                                    <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setQuickDateRange('quarter')">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        ربع سنة
                                    </button>
                                                </div>
                                    <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setQuickDateRange('half')">
                                        <i class="fas fa-calendar me-1"></i>
                                        نصف سنة
                                    </button>
                                                    </div>
                                    <div class="col-6">
                                    <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setQuickDateRange('year')">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        سنة
                                    </button>
                                                </div>
                                            </div>
                                                    </div>
                        
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary w-100 mb-2" onclick="applyDateFilter()">
                                <i class="fas fa-filter me-1"></i>
                                تطبيق
                            </button>
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="clearDateFilter()">
                                <i class="fas fa-times me-1"></i>
                                مسح
                            </button>
                                                </div>
                                            </div>
                        </div>
                    </div>

            <!-- Quick Stats Overview -->
                <div class="col-12">
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-light">
                                <h5 class="mb-0">
                                <i class="fas fa-th-large me-2 text-primary"></i>
                                لوحة تحكم حالات أوامر العمل
                            </h5>
                        </div>
                            <div class="card-body">
                            <div class="row g-2 justify-content-center">
                                <div class="col-6 col-md-4 col-lg" style="flex: 0 0 auto; width: 9.09%;">
                                    <div class="text-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                            <i class="fas fa-inbox text-success fs-6"></i>
                                            </div>
                                        <h6 class="mb-1" id="quickReceiptsCount">0</h6>
                                        <small class="text-muted" style="font-size: 0.7rem;">أوامر مستلمة</small>
                                        </div>
                                                </div>
                                <div class="col-6 col-md-4 col-lg" style="flex: 0 0 auto; width: 9.09%;">
                                    @php
                                        $inProgressRoute = ($project ?? 'riyadh') === 'madinah' 
                                            ? route('admin.work-orders.status.inprogress.madinah')
                                            : route('admin.work-orders.status.inprogress.riyadh');
                                    @endphp
                                    <a href="{{ $inProgressRoute }}" 
                                       class="text-decoration-none"
                                       style="color: inherit; transition: all 0.3s ease;">
                                        <div class="text-center p-2 rounded" style="cursor: pointer; transition: all 0.3s ease;" 
                                             onmouseover="this.style.backgroundColor='rgba(23, 162, 184, 0.1)'" 
                                             onmouseout="this.style.backgroundColor='transparent'">
                                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                                <i class="fas fa-cogs text-info fs-6"></i>
                                            </div>
                                            <h6 class="mb-1" id="quickInProgressCount">0</h6>
                                            <small class="text-muted" style="font-size: 0.65rem;">جاري العمل</small>
                                            <div class="mt-1">
                                                <small class="text-info fw-bold d-block" style="font-size: 0.55rem;" id="quickInProgressValue">0</small>
                                                <small class="text-muted" style="font-size: 0.5rem;">القيمة المبدئية</small>
                                            </div>
                                            <div class="mt-1">
                                                <small class="text-info" style="font-size: 0.6rem;">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </small>
                                        </div>
                                                </div>
                            </a>
                                                </div>
                                <div class="col-6 col-md-4 col-lg" style="flex: 0 0 auto; width: 9.09%;">
                                    @php
                                        $executedRoute = ($project ?? 'riyadh') === 'madinah' 
                                            ? route('admin.work-orders.status.executed.madinah')
                                            : route('admin.work-orders.status.executed.riyadh');
                                    @endphp
                                    <a href="{{ $executedRoute }}" 
                                       class="text-decoration-none"
                                       style="color: inherit; transition: all 0.3s ease;">
                                        <div class="text-center p-2 rounded" style="cursor: pointer; transition: all 0.3s ease;" 
                                             onmouseover="this.style.backgroundColor='rgba(112, 68, 2, 0.1)'" 
                                             onmouseout="this.style.backgroundColor='transparent'">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px; background-color: rgba(112, 68, 2, 0.1);">
                                                <i class="fas fa-hammer fs-6" style="color: #704402;"></i>
                                            </div>
                                            <h6 class="mb-1" id="quickExecutedCount">0</h6>
                                            <small class="text-muted" style="font-size: 0.65rem;">تم التنفيذ</small>
                                            <div class="mt-1">
                                                <small class="fw-bold d-block" style="color: #704402; font-size: 0.55rem;" id="quickExecutedValue">0</small>
                                                <small class="text-muted" style="font-size: 0.5rem;">القيمة المبدئية</small>
                                            </div>
                                            <div class="mt-1">
                                                <small style="color: #704402; font-size: 0.6rem;">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </small>
                                        </div>
                                    </div>
                                    </a>
                                                </div>
                                <div class="col-6 col-md-4 col-lg" style="flex: 0 0 auto; width: 9.09%;">
                                    @php
                                        $delivery155Route = ($project ?? 'riyadh') === 'madinah' 
                                            ? route('admin.work-orders.status.delivery155.madinah')
                                            : route('admin.work-orders.status.delivery155.riyadh');
                                    @endphp
                                    <a href="{{ $delivery155Route }}" 
                                       class="text-decoration-none"
                                       style="color: inherit; transition: all 0.3s ease;">
                                        <div class="text-center p-2 rounded" style="cursor: pointer; transition: all 0.3s ease;" 
                                             onmouseover="this.style.backgroundColor='rgba(111, 66, 193, 0.1)'" 
                                             onmouseout="this.style.backgroundColor='transparent'">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px; background-color: rgba(111, 66, 193, 0.1);">
                                                <i class="fas fa-file-signature fs-6" style="color: #6f42c1;"></i>
                                            </div>
                                            <h6 class="mb-1" id="quickDelivery155Count">0</h6>
                                            <small class="text-muted" style="font-size: 0.65rem;">تسليم 155</small>
                                            <div class="mt-1">
                                                <small class="fw-bold d-block" style="color: #6f42c1; font-size: 0.55rem;" id="quickDelivery155Value">0</small>
                                                <small class="text-muted" style="font-size: 0.5rem;">القيمة المبدئية</small>
                                            </div>
                                            <div class="mt-1">
                                                <small style="color: #6f42c1; font-size: 0.6rem;">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </small>
                                        </div>
                                                </div>
                                    </a>
                                            </div>
                                <div class="col-6 col-md-4 col-lg" style="flex: 0 0 auto; width: 9.09%;">
                                    @php
                                        $firstExtractRoute = ($project ?? 'riyadh') === 'madinah' 
                                            ? route('admin.work-orders.status.firstextract.madinah')
                                            : route('admin.work-orders.status.firstextract.riyadh');
                                    @endphp
                                    <a href="{{ $firstExtractRoute }}" 
                                       class="text-decoration-none"
                                       style="color: inherit; transition: all 0.3s ease;">
                                        <div class="text-center p-2 rounded" style="cursor: pointer; transition: all 0.3s ease;" 
                                             onmouseover="this.style.backgroundColor='rgba(233, 30, 99, 0.1)'" 
                                             onmouseout="this.style.backgroundColor='transparent'">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px; background-color: rgba(233, 30, 99, 0.1);">
                                                <i class="fas fa-file-invoice-dollar fs-6" style="color: #e91e63;"></i>
                                        </div>
                                            <h6 class="mb-1" id="quickFirstExtractCount">0</h6>
                                            <small class="text-muted" style="font-size: 0.65rem;">إعداد مستخلص</small>
                                            <div class="mt-1">
                                                <small class="fw-bold d-block" style="color: #e91e63; font-size: 0.55rem;" id="quickFirstExtractValue">0</small>
                                                <small class="text-muted" style="font-size: 0.5rem;">قيمة التنفيذ الفعلي</small>
                                            </div>
                                            <div class="mt-1">
                                                <small style="color: #e91e63; font-size: 0.6rem;">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </small>
                                    </div>
                                </div>
                            </a>
                            </div>
                                <div class="col-6 col-md-4 col-lg" style="flex: 0 0 auto; width: 9.09%;">
                                    @php
                                        $paidFirstRoute = ($project ?? 'riyadh') === 'madinah' 
                                            ? route('admin.work-orders.status.paidfirst.madinah')
                                            : route('admin.work-orders.status.paidfirst.riyadh');
                                    @endphp
                                    <a href="{{ $paidFirstRoute }}" 
                                       class="text-decoration-none"
                                       style="color: inherit; transition: all 0.3s ease;">
                                        <div class="text-center p-2 rounded" style="cursor: pointer; transition: all 0.3s ease;" 
                                             onmouseover="this.style.backgroundColor='rgba(255, 152, 0, 0.1)'" 
                                             onmouseout="this.style.backgroundColor='transparent'">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px; background-color: rgba(255, 152, 0, 0.1);">
                                                <i class="fas fa-money-check-alt fs-6" style="color: #ff9800;"></i>
                        </div>
                                            <h6 class="mb-1" id="quickPaidFirstCount">0</h6>
                                            <small class="text-muted" style="font-size: 0.65rem;">تم صرف أولى</small>
                                            <div class="mt-1">
                                                <small class="fw-bold d-block" style="color: #ff9800; font-size: 0.55rem;" id="quickPaidFirstValue">0</small>
                                                <small class="text-muted" style="font-size: 0.5rem;">قيمة التنفيذ الفعلي</small>
                                            </div>
                                            <div class="mt-1">
                                                <small style="color: #ff9800; font-size: 0.6rem;">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </small>
                    </div>
                                    </div>
                            </a>
                            </div>
                                <div class="col-6 col-md-4 col-lg" style="flex: 0 0 auto; width: 9.09%;">
                                    @php
                                        $secondExtractRoute = ($project ?? 'riyadh') === 'madinah'
                                            ? route('admin.work-orders.status.secondextract.madinah')
                                            : route('admin.work-orders.status.secondextract.riyadh');
                                    @endphp
                                    <a href="{{ $secondExtractRoute }}" 
                                       class="text-decoration-none"
                                       style="color: inherit; transition: all 0.3s ease;">
                                        <div class="text-center p-2 rounded" style="cursor: pointer; transition: all 0.3s ease;" 
                                             onmouseover="this.style.backgroundColor='rgba(156, 39, 176, 0.1)'" 
                                             onmouseout="this.style.backgroundColor='transparent'">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px; background-color: rgba(156, 39, 176, 0.1);">
                                                <i class="fas fa-file-invoice fs-6" style="color: #9c27b0;"></i>
                                            </div>
                                            <h6 class="mb-1" id="quickSecondExtractCount">0</h6>
                                            <small class="text-muted" style="font-size: 0.65rem;">مستخلص ثانية</small>
                                            <div class="mt-1">
                                                <small class="fw-bold d-block" style="color: #9c27b0; font-size: 0.55rem;" id="quickSecondExtractValue">0</small>
                                                <small class="text-muted" style="font-size: 0.5rem;">قيمة التنفيذ الفعلي</small>
                                            </div>
                                            <div class="mt-1">
                                                <small style="color: #9c27b0; font-size: 0.6rem;">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </small>
                                        </div>
                                                </div>
                                    </a>
                                </div>
                                <div class="col-6 col-md-4 col-lg" style="flex: 0 0 auto; width: 9.09%;">
                                    @php
                                        $certificateRoute = ($project ?? 'riyadh') === 'madinah'
                                            ? route('admin.work-orders.status.certificate.madinah')
                                            : route('admin.work-orders.status.certificate.riyadh');
                                    @endphp
                                    <a href="{{ $certificateRoute }}" 
                                       class="text-decoration-none"
                                       style="color: inherit; transition: all 0.3s ease;">
                                        <div class="text-center p-2 rounded" style="cursor: pointer; transition: all 0.3s ease;" 
                                             onmouseover="this.style.backgroundColor='rgba(76, 175, 80, 0.1)'" 
                                             onmouseout="this.style.backgroundColor='transparent'">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px; background-color: rgba(76, 175, 80, 0.1);">
                                                <i class="fas fa-certificate fs-6" style="color: #4caf50;"></i>
                                        </div>
                                            <h6 class="mb-1" id="quickCertificateCount">0</h6>
                                            <small class="text-muted" style="font-size: 0.65rem;">شهادة إنجاز</small>
                                            <div class="mt-1">
                                                <small class="fw-bold d-block" style="color: #4caf50; font-size: 0.55rem;" id="quickCertificateValue">0</small>
                                                <small class="text-muted" style="font-size: 0.5rem;">قيمة التنفيذ الفعلي</small>
                                            </div>
                                            <div class="mt-1">
                                                <small style="color: #4caf50; font-size: 0.6rem;">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </small>
                                    </div>
                                </div>
                                    </a>
                                </div>
                                <div class="col-6 col-md-4 col-lg" style="flex: 0 0 auto; width: 9.09%;">
                                    @php
                                        $totalExtractRoute = ($project ?? 'riyadh') === 'madinah' 
                                            ? route('admin.work-orders.status.totalextract.madinah')
                                            : route('admin.work-orders.status.totalextract.riyadh');
                                    @endphp
                                    <a href="{{ $totalExtractRoute }}" 
                                       class="text-decoration-none"
                                       style="color: inherit; transition: all 0.3s ease;">
                                        <div class="text-center p-2 rounded" style="cursor: pointer; transition: all 0.3s ease;" 
                                             onmouseover="this.style.backgroundColor='rgba(0, 188, 212, 0.1)'" 
                                             onmouseout="this.style.backgroundColor='transparent'">
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px; background-color: rgba(0, 188, 212, 0.1);">
                                                <i class="fas fa-file-contract fs-6" style="color: #00bcd4;"></i>
                                            </div>
                                            <h6 class="mb-1" id="quickTotalExtractCount">0</h6>
                                            <small class="text-muted" style="font-size: 0.65rem;">مستخلص كلي</small>
                                            <div class="mt-1">
                                                <small class="fw-bold d-block" style="color: #00bcd4; font-size: 0.55rem;" id="quickTotalExtractValue">0</small>
                                                <small class="text-muted" style="font-size: 0.5rem;">قيمة التنفيذ الفعلي</small>
                                            </div>
                                            <div class="mt-1">
                                                <small style="color: #00bcd4; font-size: 0.6rem;">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-6 col-md-4 col-lg" style="flex: 0 0 auto; width: 9.09%;">
                                    @php
                                        $completedRoute = ($project ?? 'riyadh') === 'madinah' 
                                            ? route('admin.work-orders.status.completed.madinah')
                                            : route('admin.work-orders.status.completed.riyadh');
                                    @endphp
                                    <a href="{{ $completedRoute }}" 
                                       class="text-decoration-none"
                                       style="color: inherit; transition: all 0.3s ease;">
                                        <div class="text-center p-2 rounded" style="cursor: pointer; transition: all 0.3s ease;" 
                                             onmouseover="this.style.backgroundColor='rgba(108, 117, 125, 0.1)'" 
                                             onmouseout="this.style.backgroundColor='transparent'">
                                            <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px;">
                                                <i class="fas fa-check-circle text-secondary fs-6"></i>
                                            </div>
                                            <h6 class="mb-1" id="quickCompletedCount">0</h6>
                                            <small class="text-muted" style="font-size: 0.65rem;">منتهي صرف</small>
                                            <div class="mt-1">
                                                <small class="fw-bold d-block text-secondary" style="font-size: 0.55rem;" id="quickCompletedValue">0</small>
                                                <small class="text-muted" style="font-size: 0.5rem;">قيمة التنفيذ الفعلي</small>
                                            </div>
                                            <div class="mt-1">
                                                <small class="text-secondary" style="font-size: 0.6rem;">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                        </div>
                        <div class="col-6 col-md-4 col-lg" style="flex: 0 0 auto; width: 9.09%;">
                            @php
                                $droopRoute = ($project ?? 'riyadh') === 'madinah'
                                    ? route('admin.work-orders.status.droop.madinah')
                                    : route('admin.work-orders.status.droop.riyadh');
                            @endphp
                            <a href="{{ $droopRoute }}" 
                               class="text-decoration-none"
                               style="color: inherit; transition: all 0.3s ease;">
                                <div class="text-center p-2 rounded" style="cursor: pointer; transition: all 0.3s ease;" 
                                     onmouseover="this.style.backgroundColor='rgba(220, 53, 69, 0.1)'" 
                                     onmouseout="this.style.backgroundColor='transparent'">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 45px; height: 45px; background-color: rgba(220, 53, 69, 0.1);">
                                        <i class="fas fa-ban fs-6" style="color: #dc3545;"></i>
                                    </div>
                                    <h6 class="mb-1" id="quickDroopCount">0</h6>
                                    <small class="text-muted" style="font-size: 0.65rem;">دروب</small>
                                    <div class="mt-1">
                                        <small class="fw-bold d-block" style="color: #dc3545; font-size: 0.55rem;" id="quickDroopValue">0</small>
                                        <small class="text-muted" style="font-size: 0.5rem;">القيمة المبدئية</small>
                                    </div>
                                    <div class="mt-1">
                                        <small style="color: #dc3545; font-size: 0.6rem;">
                                            <i class="fas fa-external-link-alt"></i>
                                        </small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Survey and Completion Files Section - Combined -->
                <div class="col-12">
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #f5576c 100%);">
                            <h5 class="mb-0 text-white text-center">
                                <i class="fas fa-tasks me-2"></i>
                                أوامر العمل التي تحتاج لإجراءات
                            </h5>
                        </div>
                        <div class="card-body py-4">
                            <div class="row g-3">
                                <!-- Survey Orders Card -->
                                <div class="col-md-6">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-header text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <h6 class="mb-0 text-center">
                                                <i class="fas fa-clipboard-list me-2"></i>
                                                أوامر تحتاج لمسح
                                            </h6>
                                        </div>
                                        <div class="card-body text-center">
                            @php
                                $surveyRoute = ($project ?? 'riyadh') === 'madinah' 
                                    ? route('admin.work-orders.survey.madinah')
                                    : route('admin.work-orders.survey.riyadh');
                                
                                // حساب عدد أوامر العمل التي تحتاج للمسح
                                $city = ($project ?? 'riyadh') === 'madinah' ? 'المدينة المنورة' : 'الرياض';
                                $surveyNeedsCount = \App\Models\WorkOrder::where('city', $city)
                                    ->where(function($query) {
                                        $query->whereDoesntHave('surveys')
                                              ->orWhereHas('surveys', function($q) {
                                                  $q->whereDoesntHave('files');
                                              });
                                    })
                                    ->count();
                            @endphp
                                            <div class="mb-3">
                                                <i class="fas fa-map-marked-alt fa-3x" style="color: #667eea;"></i>
                                            </div>
                                            <div class="mb-3">
                                                <h2 class="mb-1" style="color: #667eea;">{{ number_format($surveyNeedsCount) }}</h2>
                                                <p class="text-muted mb-0" style="font-size: 0.9rem;">عدد الأوامر</p>
                                            </div>
                            <a href="{{ $surveyRoute }}" 
                               class="btn btn-lg shadow-sm"
                                               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; min-width: 200px;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                عرض الأوامر التي تحتاج للمسح
                            </a>
                                            <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                                                عرض جميع أوامر العمل التي لم يتم مسحها بعد
                            </p>
                        </div>
                    </div>
                </div>

                                <!-- Completion Files Orders Card -->
                                <div class="col-md-6">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-header text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                            <h6 class="mb-0 text-center">
                                                <i class="fas fa-file-upload me-2"></i>
                                                أوامر تحتاج لرفع ملفات
                                            </h6>
                        </div>
                                        <div class="card-body text-center">
                            @php
                                $completionRoute = ($project ?? 'riyadh') === 'madinah' 
                                    ? route('admin.work-orders.completion.madinah')
                                    : route('admin.work-orders.completion.riyadh');
                                
                                // حساب عدد أوامر العمل التي تحتاج لرفع ملفات
                                $completionFilesCount = \App\Models\WorkOrder::where('city', $city)
                                    ->whereDoesntHave('files', function($q) {
                                        $q->where('file_category', 'completion_files');
                                    })
                                    ->count();
                            @endphp
                                            <div class="mb-3">
                                                <i class="fas fa-cloud-upload-alt fa-3x" style="color: #f093fb;"></i>
                                            </div>
                                            <div class="mb-3">
                                                <h2 class="mb-1" style="color: #f093fb;">{{ number_format($completionFilesCount) }}</h2>
                                                <p class="text-muted mb-0" style="font-size: 0.9rem;">عدد الأوامر</p>
                                            </div>
                            <a href="{{ $completionRoute }}" 
                               class="btn btn-lg shadow-sm"
                                               style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; min-width: 200px;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                عرض الأوامر التي تحتاج لرفع ملفات
                            </a>
                                            <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                                                عرض جميع أوامر العمل التي لم يتم رفع ملفات انتهاء العمل عليها
                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Quality Reports Section -->
            <div class="col-12">
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                        <h5 class="mb-0 text-white text-center">
                            <i class="fas fa-medal me-2"></i>
                            تقارير الجودة
                        </h5>
                    </div>
                    <div class="card-body py-4">
                        <div class="row g-3">
                            <!-- Licenses Card -->
                            <div class="col-md-2-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header text-white" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
                                        <h6 class="mb-0 text-center">
                                            <i class="fas fa-certificate me-2"></i>
                                            الرخص
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        @php
                                            $licensesRoute = ($project ?? 'riyadh') === 'madinah' 
                                                ? route('admin.licenses.all.madinah')
                                                : route('admin.licenses.all.riyadh');
                                            
                                            // حساب عدد الرخص وقيمة الرخص
                                            $licensesCount = \App\Models\License::where(function($q) use ($city) {
                                                $q->whereHas('workOrder', function ($subQ) use ($city) {
                                                    $subQ->where('city', $city);
                                                })->orWhere('city', $city);
                                            })->count();
                                            
                                            $licensesValue = \App\Models\License::where(function($q) use ($city) {
                                                $q->whereHas('workOrder', function ($subQ) use ($city) {
                                                    $subQ->where('city', $city);
                                                })->orWhere('city', $city);
                                            })->sum('license_value');
                                        @endphp
                                        <div class="mb-3">
                                            <i class="fas fa-file-contract fa-3x" style="color: #6a11cb;"></i>
                                        </div>
                                        <div class="mb-3">
                                            <h4 class="mb-1" style="color: #6a11cb;">{{ number_format($licensesCount) }}</h4>
                                            <p class="text-muted mb-0" style="font-size: 0.8rem;">عدد الرخص</p>
                                            <h5 class="mb-0 mt-2" style="color: #2575fc; font-size: 0.95rem;">
                                                @if($licensesValue >= 1000000)
                                                    {{ number_format($licensesValue / 1000000, 1) }}M
                                                @elseif($licensesValue >= 1000)
                                                    {{ number_format($licensesValue / 1000, 1) }}K
                                                @else
                                                    {{ number_format($licensesValue) }}
                                                @endif
                                            </h5>
                                            <p class="text-muted mb-0" style="font-size: 0.75rem;">قيمة الرخص</p>
                                        </div>
                                        <a href="{{ $licensesRoute }}" 
                                           class="btn btn-sm shadow-sm"
                                           style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color: white; min-width: 150px;">
                                            <i class="fas fa-list me-1"></i>
                                            عرض الرخص
                                        </a>
                                        <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                                            عرض جميع رخص أوامر العمل
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Violations Card -->
                            <div class="col-md-2-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header text-white" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);">
                                        <h6 class="mb-0 text-center">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            المخالفات
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        @php
                                            $violationsRoute = ($project ?? 'riyadh') === 'madinah' 
                                                ? route('admin.quality.violations.madinah')
                                                : route('admin.quality.violations.riyadh');
                                            
                                            // حساب مخالفات الجودة
                                            try {
                                                $qualityViolationsCount = \App\Models\LicenseViolation::whereHas('workOrder', function($q) use ($city) {
                                                    $q->where('city', $city);
                                                })->count();
                                                
                                                $qualityViolationsValue = \App\Models\LicenseViolation::whereHas('workOrder', function($q) use ($city) {
                                                    $q->where('city', $city);
                                                })->sum('violation_amount') ?? 0;
                                            } catch (\Exception $e) {
                                                $qualityViolationsCount = 0;
                                                $qualityViolationsValue = 0;
                                            }
                                            
                                            // حساب مخالفات السلامة
                                            try {
                                                $safetyViolationsCount = \App\Models\SafetyViolation::whereHas('workOrder', function($q) use ($city) {
                                                    $q->where('city', $city);
                                                })->count();
                                                
                                                $safetyViolationsValue = \App\Models\SafetyViolation::whereHas('workOrder', function($q) use ($city) {
                                                    $q->where('city', $city);
                                                })->sum('violation_amount') ?? 0;
                                            } catch (\Exception $e) {
                                                $safetyViolationsCount = 0;
                                                $safetyViolationsValue = 0;
                                            }
                                            
                                            // إجمالي المخالفات
                                            $totalViolationsCount = $qualityViolationsCount + $safetyViolationsCount;
                                            $totalViolationsValue = $qualityViolationsValue + $safetyViolationsValue;
                                        @endphp
                                        <div class="mb-3">
                                            <i class="fas fa-ban fa-3x" style="color: #eb3349;"></i>
                                        </div>
                                        <div class="mb-3">
                                            <h4 class="mb-1" style="color: #eb3349;">{{ number_format($totalViolationsCount) }}</h4>
                                            <p class="text-muted mb-0" style="font-size: 0.8rem;">إجمالي عدد المخالفات</p>
                                            
                                            <div class="mt-3">
                                                <div class="mb-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted" style="font-size: 0.75rem;">
                                                            <i class="fas fa-clipboard-check me-1"></i>مخالفات الجودة:
                                                        </small>
                                                        <span class="badge bg-warning text-dark">{{ number_format($qualityViolationsCount) }}</span>
                                                    </div>
                                                    <h6 class="mb-0 mt-1" style="color: #f39c12; font-size: 0.85rem;">
                                                        @if($qualityViolationsValue >= 1000000)
                                                            {{ number_format($qualityViolationsValue / 1000000, 1) }}M
                                                        @elseif($qualityViolationsValue >= 1000)
                                                            {{ number_format($qualityViolationsValue / 1000, 1) }}K
                                                        @else
                                                            {{ number_format($qualityViolationsValue) }}
                                                        @endif
                                                    </h6>
                                                    <small class="text-muted" style="font-size: 0.7rem;">القيمة</small>
                                                </div>
                                                
                                                <div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted" style="font-size: 0.75rem;">
                                                            <i class="fas fa-hard-hat me-1"></i>مخالفات السلامة:
                                                        </small>
                                                        <span class="badge bg-danger">{{ number_format($safetyViolationsCount) }}</span>
                                                    </div>
                                                    <h6 class="mb-0 mt-1" style="color: #eb3349; font-size: 0.85rem;">
                                                        @if($safetyViolationsValue >= 1000000)
                                                            {{ number_format($safetyViolationsValue / 1000000, 1) }}M
                                                        @elseif($safetyViolationsValue >= 1000)
                                                            {{ number_format($safetyViolationsValue / 1000, 1) }}K
                                                        @else
                                                            {{ number_format($safetyViolationsValue) }}
                                                        @endif
                                                    </h6>
                                                    <small class="text-muted" style="font-size: 0.7rem;">القيمة</small>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ $violationsRoute }}" 
                                           class="btn btn-sm shadow-sm"
                                           style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white; min-width: 150px;">
                                            <i class="fas fa-list me-1"></i>
                                            عرض المخالفات
                                        </a>
                                        <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                                            عرض جميع مخالفات أوامر العمل
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Extensions Card -->
                            <div class="col-md-2-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header text-white" style="background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);">
                                        <h6 class="mb-0 text-center">
                                            <i class="fas fa-calendar-plus me-2"></i>
                                            التمديدات
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        @php
                                            $extensionsRoute = ($project ?? 'riyadh') === 'madinah' 
                                                ? route('admin.quality.extensions.madinah')
                                                : route('admin.quality.extensions.riyadh');
                                            
                                            // حساب عدد التمديدات وقيمة التمديدات
                                            $extensionsCount = \App\Models\LicenseExtension::whereHas('license.workOrder', function($q) use ($city) {
                                                $q->where('city', $city);
                                            })->count();
                                            
                                            $extensionsValue = \App\Models\LicenseExtension::whereHas('license.workOrder', function($q) use ($city) {
                                                $q->where('city', $city);
                                            })->sum('extension_value');
                                        @endphp
                                        <div class="mb-3">
                                            <i class="fas fa-clock fa-3x" style="color: #f2994a;"></i>
                                        </div>
                                        <div class="mb-3">
                                            <h4 class="mb-1" style="color: #f2994a;">{{ number_format($extensionsCount) }}</h4>
                                            <p class="text-muted mb-0" style="font-size: 0.8rem;">عدد التمديدات</p>
                                            <h5 class="mb-0 mt-2" style="color: #f2c94c; font-size: 0.95rem;">
                                                @if($extensionsValue >= 1000000)
                                                    {{ number_format($extensionsValue / 1000000, 1) }}M
                                                @elseif($extensionsValue >= 1000)
                                                    {{ number_format($extensionsValue / 1000, 1) }}K
                                                @else
                                                    {{ number_format($extensionsValue) }}
                                                @endif
                                            </h5>
                                            <p class="text-muted mb-0" style="font-size: 0.75rem;">قيمة التمديدات</p>
                                        </div>
                                        <a href="{{ $extensionsRoute }}" 
                                           class="btn btn-sm shadow-sm"
                                           style="background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); color: white; min-width: 150px;">
                                            <i class="fas fa-list me-1"></i>
                                            عرض التمديدات
                                        </a>
                                        <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                                            عرض جميع تمديدات أوامر العمل
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Inspections Card -->
                            <div class="col-md-2-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                        <h6 class="mb-0 text-center">
                                            <i class="fas fa-clipboard-check me-2"></i>
                                            الاختبارات
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        @php
                                            $inspectionsRoute = ($project ?? 'riyadh') === 'madinah' 
                                                ? route('admin.quality.inspections.madinah')
                                                : route('admin.quality.inspections.riyadh');
                                            
                                            // حساب إجمالي عدد الاختبارات والناجحة والراسبة
                                            $inspectionsLicenses = \App\Models\License::whereHas('workOrder', function($q) use ($city) {
                                                $q->where('city', $city);
                                            })->where(function($q) {
                                                $q->where('total_tests_count', '>', 0)
                                                  ->orWhereNotNull('lab_tests_data');
                                            })->get();
                                            
                                            // إجمالي عدد الاختبارات
                                            $totalTestsCount = $inspectionsLicenses->sum('total_tests_count');
                                            
                                            // عدد الاختبارات الناجحة
                                            $successfulTestsCount = $inspectionsLicenses->sum('successful_tests_count');
                                            
                                            // عدد الاختبارات الراسبة
                                            $failedTestsCount = $inspectionsLicenses->sum('failed_tests_count');
                                            
                                            // حساب قيمة الرخص التي لديها اختبارات ناجحة
                                            $successfulTestsValue = $inspectionsLicenses->filter(function($license) {
                                                return ($license->successful_tests_count ?? 0) > 0;
                                            })->sum('license_value');
                                            
                                            // حساب قيمة الرخص التي لديها اختبارات راسبة
                                            $failedTestsValue = $inspectionsLicenses->filter(function($license) {
                                                return ($license->failed_tests_count ?? 0) > 0;
                                            })->sum('license_value');
                                        @endphp
                                        <div class="mb-3">
                                            <i class="fas fa-tasks fa-3x" style="color: #4facfe;"></i>
                                        </div>
                                        <div class="mb-3">
                                            <h4 class="mb-1" style="color: #4facfe;">{{ number_format($totalTestsCount) }}</h4>
                                            <p class="text-muted mb-0" style="font-size: 0.8rem;">إجمالي عدد الاختبارات</p>
                                            
                                            <div class="mt-2 d-flex justify-content-around">
                                                <div class="text-center">
                                                    <h5 class="mb-0" style="color: #11998e; font-size: 0.95rem;">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        {{ number_format($successfulTestsCount) }}
                                                    </h5>
                                                    <p class="text-muted mb-0" style="font-size: 0.7rem;">ناجحة</p>
                                                </div>
                                                <div class="text-center">
                                                    <h5 class="mb-0" style="color: #eb3349; font-size: 0.95rem;">
                                                        <i class="fas fa-times-circle me-1"></i>
                                                        {{ number_format($failedTestsCount) }}
                                                    </h5>
                                                    <p class="text-muted mb-0" style="font-size: 0.7rem;">راسبة</p>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3">
                                                <div class="mb-2">
                                                    <small class="text-muted d-block" style="font-size: 0.7rem;">قيمة الناجحة:</small>
                                                    <h6 class="mb-0" style="color: #11998e; font-size: 0.85rem;">
                                                        @if($successfulTestsValue >= 1000000)
                                                            {{ number_format($successfulTestsValue / 1000000, 1) }}M
                                                        @elseif($successfulTestsValue >= 1000)
                                                            {{ number_format($successfulTestsValue / 1000, 1) }}K
                                                        @else
                                                            {{ number_format($successfulTestsValue) }}
                                                        @endif
                                                    </h6>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block" style="font-size: 0.7rem;">قيمة الراسبة:</small>
                                                    <h6 class="mb-0" style="color: #eb3349; font-size: 0.85rem;">
                                                        @if($failedTestsValue >= 1000000)
                                                            {{ number_format($failedTestsValue / 1000000, 1) }}M
                                                        @elseif($failedTestsValue >= 1000)
                                                            {{ number_format($failedTestsValue / 1000, 1) }}K
                                                        @else
                                                            {{ number_format($failedTestsValue) }}
                                                        @endif
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ $inspectionsRoute }}" 
                                           class="btn btn-sm shadow-sm"
                                           style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; min-width: 150px;">
                                            <i class="fas fa-list me-1"></i>
                                            عرض الاختبارات
                                        </a>
                                        <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                                            عرض جميع اختبارات الجودة
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Evacuations Card -->
                            <div class="col-md-2-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header text-white" style="background: linear-gradient(135deg, #8e44ad 0%, #c0392b 100%);">
                                        <h6 class="mb-0 text-center">
                                            <i class="fas fa-truck-loading me-2"></i>
                                            الإخلاءات
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        @php
                                            $evacuationsRoute = ($project ?? 'riyadh') === 'madinah' 
                                                ? route('admin.quality.evacuations.madinah')
                                                : route('admin.quality.evacuations.riyadh');
                                            
                                            // حساب عدد الرخص الغير نشطة وقيمة رخص الإخلاءات
                                            $evacuationsCount = \App\Models\License::whereHas('workOrder', function($q) use ($city) {
                                                $q->where('city', $city);
                                            })
                                            ->where(function($q) {
                                                $q->whereNull('evacuation_date')
                                                  ->orWhere('evacuation_date', '');
                                            })
                                            ->count();
                                            
                                            $evacuationsValue = \App\Models\License::whereHas('workOrder', function($q) use ($city) {
                                                $q->where('city', $city);
                                            })
                                            ->where(function($q) {
                                                $q->whereNull('evacuation_date')
                                                  ->orWhere('evacuation_date', '');
                                            })
                                            ->sum('license_value');
                                        @endphp
                                        <div class="mb-3">
                                            <i class="fas fa-ban fa-3x" style="color: #8e44ad;"></i>
                                        </div>
                                        <div class="mb-3">
                                            <h4 class="mb-1" style="color: #8e44ad;">{{ number_format($evacuationsCount) }}</h4>
                                            <p class="text-muted mb-0" style="font-size: 0.8rem;">عدد الرخص الغير نشطة</p>
                                            <h5 class="mb-0 mt-2" style="color: #c0392b; font-size: 0.95rem;">
                                                @if($evacuationsValue >= 1000000)
                                                    {{ number_format($evacuationsValue / 1000000, 1) }}M
                                                @elseif($evacuationsValue >= 1000)
                                                    {{ number_format($evacuationsValue / 1000, 1) }}K
                                                @else
                                                    {{ number_format($evacuationsValue) }}
                                                @endif
                                            </h5>
                                            <p class="text-muted mb-0" style="font-size: 0.75rem;">قيمة الرخص الغير نشطة</p>
                                        </div>
                                        <a href="{{ $evacuationsRoute }}" 
                                           class="btn btn-sm shadow-sm"
                                           style="background: linear-gradient(135deg, #8e44ad 0%, #c0392b 100%); color: white; min-width: 150px;">
                                            <i class="fas fa-list me-1"></i>
                                            الرخص الغير نشطة
                                        </a>
                                        <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                                            عرض رخص الحفر الغير نشطة
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Overdue and Unexecuted Section -->
            <div class="col-12">
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);">
                        <h5 class="mb-0 text-white text-center">
                            <i class="fas fa-hourglass-end me-2"></i>
                            تجاوز المدة الزمنية والغير منفذه
                        </h5>
                    </div>
                    <div class="card-body py-4">
                        <div class="row g-3">
                            <!-- Overdue Orders Card -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header text-white" style="background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);">
                                        <h6 class="mb-0 text-center">
                                            <i class="fas fa-clock me-2"></i>
                                            أوامر متأخرة
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        @php
                                            $overdueRoute = ($project ?? 'riyadh') === 'madinah' 
                                                ? route('admin.time-management.overdue.madinah')
                                                : route('admin.time-management.overdue.riyadh');
                                            
                                            // حساب عدد الأوامر المتأخرة (كل الأوامر غير المنتهية)
                                            $overdueCount = \App\Models\WorkOrder::where('city', $city)
                                                ->where('execution_status', '!=', 7) // ليست منتهية
                                                ->where('execution_status', '!=', 8) // ليست مستخلص كلي
                                                ->where('execution_status', '!=', 10) // ليست منتهي صرف
                                                ->whereNotNull('approval_date')
                                                ->count();
                                        @endphp
                                        <div class="mb-3">
                                            <i class="fas fa-exclamation-circle fa-3x" style="color: #fc4a1a;"></i>
                                        </div>
                                        <div class="mb-3">
                                            <h2 class="mb-1" style="color: #fc4a1a;">{{ number_format($overdueCount) }}</h2>
                                            <p class="text-muted mb-0" style="font-size: 0.9rem;">عدد الأوامر</p>
                                        </div>
                                        <a href="{{ $overdueRoute }}" 
                                           class="btn btn-sm shadow-sm"
                                           style="background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%); color: white; min-width: 150px;">
                                            <i class="fas fa-list me-1"></i>
                                            عرض الأوامر المتأخرة
                                        </a>
                                        <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                                            أوامر تجاوزت المدة الزمنية المحددة
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Unexecuted Orders Card -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header text-white" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
                                        <h6 class="mb-0 text-center">
                                            <i class="fas fa-times-circle me-2"></i>
                                            أوامر غير منفذة
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        @php
                                            $unexecutedRoute = ($project ?? 'riyadh') === 'madinah' 
                                                ? route('admin.time-management.unexecuted.madinah')
                                                : route('admin.time-management.unexecuted.riyadh');
                                            
                                            // حساب عدد الأوامر الغير منفذة (execution_status = 1)
                                            $unexecutedCount = \App\Models\WorkOrder::where('city', $city)
                                                ->where('execution_status', 1)
                                                ->count();
                                        @endphp
                                        <div class="mb-3">
                                            <i class="fas fa-ban fa-3x" style="color: #e74c3c;"></i>
                                        </div>
                                        <div class="mb-3">
                                            <h2 class="mb-1" style="color: #e74c3c;">{{ number_format($unexecutedCount) }}</h2>
                                            <p class="text-muted mb-0" style="font-size: 0.9rem;">عدد الأوامر</p>
                                        </div>
                                        <a href="{{ $unexecutedRoute }}" 
                                           class="btn btn-sm shadow-sm"
                                           style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; min-width: 150px;">
                                            <i class="fas fa-list me-1"></i>
                                            عرض الأوامر الغير منفذة
                                        </a>
                                        <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                                            أوامر لم يتم تنفيذها حتى الآن
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Detailed Report Card -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header text-white" style="background: linear-gradient(135deg, #fd79a8 0%, #a29bfe 100%);">
                                        <h6 class="mb-0 text-center">
                                            <i class="fas fa-chart-bar me-2"></i>
                                            اوامر  عليها معوقات تنفيذ
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        @php
                                            $detailedReportRoute = ($project ?? 'riyadh') === 'madinah' 
                                                ? route('admin.time-management.detailed-report.madinah')
                                                : route('admin.time-management.detailed-report.riyadh');
                                            
                                            // حساب عدد الأوامر التي عليها معوقات
                                            $obstaclesCount = \App\Models\WorkOrder::where('city', $city)
                                                ->whereHas('survey', function($q) {
                                                    $q->where('has_obstacles', true);
                                                })
                                                ->count();
                                        @endphp
                                        <div class="mb-3">
                                            <i class="fas fa-file-alt fa-3x" style="color: #fd79a8;"></i>
                                        </div>
                                        <div class="mb-3">
                                            <h2 class="mb-1" style="color: #fd79a8;">{{ number_format($obstaclesCount) }}</h2>
                                            <p class="text-muted mb-0" style="font-size: 0.9rem;">أوامر عليها معوقات</p>
                                        </div>
                                        <a href="{{ $detailedReportRoute }}" 
                                           class="btn btn-sm shadow-sm"
                                           style="background: linear-gradient(135deg, #fd79a8 0%, #a29bfe 100%); color: white; min-width: 150px;">
                                            <i class="fas fa-list me-1"></i>
                                            عرض التقرير المفصل
                                        </a>
                                        <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                                            تحليل شامل لأوامر العمل
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Materials and Warehouses Status Section -->
                <div class="col-12">
                    <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);">
                        <h5 class="mb-0 text-white text-center">
                            <i class="fas fa-warehouse me-2"></i>
                            موقف المواد والمستودعات
                                </h5>
                                                </div>
                    <div class="card-body py-4">
                        <div class="row g-3">
                            <!-- Materials Difference (Executed vs Disbursed) Card -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header text-white" style="background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);">
                                        <h6 class="mb-0 text-center">
                                            <i class="fas fa-balance-scale me-2"></i>
                                            الفرق بين المنفذ والمصروف
                                        </h6>
                                            </div>
                                    <div class="card-body text-center">
                                        @php
                                            $executedDisbursedRoute = ($project ?? 'riyadh') === 'madinah' 
                                                ? route('admin.materials.executed-disbursed.madinah')
                                                : route('admin.materials.executed-disbursed.riyadh');
                                    @endphp
                                        <div class="mb-3">
                                            <i class="fas fa-chart-line fa-3x" style="color: #6c5ce7;"></i>
                                            </div>
                                        <a href="{{ $executedDisbursedRoute }}" 
                                           class="btn btn-sm shadow-sm"
                                           style="background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%); color: white; min-width: 150px;">
                                            <i class="fas fa-list me-1"></i>
                                            عرض الفروقات
                                        </a>
                                        <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                                            مقارنة المواد المنفذة مع المصروفة
                                        </p>
                                                </div>
                                            </div>
                                        </div>

                            <!-- Materials to Return Card -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header text-white" style="background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%);">
                                        <h6 class="mb-0 text-center">
                                            <i class="fas fa-undo me-2"></i>
                                            المواد المطلوب إرجاعها
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        @php
                                            $materialsReturnRoute = ($project ?? 'riyadh') === 'madinah' 
                                                ? route('admin.materials.to-return.madinah')
                                                : route('admin.materials.to-return.riyadh');
                                    @endphp
                                        <div class="mb-3">
                                            <i class="fas fa-box-open fa-3x" style="color: #fdcb6e;"></i>
                                            </div>
                                        <a href="{{ $materialsReturnRoute }}" 
                                           class="btn btn-sm shadow-sm"
                                           style="background: linear-gradient(135deg, #fdcb6e 0%, #e17055 100%); color: white; min-width: 150px;">
                                            <i class="fas fa-list me-1"></i>
                                            عرض المواد للإرجاع
                                        </a>
                                        <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                                            المواد الزائدة المطلوب إرجاعها
                                        </p>
                                            </div>
                                        </div>
                                    </div>

                            <!-- Materials to Disburse Card -->
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header text-white" style="background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);">
                                        <h6 class="mb-0 text-center">
                                            <i class="fas fa-truck-loading me-2"></i>
                                            المواد المطلوب صرفها
                                        </h6>
                                </div>
                                    <div class="card-body text-center">
                                        @php
                                            $materialsDisburseRoute = ($project ?? 'riyadh') === 'madinah' 
                                                ? route('admin.materials.to-disburse.madinah')
                                                : route('admin.materials.to-disburse.riyadh');
                                    @endphp
                                        <div class="mb-3">
                                            <i class="fas fa-dolly fa-3x" style="color: #00b894;"></i>
                        </div>
                                        <a href="{{ $materialsDisburseRoute }}" 
                                           class="btn btn-sm shadow-sm"
                                           style="background: linear-gradient(135deg, #00b894 0%, #00cec9 100%); color: white; min-width: 150px;">
                                            <i class="fas fa-list me-1"></i>
                                            عرض المواد للصرف
                                        </a>
                                        <p class="text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                                            المواد الناقصة المطلوب صرفها
                                        </p>
                            </div>
                                            </div>
                                        </div>
                                                </div>
                                </div>
                                        </div>
            </div>
    </div>
</div>

@push('styles')
<style>
    /* Custom column for 5 cards in quality section */
    .col-md-2-4 {
        flex: 0 0 auto;
        width: 20%; /* 5 cards per row */
    }
    
    @media (max-width: 768px) {
        .col-md-2-4 {
            width: 100% !important; /* 1 card per row on mobile */
        }
    }
    
    @media (min-width: 769px) and (max-width: 991px) {
        .col-md-2-4 {
            width: 50% !important; /* 2 cards per row on tablet */
        }
    }

    /* Responsive grid for 11 cards */
    @media (max-width: 768px) {
        .col-6.col-md-4.col-lg {
            flex: 0 0 auto;
            width: 50% !important; /* 2 cards per row on mobile */
        }
    }
    
    @media (min-width: 769px) and (max-width: 1199px) {
        .col-6.col-md-4.col-lg {
            flex: 0 0 auto;
            width: 33.33% !important; /* 3 cards per row on tablet */
        }
    }
    
    @media (min-width: 1200px) {
        .col-6.col-md-4.col-lg {
            flex: 0 0 auto;
            width: 9.09% !important; /* 11 cards per row on desktop */
        }
    }
    
    /* BI Dashboard Styles */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .bg-gradient-danger {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }
    
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    }

    .dashboard-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
    }
    
    .dashboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
    }
    
    .dashboard-card .card-header {
        padding: 1.5rem 1rem;
    }
    
    .dashboard-card .card-body {
        padding: 1.25rem;
    }
    
    .dashboard-icon {
        opacity: 0.9;
    }
    
    .stat-item {
        padding: 8px;
    }
    
    .stat-number {
        font-size: 1.4rem;
        font-weight: bold;
        margin-bottom: 0;
    }
    
    .stat-item small {
        font-size: 0.8rem;
        line-height: 1.3;
    }
    
    .dashboard-btn {
        border-radius: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
        padding: 0.65rem 1rem;
        font-size: 0.9rem;
    }
    
    .dashboard-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .text-purple {
        color: #6f42c1 !important;
    }
    
    .progress {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-bar {
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    /* Project Switcher Styles */
    .btn-group .btn {
        font-weight: 600;
        border-radius: 8px !important;
        transition: all 0.3s ease;
    }
    
    .btn-group .btn:first-child {
        border-top-left-radius: 8px !important;
        border-bottom-left-radius: 8px !important;
    }
    
    .btn-group .btn:last-child {
        border-top-right-radius: 8px !important;
        border-bottom-right-radius: 8px !important;
    }
    
    .btn-group .btn-light {
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        border-color: rgba(255, 255, 255, 0.5);
    }
    
    .btn-group .btn-outline-light {
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.8);
        border-color: rgba(255, 255, 255, 0.3);
    }
    
    .btn-group .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-color: rgba(255, 255, 255, 0.5);
    }

    /* Execution Status List */
    .execution-status-list {
        display: flex;
        flex-direction: column;
        gap: 5px;
        max-height: 280px;
        overflow-y: auto;
    }
    
    .status-item-small {
        padding: 5px 8px;
        border-radius: 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
    }
    
    .status-item-small:hover {
        transform: translateX(-3px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .status-item-small .status-count {
        font-weight: bold;
        font-size: 0.95rem;
        min-width: 25px;
    }
    
    .status-item-small small {
        font-size: 0.7rem;
        flex: 1;
        text-align: right;
        margin-right: 6px;
    }

    /* RTL Support */
    body {
        direction: rtl;
        text-align: right;
    }
    
    .card-header h4, .card-header p {
        text-align: center;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .col-xl-4 {
            margin-bottom: 2rem;
        }
        
        .dashboard-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click events to cards
    document.querySelectorAll('.dashboard-card').forEach(card => {
        card.addEventListener('click', function() {
            const button = this.querySelector('.dashboard-btn');
            if (button) {
                button.click();
            }
        });
    });
    
    // Add hover effects
    document.querySelectorAll('.dashboard-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
    });
});

    // Calculate and display work orders statistics immediately
    updateWorkOrdersStats();
    
    // Start observing table changes
    observeTableChanges();
    
    // Force update after a short delay to ensure DOM is ready
    setTimeout(() => {
        // Update all stats
        updateWorkOrdersStats();
        updateExecutionStats();
        updateInProgressStats();
        updateExtractStats();
        updateCompletedStats();
    }, 500);
    
    // Additional update after 2 seconds to ensure all APIs have responded
    setTimeout(() => {
        updateWorkOrdersStats();
        updateExecutionStats();
        updateInProgressStats();
        updateExtractStats();
        updateCompletedStats();
        updateQuickStats();
    }, 2000);
    
    // Update stats every 10 seconds to keep data fresh
    setInterval(() => {
        updateWorkOrdersStats();
        updateExecutionStats();
        updateInProgressStats();
        updateExtractStats();
        updateCompletedStats();
        updateQuickStats();
    }, 10000);
});

// Update quick stats overview
function updateQuickStats() {
    const quickReceiptsElement = document.getElementById('quickReceiptsCount');
    const quickExecutionElement = document.getElementById('quickExecutionCount');
    const quickInProgressElement = document.getElementById('quickInProgressCount');
    const quickExecutedElement = document.getElementById('quickExecutedCount');
    const quickCompletedElement = document.getElementById('quickCompletedCount');
    
    // Get values from main cards
    const receiptsCount = document.getElementById('workOrdersCount')?.textContent || '0';
    const executionCount = document.getElementById('executedOrdersCount')?.textContent || '0';
    const inProgressCount = document.getElementById('inProgressOrdersCount')?.textContent || '0';
    const completedCount = document.getElementById('completedOrdersCount')?.textContent || '0';
    
    if (quickReceiptsElement) quickReceiptsElement.textContent = receiptsCount;
    if (quickExecutionElement) quickExecutionElement.textContent = executionCount;
    if (quickInProgressElement) quickInProgressElement.textContent = inProgressCount;
    if (quickCompletedElement) quickCompletedElement.textContent = completedCount;
    
    // Update all status counts from API
    updateExecutedCount();
    updateDelivery155Count();
    updateFirstExtractCount();
    updatePaidFirstCount();
    updateSecondExtractCount();
    updateTotalExtractCount();
    updateCertificateCount();
    updateCompletedCount();
    updateDroopCount();
}

// Update executed count from API
function updateExecutedCount() {
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    const city = project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    let url = `/api/work-orders/execution?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.summary) {
            // Get count for status 2 (تم التنفيذ بالموقع)
            const executedCount = data.summary.status_counts?.status_2 || 0;
            const executedValue = data.summary.status_values?.status_2_value || 0;
            
            const quickExecutedElement = document.getElementById('quickExecutedCount');
            const quickExecutedValueElement = document.getElementById('quickExecutedValue');
            
            if (quickExecutedElement) {
                quickExecutedElement.textContent = executedCount.toLocaleString('en-US');
            }
            
            if (quickExecutedValueElement) {
                if (executedValue >= 1000000) {
                    quickExecutedValueElement.textContent = (executedValue / 1000000).toFixed(1) + 'M';
                } else if (executedValue >= 1000) {
                    quickExecutedValueElement.textContent = (executedValue / 1000).toFixed(1) + 'K';
                } else {
                    quickExecutedValueElement.textContent = executedValue.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }
            }
        }
    })
    .catch(error => {
        console.error('Error fetching executed count:', error);
    });
}

// Update delivery155 count from API
function updateDelivery155Count() {
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    const city = project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    let url = `/api/work-orders/execution?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.summary) {
            // Get count for status 3 (تم تسليم 155 - جاري إصدار شهادة الإنجاز)
            const delivery155Count = data.summary.status_counts?.status_3 || 0;
            const delivery155Value = data.summary.status_values?.status_3_value || 0;
            
            const quickDelivery155Element = document.getElementById('quickDelivery155Count');
            const quickDelivery155ValueElement = document.getElementById('quickDelivery155Value');
            
            if (quickDelivery155Element) {
                quickDelivery155Element.textContent = delivery155Count.toLocaleString('en-US');
            }
            
            if (quickDelivery155ValueElement) {
                if (delivery155Value >= 1000000) {
                    quickDelivery155ValueElement.textContent = (delivery155Value / 1000000).toFixed(1) + 'M';
                } else if (delivery155Value >= 1000) {
                    quickDelivery155ValueElement.textContent = (delivery155Value / 1000).toFixed(1) + 'K';
                } else {
                    quickDelivery155ValueElement.textContent = delivery155Value.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }
            }
        }
    })
    .catch(error => {
        console.error('Error fetching delivery155 count:', error);
    });
}

// Update first extract count from API
function updateFirstExtractCount() {
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    const city = project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    let url = `/api/work-orders/execution?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.summary) {
            // Get count for status 4 (إعداد مستخلص الدفعة الجزئية الأولى وجاري الصرف)
            const firstExtractCount = data.summary.status_counts?.status_4 || 0;
            const firstExtractValue = data.summary.status_values?.status_4_value || 0;
            
            const quickFirstExtractElement = document.getElementById('quickFirstExtractCount');
            const quickFirstExtractValueElement = document.getElementById('quickFirstExtractValue');
            
            if (quickFirstExtractElement) {
                quickFirstExtractElement.textContent = firstExtractCount.toLocaleString('en-US');
            }
            
            if (quickFirstExtractValueElement) {
                if (firstExtractValue >= 1000000) {
                    quickFirstExtractValueElement.textContent = (firstExtractValue / 1000000).toFixed(1) + 'M';
                } else if (firstExtractValue >= 1000) {
                    quickFirstExtractValueElement.textContent = (firstExtractValue / 1000).toFixed(1) + 'K';
                } else {
                    quickFirstExtractValueElement.textContent = firstExtractValue.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }
            }
        }
    })
    .catch(error => {
        console.error('Error fetching first extract count:', error);
    });
}

// Update paid first extract count from API
function updatePaidFirstCount() {
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    const city = project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    let url = `/api/work-orders/execution?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.summary) {
            // Get count for status 5 (تم صرف مستخلص الدفعة الجزئية الأولى)
            const paidFirstCount = data.summary.status_counts?.status_5 || 0;
            const paidFirstValue = data.summary.status_values?.status_5_value || 0;
            
            const quickPaidFirstElement = document.getElementById('quickPaidFirstCount');
            const quickPaidFirstValueElement = document.getElementById('quickPaidFirstValue');
            
            if (quickPaidFirstElement) {
                quickPaidFirstElement.textContent = paidFirstCount.toLocaleString('en-US');
            }
            
            if (quickPaidFirstValueElement) {
                if (paidFirstValue >= 1000000) {
                    quickPaidFirstValueElement.textContent = (paidFirstValue / 1000000).toFixed(1) + 'M';
                } else if (paidFirstValue >= 1000) {
                    quickPaidFirstValueElement.textContent = (paidFirstValue / 1000).toFixed(1) + 'K';
                } else {
                    quickPaidFirstValueElement.textContent = paidFirstValue.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }
            }
        }
    })
    .catch(error => {
        console.error('Error fetching paid first count:', error);
    });
}

// Update second extract count from API
function updateSecondExtractCount() {
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    const city = project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    let url = `/api/work-orders/execution?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.summary) {
            // Get count for status 6 (إعداد مستخلص الدفعة الجزئية الثانية وجاري الصرف)
            const secondExtractCount = data.summary.status_counts?.status_6 || 0;
            const secondExtractValue = data.summary.status_values?.status_6_value || 0;
            
            const quickSecondExtractElement = document.getElementById('quickSecondExtractCount');
            const quickSecondExtractValueElement = document.getElementById('quickSecondExtractValue');
            
            if (quickSecondExtractElement) {
                quickSecondExtractElement.textContent = secondExtractCount.toLocaleString('en-US');
            }
            
            if (quickSecondExtractValueElement) {
                if (secondExtractValue >= 1000000) {
                    quickSecondExtractValueElement.textContent = (secondExtractValue / 1000000).toFixed(1) + 'M';
                } else if (secondExtractValue >= 1000) {
                    quickSecondExtractValueElement.textContent = (secondExtractValue / 1000).toFixed(1) + 'K';
                } else {
                    quickSecondExtractValueElement.textContent = secondExtractValue.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }
            }
        }
    })
    .catch(error => {
        console.error('Error fetching second extract count:', error);
    });
}

// Update certificate count from API
function updateCertificateCount() {
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    const city = project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    let url = `/api/work-orders/execution?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.summary) {
            // Get count for status 8 (تم إصدار شهادة الإنجاز)
            const certificateCount = data.summary.status_counts?.status_8 || 0;
            const certificateValue = data.summary.status_values?.status_8_value || 0;
            
            const quickCertificateElement = document.getElementById('quickCertificateCount');
            const quickCertificateValueElement = document.getElementById('quickCertificateValue');
            
            if (quickCertificateElement) {
                quickCertificateElement.textContent = certificateCount.toLocaleString('en-US');
            }
            
            if (quickCertificateValueElement) {
                if (certificateValue >= 1000000) {
                    quickCertificateValueElement.textContent = (certificateValue / 1000000).toFixed(1) + 'M';
                } else if (certificateValue >= 1000) {
                    quickCertificateValueElement.textContent = (certificateValue / 1000).toFixed(1) + 'K';
                } else {
                    quickCertificateValueElement.textContent = certificateValue.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }
            }
        }
    })
    .catch(error => {
        console.error('Error fetching certificate count:', error);
    });
}

// Update total extract count from API
function updateTotalExtractCount() {
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    const city = project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    let url = `/api/work-orders/execution?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.summary) {
            // Get count for status 10 (تم إعداد المستخلص الكلي وجاري الصرف)
            const totalExtractCount = data.summary.status_counts?.status_10 || 0;
            const totalExtractValue = data.summary.status_values?.status_10_value || 0;
            
            const quickTotalExtractElement = document.getElementById('quickTotalExtractCount');
            const quickTotalExtractValueElement = document.getElementById('quickTotalExtractValue');
            
            if (quickTotalExtractElement) {
                quickTotalExtractElement.textContent = totalExtractCount.toLocaleString('en-US');
            }
            
            if (quickTotalExtractValueElement) {
                if (totalExtractValue >= 1000000) {
                    quickTotalExtractValueElement.textContent = (totalExtractValue / 1000000).toFixed(1) + 'M';
                } else if (totalExtractValue >= 1000) {
                    quickTotalExtractValueElement.textContent = (totalExtractValue / 1000).toFixed(1) + 'K';
                } else {
                    quickTotalExtractValueElement.textContent = totalExtractValue.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }
            }
        }
    })
    .catch(error => {
        console.error('Error fetching total extract count:', error);
    });
}

// Update completed count from API
function updateCompletedCount() {
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    const city = project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    let url = `/api/work-orders/execution?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.summary) {
            // Get count for status 7 (منتهي تم الصرف)
            const completedCount = data.summary.status_counts?.status_7 || 0;
            const completedValue = data.summary.status_values?.status_7_value || 0;
            
            const quickCompletedElement = document.getElementById('quickCompletedCount');
            const quickCompletedValueElement = document.getElementById('quickCompletedValue');
            
            if (quickCompletedElement) {
                quickCompletedElement.textContent = completedCount.toLocaleString('en-US');
            }
            
            if (quickCompletedValueElement) {
                if (completedValue >= 1000000) {
                    quickCompletedValueElement.textContent = (completedValue / 1000000).toFixed(1) + 'M';
                } else if (completedValue >= 1000) {
                    quickCompletedValueElement.textContent = (completedValue / 1000).toFixed(1) + 'K';
                } else {
                    quickCompletedValueElement.textContent = completedValue.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }
            }
        }
    })
    .catch(error => {
        console.error('Error fetching completed count:', error);
    });
}

// Update droop count from API
function updateDroopCount() {
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    const city = project === 'madinah' ? 'المدينة المنورة' : 'الرياض';
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    let url = `/api/work-orders/execution?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.summary) {
            // Get count for status 9 (دروب)
            const droopCount = data.summary.status_counts?.status_9 || 0;
            const droopValue = data.summary.status_values?.status_9_value || 0;
            
            const quickDroopElement = document.getElementById('quickDroopCount');
            const quickDroopValueElement = document.getElementById('quickDroopValue');
            
            if (quickDroopElement) {
                quickDroopElement.textContent = droopCount.toLocaleString('en-US');
            }
            
            if (quickDroopValueElement) {
                if (droopValue >= 1000000) {
                    quickDroopValueElement.textContent = (droopValue / 1000000).toFixed(1) + 'M';
                } else if (droopValue >= 1000) {
                    quickDroopValueElement.textContent = (droopValue / 1000).toFixed(1) + 'K';
                } else {
                    quickDroopValueElement.textContent = droopValue.toLocaleString('en-US', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                }
            }
        }
    })
    .catch(error => {
        console.error('Error fetching droop count:', error);
    });
}

// Set quick date range
function setQuickDateRange(period) {
    const today = new Date();
    const endDate = today.toISOString().split('T')[0];
    let startDate;
    
    switch(period) {
        case 'day':
            // آخر يوم (24 ساعة)
            const dayAgo = new Date(today);
            dayAgo.setDate(today.getDate() - 1);
            startDate = dayAgo.toISOString().split('T')[0];
            break;
        case 'quarter':
            // ربع سنة (3 أشهر)
            const quarterAgo = new Date(today);
            quarterAgo.setMonth(today.getMonth() - 3);
            startDate = quarterAgo.toISOString().split('T')[0];
            break;
        case 'half':
            // نصف سنة (6 أشهر)
            const halfYearAgo = new Date(today);
            halfYearAgo.setMonth(today.getMonth() - 6);
            startDate = halfYearAgo.toISOString().split('T')[0];
            break;
        case 'year':
            // سنة كاملة (12 شهر)
            const yearAgo = new Date(today);
            yearAgo.setFullYear(today.getFullYear() - 1);
            startDate = yearAgo.toISOString().split('T')[0];
            break;
    }
    
    document.getElementById('filter_start_date').value = startDate;
    document.getElementById('filter_end_date').value = endDate;
}

// Clear date filter
function clearDateFilter() {
    document.getElementById('filter_start_date').value = '';
    document.getElementById('filter_end_date').value = '';
}

// Apply date filter - reload all stats with date filters
function applyDateFilter() {
    const startDate = document.getElementById('filter_start_date').value;
    const endDate = document.getElementById('filter_end_date').value;
    
    console.log('Applying date filter:', startDate, 'to', endDate);
    
    // Show loading indicator
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري التحميل...';
    btn.disabled = true;
    
    // Update all stats with date filters
    setTimeout(() => {
        updateWorkOrdersStats();
        updateExecutionStats();
        updateInProgressStats();
        updateExtractStats();
        updateCompletedStats();
        updateQuickStats();
        
        // Restore button
        btn.innerHTML = originalText;
        btn.disabled = false;
    }, 100);
}

// Function to update work orders statistics from the API
function updateWorkOrdersStats() {
    console.log('Fetching receipts stats from API...');
    
    // Get project from URL or default to 'riyadh'
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    
    // Get date filters
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    // Build URL with parameters
    let url = `/api/work-orders/receipts?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    console.log('Receipts API URL:', url);
    
    // Call the receipts API
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.summary) {
            console.log('Received receipts stats:', data.summary);
            // Update the display with the summary data
            updateStatsDisplay(
                data.summary.total_orders || 0,
                data.summary.total_value || 0,
                data.summary.percentage || 0
            );
        } else {
            console.error('Failed to fetch receipts data:', data.message || 'No summary data');
            // Use fallback data
            const stats = getWorkOrdersStats();
            updateStatsDisplay(stats.count, stats.total, 0);
        }
    })
    .catch(error => {
        console.error('Error fetching receipts data:', error);
        // Use fallback data
        const stats = getWorkOrdersStats();
        updateStatsDisplay(stats.count, stats.total, 0);
    });
}

// Function to update the statistics display
function updateStatsDisplay(count, value, percentage) {
    const countElement = document.getElementById('workOrdersCount');
    const valueElement = document.getElementById('totalValue');
    const progressBar = document.getElementById('receiptsProgressBar');
    const progressText = document.getElementById('receiptsProgressText');
    
    if (countElement) {
        countElement.textContent = count.toLocaleString('en-US');
    }
    
    if (valueElement) {
        if (value >= 1000000) {
            valueElement.textContent = (value / 1000000).toFixed(1) + 'M';
        } else if (value >= 1000) {
            valueElement.textContent = (value / 1000).toFixed(1) + 'K';
        } else {
            valueElement.textContent = value.toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }
    }
    
    // Update progress bar if percentage is provided
    if (progressBar && percentage !== undefined) {
        progressBar.style.width = percentage + '%';
    }
    
    if (progressText && percentage !== undefined) {
        progressText.textContent = `معدل الاستلام ${percentage}%`;
    }
    
    console.log('Updated stats display - Count:', count, 'Value:', value, 'Percentage:', percentage);
}

// Function to fetch work orders data from API
function fetchWorkOrdersData() {
    // Try to get data from the current page context first
    try {
        // Check if there's Laravel data available
        if (typeof Laravel !== 'undefined' && Laravel.workOrdersData) {
            updateStatsDisplay(Laravel.workOrdersData.count, Laravel.workOrdersData.total_value);
        return;
        }
    } catch (e) {
        console.log('No Laravel data available');
    }
    
    // Fallback: Use sample data or make API call
    // For now, we'll use sample data based on the visible table
    const sampleData = calculateFromVisibleData();
    updateStatsDisplay(sampleData.count, sampleData.total, 0);
}

// Function to calculate data from visible elements
function calculateFromVisibleData() {
    // Try multiple selectors to find work order data
    let count = 0;
    let total = 0;
    
    // Method 1: Look for work order rows with specific class
    const workOrderRows = document.querySelectorAll('.work-order-row');
    if (workOrderRows.length > 0) {
        count = workOrderRows.length;
        workOrderRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 12) {
                const valueCell = cells[12];
                if (valueCell) {
                    const valueText = valueCell.textContent.trim();
                    console.log('Processing value text:', valueText);
                    
                    // More precise regex to extract numbers
                    const valueMatch = valueText.match(/([\d,]+\.?\d*)/);
                    if (valueMatch) {
                        const numericValue = parseFloat(valueMatch[1].replace(/,/g, ''));
                        if (!isNaN(numericValue) && numericValue > 0) {
                            total += numericValue;
                            console.log('Added value:', numericValue);
                        }
                    }
                }
            }
        });
        console.log('Method 1 - Count:', count, 'Total:', total);
        return { count: count, total: total };
    }
    
    // Method 2: Look for any table rows in tbody
    const tableRows = document.querySelectorAll('table tbody tr');
    if (tableRows.length > 0) {
        count = tableRows.length;
        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 12) {
                const valueCell = cells[12];
                if (valueCell) {
                    const valueText = valueCell.textContent.trim();
                    const valueMatch = valueText.match(/[\d,]+\.?\d*/);
                    if (valueMatch) {
                        const numericValue = parseFloat(valueMatch[0].replace(/,/g, ''));
                        if (!isNaN(numericValue)) {
                            total += numericValue;
                        }
                    }
                }
            }
        });
        return { count: count, total: total };
    }
    
    // Method 3: Use empty data if nothing found
    console.log('No table data found, using empty data');
    return { count: 0, total: 0 };
}

// Add observer to watch for table changes
function observeTableChanges() {
    // Try multiple possible table containers
    const possibleTargets = ['#workOrdersTableBody', 'tbody', '#resultsTable tbody'];
    
    possibleTargets.forEach(selector => {
        const targetNode = document.querySelector(selector);
        if (targetNode) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList') {
                        // Table content changed, update stats
                        setTimeout(updateWorkOrdersStats, 100);
                    }
                });
            });
            
            observer.observe(targetNode, { 
                childList: true, 
                subtree: true 
            });
            console.log('Observing changes in:', selector);
        }
    });
}

// Force update stats with debugging
function forceUpdateStats() {
    console.log('Force updating stats...');
    
    // Try to get stats from current page
    const sampleData = calculateFromVisibleData();
    console.log('Calculated data:', sampleData);
    
    // Always show at least the sample data
    updateStatsDisplay(sampleData.count, sampleData.total, 0);
    
    // Also try the main update function
    updateWorkOrdersStats();
}

// Alternative method to calculate total from Laravel data if available
function calculateFromLaravelData() {
    // Check if we have access to Laravel data
    if (typeof workOrders !== 'undefined' && Array.isArray(workOrders)) {
        let total = 0;
        workOrders.forEach(order => {
            if (order.order_value_without_consultant) {
                total += parseFloat(order.order_value_without_consultant) || 0;
            }
        });
        return { count: workOrders.length, total: total };
    }
    return null;
}

// Enhanced calculation with multiple fallbacks
function getWorkOrdersStats() {
    console.log('Getting work orders stats...');
    
    // Method 1: Try Laravel data
    const laravelData = calculateFromLaravelData();
    if (laravelData && laravelData.count > 0) {
        console.log('Using Laravel data:', laravelData);
        return laravelData;
    }
    
    // Method 2: Try from visible table
    const visibleData = calculateFromVisibleData();
    if (visibleData && visibleData.count > 0) {
        console.log('Using visible table data:', visibleData);
        return visibleData;
    }
    
    // Method 3: Manual calculation from specific selectors
    const manualData = calculateManually();
    if (manualData && manualData.count > 0) {
        console.log('Using manual calculation:', manualData);
        return manualData;
    }
    
    // Fallback: Use empty data
    console.log('No data found, using empty fallback');
    return { count: 0, total: 0 };
}

// Manual calculation method
function calculateManually() {
    const rows = document.querySelectorAll('tr.work-order-row, tbody tr');
    let count = 0;
    let total = 0;
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        // Look for the value column (usually contains "ريال")
        for (let i = 0; i < cells.length; i++) {
            const cellText = cells[i].textContent.trim();
            if (cellText.includes('ريال') && cellText.match(/[\d,]+\.?\d*/)) {
                count++;
                const valueMatch = cellText.match(/([\d,]+\.?\d*)/);
                if (valueMatch) {
                    const numericValue = parseFloat(valueMatch[1].replace(/,/g, ''));
                    if (!isNaN(numericValue) && numericValue > 0) {
                        total += numericValue;
                        console.log('Manual calculation - added:', numericValue, 'from:', cellText);
                    }
                }
                break; // Found the value column, move to next row
            }
        }
    });
    
    return { count: count, total: total };
}

// Calculate execution statistics
// Calculate execution statistics from API
function calculateExecutionStats() {
    console.log('Fetching execution stats from API...');
    
    // Get project from URL or default to 'riyadh'
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    
    // Call the execution API
    return fetch(`/api/work-orders/execution?project=${project}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
        .then(response => response.json())
        .then(data => {
        if (data.success) {
            console.log('Received execution stats:', data.summary);
            return {
                executedCount: data.summary.total_orders || 0,
                totalExecutedValue: data.summary.total_executed_value || 0,
                executionRate: data.summary.execution_percentage || 0,
                totalOrders: data.summary.total_orders || 0
            };
        } else {
            console.error('Failed to fetch execution data:', data.message);
            return {
                executedCount: 0,
                totalExecutedValue: 0,
                executionRate: 0,
                totalOrders: 0
            };
        }
    })
    .catch(error => {
        console.error('Error fetching execution data:', error);
        return {
            executedCount: 0,
            totalExecutedValue: 0,
            executionRate: 0,
            totalOrders: 0
        };
    });
}

// Update execution statistics display
function updateExecutionStats() {
    console.log('Fetching execution stats with status counts from API...');
    
    // Get project from URL or default to 'riyadh'
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    
    // Get date filters
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    // Build URL with parameters
    let url = `/api/work-orders/execution?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    console.log('Execution API URL:', url);
    
    // Call the execution API to get full data including status counts
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.summary) {
            console.log('Received execution stats with status counts:', data.summary);
            const stats = data.summary;
            
            // Update main stats
        const countElement = document.getElementById('executedOrdersCount');
        const valueElement = document.getElementById('totalExecutedValue');
        const progressBar = document.getElementById('executionProgressBar');
        const progressText = document.getElementById('executionProgressText');
        
        if (countElement) {
                countElement.textContent = (stats.total_orders || 0).toLocaleString('en-US');
        }
        
        if (valueElement) {
                const value = stats.total_executed_value || 0;
                if (value >= 1000000) {
                    valueElement.textContent = (value / 1000000).toFixed(1) + 'M';
                } else if (value >= 1000) {
                    valueElement.textContent = (value / 1000).toFixed(1) + 'K';
            } else {
                    valueElement.textContent = value.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }
        }
        
        if (progressBar) {
                progressBar.style.width = (stats.execution_percentage || 0) + '%';
        }
        
        if (progressText) {
                progressText.textContent = `معدل الإنجاز ${stats.execution_percentage || 0}%`;
            }
            
            // Update status counts
            if (stats.status_counts) {
                const statusCounts = stats.status_counts;
                
                const status2Element = document.getElementById('status2Count');
                const status3Element = document.getElementById('status3Count');
                const status4Element = document.getElementById('status4Count');
                const status5Element = document.getElementById('status5Count');
                const status6Element = document.getElementById('status6Count');
                const status8Element = document.getElementById('status8Count');
                const status10Element = document.getElementById('status10Count');
                
                if (status2Element) status2Element.textContent = statusCounts.status_2 || 0;
                if (status3Element) status3Element.textContent = statusCounts.status_3 || 0;
                if (status4Element) status4Element.textContent = statusCounts.status_4 || 0;
                if (status5Element) status5Element.textContent = statusCounts.status_5 || 0;
                if (status6Element) status6Element.textContent = statusCounts.status_6 || 0;
                if (status8Element) status8Element.textContent = statusCounts.status_8 || 0;
                if (status10Element) status10Element.textContent = statusCounts.status_10 || 0;
            }
        } else {
            console.error('Failed to fetch execution data:', data.message);
        }
    })
    .catch(error => {
        console.error('Error fetching execution data:', error);
    });
}

// Calculate in-progress work orders statistics from API
function calculateInProgressStats() {
    console.log('Fetching in-progress stats from API...');
    
    // Get project from URL or default to 'riyadh'
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    
    // Get date filters
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    // Build URL with parameters
    let url = `/api/work-orders/inprogress?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    console.log('InProgress API URL:', url);
    
    // Call the in-progress API
    return fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
        .then(response => response.json())
        .then(data => {
        if (data.success) {
            console.log('Received in-progress stats:', data.summary);
            return {
                inProgressCount: data.summary.total_orders || 0,
                inProgressTotalValue: data.summary.total_value || 0,
                inProgressRate: data.summary.percentage || 0,
                totalOrders: data.summary.total_orders || 0
            };
            } else {
            console.error('Failed to fetch in-progress data:', data.message);
            return {
                inProgressCount: 0,
                inProgressTotalValue: 0,
                inProgressRate: 0,
                totalOrders: 0
            };
            }
        })
        .catch(error => {
        console.error('Error fetching in-progress data:', error);
        return {
            inProgressCount: 0,
            inProgressTotalValue: 0,
            inProgressRate: 0,
            totalOrders: 0
        };
    });
}

// Calculate extract statistics from API
function calculateExtractStats() {
    console.log('Fetching extract stats from API...');
    
    // Get project from URL or default to 'riyadh'
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    
    // Get date filters
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    // Build URL with parameters
    let url = `/api/work-orders/extracts?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    console.log('Extracts API URL:', url);
    
    // Call the extracts API
    return fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Received extract stats:', data.summary);
            return {
                extractsValue: data.summary.total_extract_value || 0,
                extractOrdersCount: data.summary.total_orders || 0,
                extractPercentage: data.summary.extract_percentage || 0
            };
        } else {
            console.error('Failed to fetch extract data:', data.message);
            return {
                extractsValue: 0,
                extractOrdersCount: 0,
                extractPercentage: 0
            };
        }
    })
    .catch(error => {
        console.error('Error fetching extract data:', error);
        return {
            extractsValue: 0,
            extractOrdersCount: 0,
            extractPercentage: 0
        };
    });
}

// Calculate completed (منتهي تم الصرف) statistics from API
function calculateCompletedStats() {
    console.log('Fetching completed stats from API...');
    
    // Get project from URL or default to 'riyadh'
    const project = window.location.pathname.includes('madinah') ? 'madinah' : 'riyadh';
    
    // Get date filters
    const startDate = document.getElementById('filter_start_date')?.value || '';
    const endDate = document.getElementById('filter_end_date')?.value || '';
    
    // Build URL with parameters
    let url = `/api/work-orders/completed?project=${project}`;
    if (startDate) url += `&start_date=${startDate}`;
    if (endDate) url += `&end_date=${endDate}`;
    
    console.log('Completed API URL:', url);
    
    // Call the completed orders API
    return fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Received completed stats:', data.summary);
            return {
                completedCount: data.summary.total_orders || 0,
                completedTotalValue: data.summary.total_final_value || 0,
                completionPercentage: data.summary.completion_percentage || 0,
                averageFinalValue: data.summary.average_final_value || 0
            };
        } else {
            console.error('Failed to fetch completed data:', data.message);
            return {
                completedCount: 0,
                completedTotalValue: 0,
                completionPercentage: 0,
                averageFinalValue: 0
            };
        }
    })
    .catch(error => {
        console.error('Error fetching completed data:', error);
        return {
            completedCount: 0,
            completedTotalValue: 0,
            completionPercentage: 0,
            averageFinalValue: 0
        };
    });
}

// Update in-progress statistics display
function updateInProgressStats() {
    calculateInProgressStats().then(stats => {
        const countElement = document.getElementById('inProgressOrdersCount');
        const valueElement = document.getElementById('inProgressTotalValue');
        const progressBar = document.getElementById('inProgressProgressBar');
        const progressText = document.getElementById('inProgressProgressText');
        
        // Quick Stats Elements
        const quickCountElement = document.getElementById('quickInProgressCount');
        const quickValueElement = document.getElementById('quickInProgressValue');
        
        if (countElement) {
            countElement.textContent = stats.inProgressCount.toLocaleString('en-US');
        }
        
        // Update quick stats count
        if (quickCountElement) {
            quickCountElement.textContent = stats.inProgressCount.toLocaleString('en-US');
        }
        
        if (valueElement) {
            if (stats.inProgressTotalValue >= 1000000) {
                valueElement.textContent = (stats.inProgressTotalValue / 1000000).toFixed(1) + 'M';
            } else if (stats.inProgressTotalValue >= 1000) {
                valueElement.textContent = (stats.inProgressTotalValue / 1000).toFixed(1) + 'K';
            } else {
                valueElement.textContent = stats.inProgressTotalValue.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }
        }
        
        // Update quick stats value
        if (quickValueElement) {
            if (stats.inProgressTotalValue >= 1000000) {
                quickValueElement.textContent = (stats.inProgressTotalValue / 1000000).toFixed(1) + 'M';
            } else if (stats.inProgressTotalValue >= 1000) {
                quickValueElement.textContent = (stats.inProgressTotalValue / 1000).toFixed(1) + 'K';
            } else {
                quickValueElement.textContent = stats.inProgressTotalValue.toLocaleString('en-US', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            }
        }
        
        if (progressBar) {
            progressBar.style.width = stats.inProgressRate + '%';
        }
        
        if (progressText) {
            progressText.textContent = `نسبة أوامر جاري العمل ${stats.inProgressRate}%`;
        }
    });
}

// Update extract statistics display
function updateExtractStats() {
    calculateExtractStats().then(stats => {
        const extractsValueElement = document.getElementById('extractsValue');
        const extractOrdersCountElement = document.getElementById('extractOrdersCount');
        const progressBar = document.getElementById('extractProgressBar');
        const progressText = document.getElementById('extractProgressText');
        
        if (extractsValueElement) {
            // Format the value with K/M notation
            const value = stats.extractsValue;
            if (value >= 1000000) {
                extractsValueElement.textContent = (value / 1000000).toFixed(1) + 'M';
            } else if (value >= 1000) {
                extractsValueElement.textContent = (value / 1000).toFixed(1) + 'K';
            } else {
                extractsValueElement.textContent = value.toLocaleString('en-US');
            }
        }
        
        if (extractOrdersCountElement) {
            extractOrdersCountElement.textContent = stats.extractOrdersCount.toLocaleString('en-US');
        }
        
        if (progressBar) {
            progressBar.style.width = stats.extractPercentage + '%';
        }
        
        if (progressText) {
            progressText.textContent = `نسبة المستخلص ${stats.extractPercentage}%`;
        }
    }).catch(error => {
        console.error('Error updating extract stats:', error);
        // Set default values on error
        const extractsValueElement = document.getElementById('extractsValue');
        const extractOrdersCountElement = document.getElementById('extractOrdersCount');
        
        if (extractsValueElement) {
            extractsValueElement.textContent = '0 ر.س';
        }
        
        if (extractOrdersCountElement) {
            extractOrdersCountElement.textContent = '0';
        }
    });
}

// Update completed statistics display
function updateCompletedStats() {
    calculateCompletedStats().then(stats => {
        const completedCountElement = document.getElementById('completedOrdersCount');
        const completedValueElement = document.getElementById('completedTotalValue');
        const progressBar = document.getElementById('completedProgressBar');
        const progressText = document.getElementById('completedProgressText');
        
        if (completedCountElement) {
            completedCountElement.textContent = stats.completedCount.toLocaleString('en-US');
        }
        
        if (completedValueElement) {
            // Format the value with K/M notation
            const value = stats.completedTotalValue;
            if (value >= 1000000) {
                completedValueElement.textContent = (value / 1000000).toFixed(1) + 'M';
            } else if (value >= 1000) {
                completedValueElement.textContent = (value / 1000).toFixed(1) + 'K';
            } else {
                completedValueElement.textContent = value.toLocaleString('en-US');
            }
        }
        
        if (progressBar) {
            progressBar.style.width = stats.completionPercentage + '%';
        }
        
        if (progressText) {
            progressText.textContent = `نسبة الإنجاز الكامل ${stats.completionPercentage}%`;
        }
    }).catch(error => {
        console.error('Error updating completed stats:', error);
        // Set default values on error
        const completedCountElement = document.getElementById('completedOrdersCount');
        const completedValueElement = document.getElementById('completedTotalValue');
        
        if (completedCountElement) {
            completedCountElement.textContent = '0';
        }
        
        if (completedValueElement) {
            completedValueElement.textContent = '0 ر.س';
        }
    });
}

// Add some animation to progress bars
function animateProgressBars() {
    document.querySelectorAll('.progress-bar').forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
}

// Call animation on page load
setTimeout(animateProgressBars, 300);
</script>
@endpush

@endsection 
