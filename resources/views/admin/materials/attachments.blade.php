@extends('layouts.admin')

@section('title', 'مرفقات المواد')

@push('head')
<style>
    /* إزالة الحاجب الأسود من الـ modal */
    .modal-backdrop {
        display: none !important;
    }
    
    .modal {
        background: transparent !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
     <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
         <div class="d-flex align-items-center gap-3">
             <a href="javascript:history.back()" class="btn btn-secondary">
                 <i class="fas fa-arrow-right me-1"></i>
                 عودة
             </a>
              <h2 class="mb-0" style="font-size:2.1rem; font-weight:800; color:#2c3e50;">
                  <i class="fas fa-paperclip me-3" style="color:#ffc107;"></i>
                  مرفقات المواد - {{ $project === 'riyadh' ? 'الرياض' : 'المدينة المنورة' }}
              </h2>
          </div>
        
    </div>

    {{-- رسائل النجاح والخطأ --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- زر رفع ملف جديد -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="fas fa-upload me-2"></i>
                رفع ملف جديد
            </button>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-filter me-2"></i>
            <strong>البحث والفلترة</strong>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.materials.attachments.index') }}">
                <input type="hidden" name="project" value="{{ $project }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">تاريخ الرفع</label>
                        <input type="date" name="upload_date" class="form-control" value="{{ request('upload_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">نوع المرفق</label>
                        <select name="attachment_type" class="form-select">
                            <option value="">الكل</option>
                            <option value="CHECK LIST" {{ request('attachment_type') === 'CHECK LIST' ? 'selected' : '' }}>CHECK LIST</option>
                            <option value="GATE PASS" {{ request('attachment_type') === 'GATE PASS' ? 'selected' : '' }}>GATE PASS</option>
                            <option value="STORE OUT" {{ request('attachment_type') === 'STORE OUT' ? 'selected' : '' }}>STORE OUT</option>
                            <option value="DDO" {{ request('attachment_type') === 'DDO' ? 'selected' : '' }}>DDO</option>
                            <option value="STORE IN" {{ request('attachment_type') === 'STORE IN' ? 'selected' : '' }}>STORE IN</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">رقم أمر العمل</label>
                        <input type="text" name="work_order" class="form-control" placeholder="رقم أمر العمل..." value="{{ request('work_order') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">بحث</label>
                        <input type="text" name="search" class="form-control" placeholder="اسم الملف..." value="{{ request('search') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>
                            بحث
                        </button>
                        <a href="{{ route('admin.materials.attachments.index', ['project' => $project]) }}" class="btn btn-secondary">
                            <i class="fas fa-redo me-1"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول المرفقات -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            <i class="fas fa-list me-2"></i>
            <strong>المرفقات ({{ $attachments->total() }})</strong>
        </div>
        <div class="card-body p-0">
            @if($attachments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-bordered mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th style="width: 60px;">#</th>
                            <th>اسم الملف</th>
                            <th>نوع المرفق</th>
                            <th>رقم أمر العمل</th>
                            <th>تاريخ الرفع</th>
                            <th>رفع بواسطة</th>
                            <th>الحجم</th>
                            <th style="width: 150px;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attachments as $index => $attachment)
                        <tr>
                            <td class="text-center">{{ $attachments->firstItem() + $index }}</td>
                            <td>
                                <i class="fas fa-file me-1 text-primary"></i>
                                {{ $attachment->file_name }}
                            </td>
                            <td class="text-center">
                                @if($attachment->attachment_type)
                                    <span class="badge bg-secondary">{{ $attachment->attachment_type }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($attachment->workOrder)
                                    <a href="{{ route('admin.work-orders.show', $attachment->workOrder) }}" class="text-primary fw-bold" target="_blank">
                                        {{ $attachment->workOrder->order_number }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $attachment->upload_date->format('Y-m-d') }}</td>
                            <td class="text-center">
                                @if($attachment->uploadedBy)
                                    {{ $attachment->uploadedBy->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">{{ number_format($attachment->file_size / 1024 / 1024, 2) }} MB</td>
                            <td class="text-center">
                                <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-sm btn-primary" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ Storage::url($attachment->file_path) }}" download="{{ $attachment->file_name }}" class="btn btn-sm btn-success" title="تحميل">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger delete-attachment" data-id="{{ $attachment->id }}" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="p-3">
                {{ $attachments->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                <p class="text-muted fs-5">لا توجد مرفقات</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal رفع ملف -->
<div class="modal fade" id="uploadModal" tabindex="-1" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-upload me-2"></i>
                    رفع ملف جديد
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="project" value="{{ $project }}">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">اختر الملف <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" required>
                            <small class="text-muted">الحد الأقصى: 4.2 ميجابايت</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">تاريخ الرفع <span class="text-danger">*</span></label>
                            <input type="date" name="upload_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">نوع المرفق</label>
                            <select name="attachment_type" class="form-select">
                                <option value="">اختر النوع</option>
                                <option value="CHECK LIST">CHECK LIST</option>
                                <option value="GATE PASS">GATE PASS</option>
                                <option value="STORE OUT">STORE OUT</option>
                                <option value="DDO">DDO</option>
                                <option value="STORE IN">STORE IN</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">رقم أمر العمل</label>
                            <input type="text" name="work_order_number" id="work_order_number" class="form-control" placeholder="ابحث عن رقم أمر العمل...">
                            <input type="hidden" name="work_order_id" id="work_order_id">
                            <div id="work_order_suggestions" class="list-group mt-1" style="position: absolute; z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload me-1"></i>
                        رفع الملف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // إزالة الحاجب الأسود عند فتح الـ modal
    const uploadModal = document.getElementById('uploadModal');
    if (uploadModal) {
        uploadModal.addEventListener('show.bs.modal', function() {
            // إزالة أي backdrop موجود
            setTimeout(() => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
            }, 10);
        });
        
        uploadModal.addEventListener('shown.bs.modal', function() {
            // إزالة أي backdrop موجود بعد فتح الـ modal
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
        });
    }
    
    // البحث في أوامر العمل
    const workOrderInput = document.getElementById('work_order_number');
    const workOrderIdInput = document.getElementById('work_order_id');
    const suggestionsDiv = document.getElementById('work_order_suggestions');
    
    if (workOrderInput) {
        workOrderInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            
            if (searchTerm.length < 2) {
                suggestionsDiv.style.display = 'none';
                return;
            }
            
            fetch(`{{ route('admin.work-orders.search', '') }}/${searchTerm}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        suggestionsDiv.innerHTML = '';
                        data.forEach(workOrder => {
                            const item = document.createElement('button');
                            item.type = 'button';
                            item.className = 'list-group-item list-group-item-action';
                            item.textContent = workOrder.order_number + ' - ' + (workOrder.project_name || '');
                            item.onclick = function() {
                                workOrderInput.value = workOrder.order_number;
                                workOrderIdInput.value = workOrder.id;
                                suggestionsDiv.style.display = 'none';
                            };
                            suggestionsDiv.appendChild(item);
                        });
                        suggestionsDiv.style.display = 'block';
                    } else {
                        suggestionsDiv.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
        
        // إخفاء الاقتراحات عند النقر خارجها
        document.addEventListener('click', function(e) {
            if (!workOrderInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.style.display = 'none';
            }
        });
    }
    
    // رفع ملف
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> جاري الرفع...';
        
        fetch('{{ route("admin.materials.attachments.upload") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                location.reload();
            } else {
                alert('❌ ' + data.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ حدث خطأ أثناء رفع الملف');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
    
    // حذف مرفق
    document.querySelectorAll('.delete-attachment').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('هل أنت متأكد من حذف هذا الملف؟')) return;
            
            const attachmentId = this.dataset.id;
            
            fetch(`{{ url('admin/materials/attachments') }}/${attachmentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ' + data.message);
                    location.reload();
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('❌ حدث خطأ أثناء حذف الملف');
            });
        });
    });
});
</script>
@endsection

