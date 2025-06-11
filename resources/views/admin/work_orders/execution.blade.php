@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 fs-4">صفحة التنفيذ</h3>
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $createdDate = $workOrder->created_at;
                                $daysPassed = $createdDate->diffInDays(now());
                                $isToday = $createdDate->isToday();
                                $isYesterday = $createdDate->isYesterday();
                                
                                if ($isToday) {
                                    $daysText = 'اليوم';
                                    $badgeColor = 'success';
                                } elseif ($isYesterday) {
                                    $daysText = 'أمس';
                                    $badgeColor = 'info';
                                } elseif ($daysPassed <= 7) {
                                    $daysText = $daysPassed . ' ' . ($daysPassed == 1 ? 'يوم' : 'أيام');
                                    $badgeColor = 'warning';
                                } elseif ($daysPassed <= 30) {
                                    $daysText = $daysPassed . ' يوم';
                                    $badgeColor = 'danger';
                                } else {
                                    $daysText = $daysPassed . ' يوم';
                                    $badgeColor = 'dark';
                                }
                            @endphp
                            
                            <div class="bg-white bg-opacity-20 rounded-pill px-3 py-2 duration-counter">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span class="fw-medium">مدة أمر العمل:</span>
                                    <span class="badge bg-{{ $badgeColor }} fw-bold">{{ $daysText }}</span>
                                </div>
                                <small class="text-white-50 d-block mt-1">
                                    تم الإنشاء: {{ $createdDate->format('Y-m-d H:i') }}
                                </small>
                            </div>
                            
                            <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-right"></i> عودة
                            </a>
                        </div>
                    </div>
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

/* تنسيق عداد الأيام */
.bg-opacity-20 {
    --bs-bg-opacity: 0.2;
}

.duration-counter {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.duration-counter .badge {
    font-size: 0.85rem;
    padding: 0.4rem 0.8rem;
    border-radius: 50px;
}

.text-white-50 {
    --bs-text-opacity: 0.75;
    color: rgba(var(--bs-white-rgb), var(--bs-text-opacity)) !important;
}

@media (max-width: 768px) {
    .col-md-4 {
        margin-bottom: 1rem;
    }
    
    .duration-counter {
        font-size: 0.85rem;
    }
    
    .duration-counter .badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }
    
    .card-header .d-flex {
        flex-direction: column;
        gap: 1rem !important;
    }
    
    .duration-counter {
        order: -1;
        align-self: stretch;
        text-align: center;
    }
}
</style>
@endsection 