@php($hideNavbar = true)
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0" style="font-weight:800; color:#2c3e50; letter-spacing:1px; display:flex; align-items:center;">
                <i class="fas fa-tools me-2" style="color:#007bff;"></i>
                إدارة التركيبات - {{ $workOrder->order_number }}
            </h2>
            <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-outline-secondary">&larr; عودة</a>
        </div>
    </div>
    <div class="row g-4">
        <!-- نموذج بيانات التركيبات منفصل -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">بيانات التركيبات</div>
                <div class="card-body">
                    <form id="installations-form" action="{{ route('admin.work-orders.installations.store', $workOrder) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>نوع التركيب</th>
                                        <th>الحالة</th>
                                        <th>العدد</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($installations as $key => $label)
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                <input type="radio" class="btn-check" name="installations[{{ $key }}][status]" id="{{ $key }}_yes" value="yes" {{ old('installations.' . $key . '.status') == 'yes' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-success" for="{{ $key }}_yes">نعم</label>
                                                <input type="radio" class="btn-check" name="installations[{{ $key }}][status]" id="{{ $key }}_no" value="no" {{ old('installations.' . $key . '.status') == 'no' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-danger" for="{{ $key }}_no">لا</label>
                                                <input type="radio" class="btn-check" name="installations[{{ $key }}][status]" id="{{ $key }}_na" value="na" {{ old('installations.' . $key . '.status') == 'na' ? 'checked' : '' }}>
                                                <label class="btn btn-outline-secondary" for="{{ $key }}_na">لا ينطبق</label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" step="1" min="0" class="form-control form-control-sm" name="installations[{{ $key }}][quantity]" value="{{ old('installations.' . $key . '.quantity', '0') }}" placeholder="0" data-installation="{{ $key }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-4">حفظ بيانات التركيبات</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- نموذج رفع صور التركيبات -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-images me-2"></i>
                    رفع صور التركيبات
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.work-orders.installations.images', $workOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="installations_images" class="form-label">اختر صور التركيبات</label>
                            <input type="file" class="form-control" id="installations_images" name="installations_images[]" multiple accept="image/*">
                            <div class="form-text">يمكنك اختيار عدة صور (حتى 70 صورة، كل صورة حتى 30 ميجا)</div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success px-4">حفظ الصور</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- عرض صور التركيبات المرفوعة -->
    @if($installationImages->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-images me-2"></i>
                        صور التركيبات المرفوعة
                    </div>
                    <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#viewAllImagesModal">
                        <i class="fas fa-expand-alt me-1"></i>
                        عرض جميع الصور
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($installationImages as $image)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card h-100 position-relative">
                                    <button type="button" 
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 delete-image" 
                                            data-image-id="{{ $image->id }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteImageModal"
                                            style="z-index: 1;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <a href="#" class="text-decoration-none view-image" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#viewImageModal"
                                       data-image-url="{{ Storage::url($image->file_path) }}"
                                       data-image-name="{{ $image->original_filename }}"
                                       data-image-date="{{ \Carbon\Carbon::parse($image->created_at)->format('Y-m-d H:i') }}">
                                        <img src="{{ Storage::url($image->file_path) }}" 
                                             class="card-img-top" 
                                             alt="{{ $image->original_filename }}"
                                             style="height: 200px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <p class="card-text small text-muted mb-0 text-truncate">
                                                {{ $image->original_filename }}
                                            </p>
                                            <p class="card-text small text-muted mb-0">
                                                {{ \Carbon\Carbon::parse($image->created_at)->format('Y-m-d H:i') }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal لعرض صورة واحدة -->
    <div class="modal fade" id="viewImageModal" tabindex="-1" aria-labelledby="viewImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewImageModalLabel">عرض الصورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="modalImage" class="img-fluid" alt="">
                    <div class="mt-3">
                        <p class="mb-1" id="modalImageName"></p>
                        <p class="text-muted small mb-0" id="modalImageDate"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لعرض جميع الصور -->
    <div class="modal fade" id="viewAllImagesModal" tabindex="-1" aria-labelledby="viewAllImagesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewAllImagesModalLabel">جميع صور التركيبات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        @foreach($installationImages as $image)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card h-100">
                                    <a href="#" class="text-decoration-none view-image" 
                                       data-bs-toggle="modal" 
                                       data-bs-target="#viewImageModal"
                                       data-image-url="{{ Storage::url($image->file_path) }}"
                                       data-image-name="{{ $image->original_filename }}"
                                       data-image-date="{{ \Carbon\Carbon::parse($image->created_at)->format('Y-m-d H:i') }}">
                                        <img src="{{ Storage::url($image->file_path) }}" 
                                             class="card-img-top" 
                                             alt="{{ $image->original_filename }}"
                                             style="height: 200px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <p class="card-text small text-muted mb-0 text-truncate">
                                                {{ $image->original_filename }}
                                            </p>
                                            <p class="card-text small text-muted mb-0">
                                                {{ \Carbon\Carbon::parse($image->created_at)->format('Y-m-d H:i') }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal لحذف صورة -->
    <div class="modal fade" id="deleteImageModal" tabindex="-1" aria-labelledby="deleteImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteImageModalLabel">تأكيد حذف الصورة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    هل أنت متأكد من حذف هذه الصورة؟
                </div>
                <div class="modal-footer">
                    <form id="deleteImageForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">حذف</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // عرض صورة في النافذة المنبثقة
    const viewImageButtons = document.querySelectorAll('.view-image');
    viewImageButtons.forEach(button => {
        button.addEventListener('click', function() {
            const imageUrl = this.dataset.imageUrl;
            const imageName = this.dataset.imageName;
            const imageDate = this.dataset.imageDate;
            
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('modalImageName').textContent = imageName;
            document.getElementById('modalImageDate').textContent = imageDate;
        });
    });

    // حذف صورة
    const deleteButtons = document.querySelectorAll('.delete-image');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const imageId = this.dataset.imageId;
            const form = document.getElementById('deleteImageForm');
            form.action = `/admin/work-orders/installations/images/${imageId}`;
        });
    });
});
</script>
@endpush

@endsection 