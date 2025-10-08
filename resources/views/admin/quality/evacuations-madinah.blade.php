@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #8e44ad 0%, #c0392b 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">
                                <i class="fas fa-truck-loading me-2"></i>
                                الإخلاءات - رخص الحفر (المدينة المنورة)
                            </h3>
                            <p class="mb-0 opacity-90">
                                <i class="fas fa-mosque me-1"></i>
                                عرض جميع رخص الحفر وحالة الإخلاء في مشروع المدينة المنورة
                            </p>
                        </div>
                        <div>
                            <a href="/admin/work-orders/productivity/madinah" class="btn btn-light">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة إلى لوحة التحكم
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-file-contract fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $licensesCount }}</h2>
                            <p class="mb-0">إجمالي الرخص</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-check-circle fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">{{ $licensesWithEvacuation }}</h2>
                            <p class="mb-0">رخص تم إخلاؤها</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #0e8c80 0%, #2ec96a 100%);">
                        <div class="card-body text-white text-center">
                            <i class="fas fa-mosque fa-3x mb-3 opacity-75"></i>
                            <h2 class="mb-1">المدينة المنورة</h2>
                            <p class="mb-0">مشروع المدينة المنورة</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evacuations Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2 text-primary"></i>
                            قائمة رخص الحفر وحالة الإخلاء
                        </h5>
                        <a href="{{ route('admin.quality.evacuations.export', ['city' => 'madinah']) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel me-1"></i>
                            تصدير Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>رقم أمر العمل</th>
                                    <th>نوع العمل</th>
                                    <th>رقم الرخصة</th>
                                    <th class="text-center">يوجد إخلاء</th>
                                    <th class="text-center">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($licenses as $index => $license)
                                @php
                                    // التحقق من بيانات الإخلاء في JSON
                                    $evacuationData = null;
                                    if (!empty($license->evacuation_data)) {
                                        $evacuationData = is_string($license->evacuation_data) ? json_decode($license->evacuation_data, true) : $license->evacuation_data;
                                    } elseif (!empty($license->additional_details)) {
                                        $additionalDetails = is_string($license->additional_details) ? json_decode($license->additional_details, true) : $license->additional_details;
                                        $evacuationData = $additionalDetails['evacuation_data'] ?? null;
                                    }
                                    
                                    $hasEvacuation = !empty($evacuationData) && is_array($evacuationData) && count($evacuationData) > 0;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $licenses->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $license->workOrder->order_number ?? 'غير محدد' }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $license->workOrder->work_type ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ $license->license_number ?? '-' }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($hasEvacuation)
                                            <span class="badge bg-success" style="font-size: 0.9rem;">
                                                <i class="fas fa-check-circle me-1"></i>
                                                نعم
                                            </span>
                                        @else
                                            <span class="badge bg-danger" style="font-size: 0.9rem;">
                                                <i class="fas fa-times-circle me-1"></i>
                                                لا
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.work-orders.show', $license->work_order_id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <p class="mb-0">لا توجد رخص مسجلة حالياً</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        @if($licenses->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $licenses->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

