@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h3 class="mb-0 fs-4">صفحة التنفيذ</h3>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.work-orders.update-execution', $workOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="execution_status" class="form-label fw-bold">حالة التنفيذ</label>
                                    <select id="execution_status" class="form-select @error('execution_status') is-invalid @enderror" name="execution_status">
                                        <option value="1" {{ old('execution_status', $workOrder->execution_status) == '1' ? 'selected' : '' }}>تم الاستلام من المقاول ولم تصدر شهادة الانجاز</option>
                                        <option value="2" {{ old('execution_status', $workOrder->execution_status) == '2' ? 'selected' : '' }}>تم تسليم المقاول ولم يتم الاستلام</option>
                                        <option value="3" {{ old('execution_status', $workOrder->execution_status) == '3' ? 'selected' : '' }}>دخلت مستخلص ولم تصرف</option>
                                        <option value="4" {{ old('execution_status', $workOrder->execution_status) == '4' ? 'selected' : '' }}>صدرت شهادة الانجاز ولم تعتمد</option>
                                        <option value="5" {{ old('execution_status', $workOrder->execution_status) == '5' ? 'selected' : '' }}>منتهي</option>
                                        <option value="6" {{ old('execution_status', $workOrder->execution_status) == '6' ? 'selected' : '' }}>مؤكد ولم تدخل مستخلص</option>
                                    </select>
                                    @error('execution_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="actual_execution_value" class="form-label fw-bold">قيمة التنفيذ الفعلي</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₪</span>
                                        <input id="actual_execution_value" type="number" step="0.01" class="form-control @error('actual_execution_value') is-invalid @enderror" name="actual_execution_value" value="{{ old('actual_execution_value', $workOrder->actual_execution_value) }}">
                                    </div>
                                    @error('actual_execution_value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="execution_file" class="form-label fw-bold">ملف التنفيذ</label>
                                    <input type="file" class="form-control @error('execution_file') is-invalid @enderror" id="execution_file" name="execution_file">
                                    @error('execution_file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                @if($workOrder->execution_file)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">الملف المرفق حالياً</label>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ Storage::url($workOrder->execution_file) }}" target="_blank" class="btn btn-sm btn-info me-2">
                                                <i class="fas fa-file-alt me-1"></i> عرض الملف
                                            </a>
                                            <form action="{{ route('admin.work-orders.delete-execution-file', $workOrder) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف الملف؟')">
                                                    <i class="fas fa-trash-alt me-1"></i> حذف الملف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="execution_notes" class="form-label fw-bold">ملاحظات التنفيذ</label>
                            <textarea id="execution_notes" class="form-control @error('execution_notes') is-invalid @enderror" name="execution_notes" rows="3">{{ old('execution_notes', $workOrder->execution_notes) }}</textarea>
                            @error('execution_notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-1"></i> عودة
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 