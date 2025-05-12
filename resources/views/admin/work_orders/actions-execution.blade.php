@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h3 class="mb-0 fs-4">إجراءات ما بعد التنفيذ</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info text-center">
                        <h4>مرحباً بك في صفحة إجراءات ما بعد التنفيذ لأمر العمل رقم {{ $workOrder->id }}</h4>
                        <p>يمكنك تطوير هذه الصفحة لاحقاً لإضافة أي بيانات أو نماذج مطلوبة.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 