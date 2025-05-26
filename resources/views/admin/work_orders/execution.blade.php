@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fs-4">صفحة التنفيذ</h3>
                    <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right"></i> عودة
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- قسم الأعمال المدنية -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-hard-hat fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">الأعمال المدنية</h4>
                                    <p class="card-text text-muted mb-4">إدارة وتوثيق الأعمال المدنية للمشروع</p>
                                    <a href="{{ route('admin.work-orders.civil-works', $workOrder) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-arrow-left ml-1"></i>
                                        الانتقال إلى الأعمال المدنية
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- قسم التركيبات -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-tools fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">التركيبات</h4>
                                    <p class="card-text text-muted mb-4">إدارة وتوثيق أعمال التركيبات للمشروع</p>
                                    <a href="{{ route('admin.work-orders.installations', $workOrder) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-arrow-left ml-1"></i>
                                        الانتقال إلى التركيبات
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- قسم أعمال التمديد والكهرباء -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-bolt fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">أعمال الكهرباء</h4>
                                    <p class="card-text text-muted mb-4">إدارة وتوثيق أعمال الكهرباء للمشروع</p>
                                    <a href="{{ route('admin.work-orders.electrical-works', $workOrder) }}" class="btn btn-primary w-100">
                                        <i class="fas fa-arrow-left ml-1"></i>
                                        الانتقال إلى أعمال الكهرباء
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

.icon-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(74, 144, 226, 0.1);
    border-radius: 50%;
}

.card-title {
    color: var(--text-color);
    font-weight: 600;
    font-size: 1.25rem;
}

.btn-primary {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(74, 144, 226, 0.2);
}

@media (max-width: 768px) {
    .col-md-4 {
        margin-bottom: 1rem;
    }
}
</style>
@endsection 