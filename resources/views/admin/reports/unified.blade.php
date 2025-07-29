@extends('layouts.admin')

@section('title', 'التقارير العامة للعقد الموحد')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        التقارير العامة للعقد الموحد
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- تقرير إنتاجية الرياض -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-chart-line me-2"></i>
                                        تقرير الإنتاجية - الرياض
                                    </h5>
                                    <p class="card-text text-muted">
                                        عرض تقرير مفصل عن إنتاجية أوامر العمل في مدينة الرياض
                                    </p>
                                    <a href="{{ route('admin.work-orders.productivity.riyadh') }}" class="btn btn-primary">
                                        <i class="fas fa-eye me-1"></i>
                                        عرض التقرير
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- تقرير إنتاجية المدينة -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-chart-line me-2"></i>
                                        تقرير الإنتاجية العام - مشروع المدينة المنورة
                                    </h5>
                                    <p class="card-text text-muted">
                                        عرض تقرير الإنتاجية العام لمشروع المدينة المنورة
                                    </p>
                                    <a href="{{ route('admin.work-orders.productivity.madinah') }}" class="btn btn-primary">
                                        <i class="fas fa-eye me-1"></i>
                                        عرض التقرير
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 