@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fs-4">أعمال التمديد والكهرباء</h3>
                    <a href="{{ route('admin.work-orders.execution', $workOrder) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right"></i> عودة
                    </a>
                </div>

                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.work-orders.electrical-works.store', $workOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- عناصر التمديد والكهرباء المطلوبة -->
                            <div class="col-md-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <h4 class="card-title mb-4">
                                            <i class="fas fa-bolt ml-2 text-primary"></i>
                                            عناصر التمديد والكهرباء
                                        </h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm mb-0">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 40%">العنصر</th>
                                                        <th style="width: 30%">الحالة</th>
                                                        <th style="width: 30%">العدد</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="align-middle">وصلة 3 × 500 ألمونيوم 13.8kv</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[al_500_joint][status]" id="al_500_joint_yes" value="yes" {{ old('electrical_items.al_500_joint.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="al_500_joint_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[al_500_joint][status]" id="al_500_joint_no" value="no" {{ old('electrical_items.al_500_joint.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="al_500_joint_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[al_500_joint][status]" id="al_500_joint_na" value="na" {{ old('electrical_items.al_500_joint.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="al_500_joint_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[al_500_joint][quantity]" value="{{ old('electrical_items.al_500_joint.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">نهاية  3 500 ألمونيوم 13.8kv</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[al_500_end][status]" id="al_500_end_yes" value="yes" {{ old('electrical_items.al_500_end.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="al_500_end_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[al_500_end][status]" id="al_500_end_no" value="no" {{ old('electrical_items.al_500_end.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="al_500_end_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[al_500_end][status]" id="al_500_end_na" value="na" {{ old('electrical_items.al_500_end.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="al_500_end_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[al_500_end][quantity]" value="{{ old('electrical_items.al_500_end.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">نهاية 4 * 70 ألمونيوم 1kv</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[end_4x70_1kv][status]" id="end_4x70_1kv_yes" value="yes" {{ old('electrical_items.end_4x70_1kv.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="end_4x70_1kv_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[end_4x70_1kv][status]" id="end_4x70_1kv_no" value="no" {{ old('electrical_items.end_4x70_1kv.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="end_4x70_1kv_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[end_4x70_1kv][status]" id="end_4x70_1kv_na" value="na" {{ old('electrical_items.end_4x70_1kv.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="end_4x70_1kv_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[end_4x70_1kv][quantity]" value="{{ old('electrical_items.end_4x70_1kv.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">نهاية 4 * 185 ألمونيوم 1kv</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[end_4x185_1kv][status]" id="end_4x185_1kv_yes" value="yes" {{ old('electrical_items.end_4x185_1kv.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="end_4x185_1kv_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[end_4x185_1kv][status]" id="end_4x185_1kv_no" value="no" {{ old('electrical_items.end_4x185_1kv.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="end_4x185_1kv_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[end_4x185_1kv][status]" id="end_4x185_1kv_na" value="na" {{ old('electrical_items.end_4x185_1kv.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="end_4x185_1kv_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[end_4x185_1kv][quantity]" value="{{ old('electrical_items.end_4x185_1kv.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">نهاية 4 * 300 ألمونيوم 1kv</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[end_4x300_1kv][status]" id="end_4x300_1kv_yes" value="yes" {{ old('electrical_items.end_4x300_1kv.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="end_4x300_1kv_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[end_4x300_1kv][status]" id="end_4x300_1kv_no" value="no" {{ old('electrical_items.end_4x300_1kv.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="end_4x300_1kv_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[end_4x300_1kv][status]" id="end_4x300_1kv_na" value="na" {{ old('electrical_items.end_4x300_1kv.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="end_4x300_1kv_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[end_4x300_1kv][quantity]" value="{{ old('electrical_items.end_4x300_1kv.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">وصلة 33kv</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[joint_33kv][status]" id="joint_33kv_yes" value="yes" {{ old('electrical_items.joint_33kv.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="joint_33kv_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[joint_33kv][status]" id="joint_33kv_no" value="no" {{ old('electrical_items.joint_33kv.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="joint_33kv_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[joint_33kv][status]" id="joint_33kv_na" value="na" {{ old('electrical_items.joint_33kv.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="joint_33kv_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[joint_33kv][quantity]" value="{{ old('electrical_items.joint_33kv.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">نهاية 33kv</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[end_33kv][status]" id="end_33kv_yes" value="yes" {{ old('electrical_items.end_33kv.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="end_33kv_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[end_33kv][status]" id="end_33kv_no" value="no" {{ old('electrical_items.end_33kv.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="end_33kv_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[end_33kv][status]" id="end_33kv_na" value="na" {{ old('electrical_items.end_33kv.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="end_33kv_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[end_33kv][quantity]" value="{{ old('electrical_items.end_33kv.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">وصلة باي ميتالك 13,8 kv</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[bi_metallic_joint_13_8kv][status]" id="bi_metallic_joint_13_8kv_yes" value="yes" {{ old('electrical_items.bi_metallic_joint_13_8kv.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="bi_metallic_joint_13_8kv_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[bi_metallic_joint_13_8kv][status]" id="bi_metallic_joint_13_8kv_no" value="no" {{ old('electrical_items.bi_metallic_joint_13_8kv.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="bi_metallic_joint_13_8kv_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[bi_metallic_joint_13_8kv][status]" id="bi_metallic_joint_13_8kv_na" value="na" {{ old('electrical_items.bi_metallic_joint_13_8kv.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="bi_metallic_joint_13_8kv_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[bi_metallic_joint_13_8kv][quantity]" value="{{ old('electrical_items.bi_metallic_joint_13_8kv.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">وصلة باي ميتالك 1 kv</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[bi_metallic_joint_1kv][status]" id="bi_metallic_joint_1kv_yes" value="yes" {{ old('electrical_items.bi_metallic_joint_1kv.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="bi_metallic_joint_1kv_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[bi_metallic_joint_1kv][status]" id="bi_metallic_joint_1kv_no" value="no" {{ old('electrical_items.bi_metallic_joint_1kv.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="bi_metallic_joint_1kv_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[bi_metallic_joint_1kv][status]" id="bi_metallic_joint_1kv_na" value="na" {{ old('electrical_items.bi_metallic_joint_1kv.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="bi_metallic_joint_1kv_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[bi_metallic_joint_1kv][quantity]" value="{{ old('electrical_items.bi_metallic_joint_1kv.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">لفزات 4x70 ألمونيوم 1 kv</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[lugs_4x70_1kv][status]" id="lugs_4x70_1kv_yes" value="yes" {{ old('electrical_items.lugs_4x70_1kv.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="lugs_4x70_1kv_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[lugs_4x70_1kv][status]" id="lugs_4x70_1kv_no" value="no" {{ old('electrical_items.lugs_4x70_1kv.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="lugs_4x70_1kv_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[lugs_4x70_1kv][status]" id="lugs_4x70_1kv_na" value="na" {{ old('electrical_items.lugs_4x70_1kv.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="lugs_4x70_1kv_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[lugs_4x70_1kv][quantity]" value="{{ old('electrical_items.lugs_4x70_1kv.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">نهاية 1x50 نحاس 13.8 kv</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[end_1x50_cu_13_8kv][status]" id="end_1x50_cu_13_8kv_yes" value="yes" {{ old('electrical_items.end_1x50_cu_13_8kv.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="end_1x50_cu_13_8kv_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[end_1x50_cu_13_8kv][status]" id="end_1x50_cu_13_8kv_no" value="no" {{ old('electrical_items.end_1x50_cu_13_8kv.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="end_1x50_cu_13_8kv_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[end_1x50_cu_13_8kv][status]" id="end_1x50_cu_13_8kv_na" value="na" {{ old('electrical_items.end_1x50_cu_13_8kv.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="end_1x50_cu_13_8kv_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[end_1x50_cu_13_8kv][quantity]" value="{{ old('electrical_items.end_1x50_cu_13_8kv.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">نهاية 3x70 ألمونيوم 13,8 kv</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[end_3x70_al_13_8kv][status]" id="end_3x70_al_13_8kv_yes" value="yes" {{ old('electrical_items.end_3x70_al_13_8kv.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="end_3x70_al_13_8kv_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[end_3x70_al_13_8kv][status]" id="end_3x70_al_13_8kv_no" value="no" {{ old('electrical_items.end_3x70_al_13_8kv.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="end_3x70_al_13_8kv_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[end_3x70_al_13_8kv][status]" id="end_3x70_al_13_8kv_na" value="na" {{ old('electrical_items.end_3x70_al_13_8kv.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="end_3x70_al_13_8kv_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[end_3x70_al_13_8kv][quantity]" value="{{ old('electrical_items.end_3x70_al_13_8kv.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">تأريفي عداد</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[tariff_meter][status]" id="tariff_meter_yes" value="yes" {{ old('electrical_items.tariff_meter.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="tariff_meter_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[tariff_meter][status]" id="tariff_meter_no" value="no" {{ old('electrical_items.tariff_meter.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="tariff_meter_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[tariff_meter][status]" id="tariff_meter_na" value="na" {{ old('electrical_items.tariff_meter.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="tariff_meter_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[tariff_meter][quantity]" value="{{ old('electrical_items.tariff_meter.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">تأريفي ميني بلر</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[tariff_minipillar][status]" id="tariff_minipillar_yes" value="yes" {{ old('electrical_items.tariff_minipillar.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="tariff_minipillar_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[tariff_minipillar][status]" id="tariff_minipillar_no" value="no" {{ old('electrical_items.tariff_minipillar.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="tariff_minipillar_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[tariff_minipillar][status]" id="tariff_minipillar_na" value="na" {{ old('electrical_items.tariff_minipillar.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="tariff_minipillar_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[tariff_minipillar][quantity]" value="{{ old('electrical_items.tariff_minipillar.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">تأريفي رنج</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[tariff_ring][status]" id="tariff_ring_yes" value="yes" {{ old('electrical_items.tariff_ring.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="tariff_ring_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[tariff_ring][status]" id="tariff_ring_no" value="no" {{ old('electrical_items.tariff_ring.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="tariff_ring_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[tariff_ring][status]" id="tariff_ring_na" value="na" {{ old('electrical_items.tariff_ring.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="tariff_ring_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[tariff_ring][quantity]" value="{{ old('electrical_items.tariff_ring.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">تأريفي محول</td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm w-100" role="group">
                                                                <input type="radio" class="btn-check" name="electrical_items[tariff_transformer][status]" id="tariff_transformer_yes" value="yes" {{ old('electrical_items.tariff_transformer.status') == 'yes' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-success" for="tariff_transformer_yes">نعم</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[tariff_transformer][status]" id="tariff_transformer_no" value="no" {{ old('electrical_items.tariff_transformer.status') == 'no' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-danger" for="tariff_transformer_no">لا</label>
                                                                <input type="radio" class="btn-check" name="electrical_items[tariff_transformer][status]" id="tariff_transformer_na" value="na" {{ old('electrical_items.tariff_transformer.status') == 'na' ? 'checked' : '' }}>
                                                                <label class="btn btn-outline-secondary" for="tariff_transformer_na">لا ينطبق</label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="1" min="0" class="form-control" name="electrical_items[tariff_transformer][quantity]" value="{{ old('electrical_items.tariff_transformer.quantity', '0') }}" placeholder="0">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">عدد</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">شد اسلاك 4x120 المونيوم منخفض</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[tension_4x120_al_low][meters]" value="{{ old('electrical_items.tension_4x120_al_low.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">شد اسلاك 1x70 المونيوم 33 kv</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[tension_1x70_al_33kv][meters]" value="{{ old('electrical_items.tension_1x70_al_33kv.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">شد اسلاك 3x70 المونيوم 33 kv</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[tension_3x70_al_33kv][meters]" value="{{ old('electrical_items.tension_3x70_al_33kv.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">شد اسلاك 6x170 المونيوم 33kv</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[tension_6x170_al_33kv][meters]" value="{{ old('electrical_items.tension_6x170_al_33kv.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">تمديد كيبل 4x70 منخفض</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[cable_4x70_low][meters]" value="{{ old('electrical_items.cable_4x70_low.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">تمديد كيبل 4x185 منخفض</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[cable_4x185_low][meters]" value="{{ old('electrical_items.cable_4x185_low.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">تمديد كيبل 4x300 منخفض</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[cable_4x300_low][meters]" value="{{ old('electrical_items.cable_4x300_low.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">تمديد كيبل 3x500 متوسط</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[cable_3x500_med][meters]" value="{{ old('electrical_items.cable_3x500_med.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="align-middle">تمديد كيبل 3x400 متوسط</td>
                                                        <td colspan="2">
                                                            <div class="input-group input-group-sm">
                                                                <input type="number" step="0.01" min="0" class="form-control" name="electrical_items[cable_3x400_med][meters]" value="{{ old('electrical_items.cable_3x400_med.meters', '0') }}" placeholder="0.00">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">متر</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save ml-1"></i> حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.card-title {
    color: var(--text-color);
    font-weight: 600;
    font-size: 1.1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border-color);
}

.card-title i {
    color: var(--primary-color);
}

.btn-group {
    gap: 0.5rem;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
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
    .card-body {
        padding: 1rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection 