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
                    <div class="row justify-content-center">
                        <!-- تقارير العقد الموحد -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-lg border-0">
                                <div class="card-body text-center p-5">
                                    <div class="mb-4">
                                        <i class="fas fa-file-contract text-primary" style="font-size: 5rem;"></i>
                                    </div>
                                    <h3 class="card-title mb-3">
                                        <i class="fas fa-chart-bar me-2"></i>
                                        تقارير العقد الموحد
                                    </h3>
                                    <p class="card-text text-muted mb-4" style="font-size: 1.1rem;">
                                        عرض تقرير شامل ومفصل لإنتاجية وأداء أوامر العمل للعقد الموحد
                                    </p>
                                    <a href="{{ route('admin.work-orders.productivity.riyadh') }}" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-eye me-2"></i>
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