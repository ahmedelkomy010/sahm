@php($hideNavbar = true)
@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                    <h4 class="mb-0">التركيبات - {{ $workOrder->order_number }}</h4>
                    <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right ml-1"></i>
                        عودة
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.work-orders.installations.store', $workOrder) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                <div class="section">
                                    <h5 class="section-title mb-3">التركيبات</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th style="width: 40%">نوع التركيب</th>
                                                    <th style="width: 30%">الحالة</th>
                                                    <th style="width: 30%">العدد</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($installations as $key => $label)
                                                <tr>
                                                    <td class="align-middle">{{ $label }}</td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm w-100" role="group">
                                                            <input type="radio" class="btn-check" name="installations[{{ $key }}][status]" 
                                                                   id="{{ $key }}_yes" value="yes" 
                                                                   {{ old('installations.' . $key . '.status') == 'yes' ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-success" for="{{ $key }}_yes">نعم</label>
                                                            <input type="radio" class="btn-check" name="installations[{{ $key }}][status]" 
                                                                   id="{{ $key }}_no" value="no"
                                                                   {{ old('installations.' . $key . '.status') == 'no' ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-danger" for="{{ $key }}_no">لا</label>
                                                            <input type="radio" class="btn-check" name="installations[{{ $key }}][status]" 
                                                                   id="{{ $key }}_na" value="na"
                                                                   {{ old('installations.' . $key . '.status') == 'na' ? 'checked' : '' }}>
                                                            <label class="btn btn-outline-secondary" for="{{ $key }}_na">لا ينطبق</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group input-group-sm">
                                                            <input type="number" step="1" min="0" class="form-control quantity-input" 
                                                                   name="installations[{{ $key }}][quantity]" 
                                                                   value="{{ old('installations.' . $key . '.quantity', '0') }}"
                                                                   placeholder="0"
                                                                   data-installation="{{ $key }}">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">قطعة</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center mt-3">
                            <button type="submit" class="btn btn-primary px-4">حفظ البيانات</button>
                        </div>
                    </form>
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
}
.section-title {
    color: #2c3e50;
    border-bottom: 2px solid #3498db;
    padding-bottom: 8px;
    margin-bottom: 15px;
    font-size: 1.1rem;
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
.card {
    margin-bottom: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.card-header {
    background-color: #fff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    padding: 0.75rem 1.25rem;
}
.card-body {
    padding: 1.25rem;
}
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    .section {
        padding: 10px;
    }
}
</style>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // التحكم في حقل الكمية بناءً على حالة التركيب
    document.querySelectorAll('.btn-check').forEach(radio => {
        radio.addEventListener('change', function() {
            const installationKey = this.id.split('_')[0];
            const quantityInput = document.querySelector(`input[data-installation="${installationKey}"]`);
            if (this.value === 'na') {
                quantityInput.value = '0';
                quantityInput.disabled = true;
            } else {
                quantityInput.disabled = false;
            }
        });
    });
    // تعطيل حقول الكمية للتركيبات التي حالتها "لا ينطبق" عند تحميل الصفحة
    document.querySelectorAll('.btn-check[value="na"]:checked').forEach(radio => {
        const installationKey = radio.id.split('_')[0];
        const quantityInput = document.querySelector(`input[data-installation="${installationKey}"]`);
        if (quantityInput) {
            quantityInput.disabled = true;
        }
    });
});
</script>
@endsection 