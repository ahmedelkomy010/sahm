@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fs-4">أعمال الكهرباء</h3>
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

                    <form action="{{ route('admin.work-orders.electrical-works.images', $workOrder) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">
                            <!-- بنود الكهرباء المطلوبة -->
                            <div class="col-md-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <h4 class="card-title mb-4">
                                            <i class="fas fa-bolt ml-2 text-primary"></i>
                                            بنود الكهرباء
                                        </h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm mb-0">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 40%">البند</th>
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
                                                        <td class="align-middle">لقزات 4x70 ألمونيوم 1 kv</td>
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
                                                        <td class="align-middle">تأريض عداد</td>
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
                                                        <td class="align-middle">تأريض ميني بلر</td>
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
                                                        <td class="align-middle">تأريض  معدة</td>
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
                                                        <td class="align-middle">تأريض رنج</td>
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
                                                        <td class="align-middle">تأريض محول</td>
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

<!-- قسم رفع صور التمديد والكهرباء -->
<div class="card mt-4">
    <div class="card-header bg-info text-white">
        <i class="fas fa-images me-2"></i>
        صور أعمال التمديد والكهرباء
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            يمكنك رفع حتى 70 صورة بحجم إجمالي لا يتجاوز 30 ميجابايت
        </div>
        <div class="mb-3">
            <label for="electrical_works_images" class="form-label">اختر الصور</label>
            <input type="file" 
                   class="form-control" 
                   id="electrical_works_images" 
                   name="electrical_works_images[]" 
                   multiple 
                   accept="image/*"
                   data-max-files="70"
                   data-max-size="31457280">
            <div class="form-text">يمكنك اختيار عدة صور عن طريق الضغط على Ctrl أثناء الاختيار</div>
        </div>
        <div id="electrical-works-image-preview" class="row g-3"></div>
        <div id="electrical-works-upload-status" class="alert d-none mt-3"></div>
        <div class="text-center mt-3">
            <button type="button" id="save-electrical-works-images" class="btn btn-success px-4">حفظ الصور</button>
        </div>
    </div>
</div>

<!-- عرض الصور المرفوعة -->
@if($workOrder->electricalWorksFiles->count() > 0)
    <div class="mt-4">
        <h5 class="mb-3">الصور المرفوعة لأعمال التمديد والكهرباء</h5>
        <div class="row g-3">
            @foreach($workOrder->electricalWorksFiles as $file)
                @if(Str::startsWith($file->file_type, 'image/'))
                <div class="col-md-3">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $file->file_path) }}" 
                             class="card-img-top" 
                             alt="{{ $file->original_filename }}"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <p class="card-text small text-truncate" title="{{ $file->original_filename }}">
                                {{ Str::limit($file->original_filename, 30) }}
                            </p>
                            <p class="card-text small text-muted">
                                {{ round($file->file_size / 1024 / 1024, 2) }} MB
                            </p>
                            <div class="btn-group w-100">
                                <a href="{{ asset('storage/' . $file->file_path) }}" 
                                   class="btn btn-sm btn-info" 
                                   target="_blank">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                                <form action="{{ route('admin.work-orders.electrical-works.delete-file', [$workOrder, $file]) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // رفع صور التمديد والكهرباء
    const imageInput = document.getElementById('electrical_works_images');
    const saveBtn = document.getElementById('save-electrical-works-images');
    if (imageInput && saveBtn) {
        const imagePreview = document.getElementById('electrical-works-image-preview');
        const uploadStatus = document.getElementById('electrical-works-upload-status');
        const maxFiles = parseInt(imageInput.dataset.maxFiles);
        const maxSize = parseInt(imageInput.dataset.maxSize);
        let totalSize = 0;

        imageInput.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            totalSize = files.reduce((sum, file) => sum + file.size, 0);

            // التحقق من عدد الملفات
            if (files.length > maxFiles) {
                showError(`يمكنك رفع ${maxFiles} صور كحد أقصى`);
                imageInput.value = '';
                return;
            }
            // التحقق من الحجم الإجمالي
            if (totalSize > maxSize) {
                showError(`الحجم الإجمالي للصور يجب أن لا يتجاوز 30 ميجابايت`);
                imageInput.value = '';
                return;
            }
            // عرض معاينة الصور
            imagePreview.innerHTML = '';
            files.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-sm-4 col-6';
                    col.innerHTML = `
                        <div class=\"card h-100\">
                            <img src=\"${e.target.result}\" class=\"card-img-top\" alt=\"معاينة الصورة ${index + 1}\">
                            <div class=\"card-body p-2\">
                                <p class=\"card-text small text-muted mb-0\">${formatFileSize(file.size)}</p>
                            </div>
                        </div>
                    `;
                    imagePreview.appendChild(col);
                };
                reader.readAsDataURL(file);
            });
            uploadStatus.classList.add('d-none');
        });
        saveBtn.addEventListener('click', function(e) {
            const files = Array.from(imageInput.files);
            if (files.length === 0) {
                showError('يرجى اختيار صور أولاً');
                return;
            }
            const formData = new FormData();
            files.forEach(file => {
                formData.append('electrical_works_images[]', file);
            });
            fetch("{{ route('admin.work-orders.electrical-works.images', $workOrder) }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content,
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    throw new Error('الاستجابة من السيرفر غير صالحة (ليست JSON). قد يكون هناك خطأ في السيرفر أو في الاتصال.');
                }
                if (!response.ok) {
                    throw new Error((data && data.message) ? data.message : 'حدث خطأ أثناء رفع الصور');
                }
                if (data.success) {
                    uploadStatus.className = 'alert alert-success mt-3';
                    uploadStatus.textContent = 'تم رفع الصور بنجاح';
                    uploadStatus.classList.remove('d-none');
                    // إضافة الصور الجديدة مباشرة إلى قسم الصور المرفوعة (Grid)
                    const gallery = document.querySelector('.mt-4 .row.g-3');
                    if (gallery && data.images && data.images.length > 0) {
                        data.images.forEach(img => {
                            const col = document.createElement('div');
                            col.className = 'col-md-3';
                            col.innerHTML = `
                                <div class=\"card h-100\">
                                    <img src=\"${img.url}\" class=\"card-img-top\" style=\"height: 200px; object-fit: cover;\" alt=\"${img.name}\">
                                    <div class=\"card-body\">
                                        <p class=\"card-text small text-truncate\" title=\"${img.name}\">${img.name.length > 30 ? img.name.substring(0, 27) + '...' : img.name}</p>
                                        <p class=\"card-text small text-muted\">${img.size} MB</p>
                                        <div class=\"btn-group w-100\">
                                            <a href=\"${img.url}\" class=\"btn btn-sm btn-info\" target=\"_blank\"><i class=\"fas fa-eye\"></i> عرض</a>
                                            <button type=\"button\" class=\"btn btn-sm btn-danger\" disabled><i class=\"fas fa-trash\"></i> حذف</button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            gallery.prepend(col);
                        });
                    }
                    // إعادة تعيين حقل الصور والمعاينة
                    imageInput.value = '';
                    imagePreview.innerHTML = '';
                } else {
                    throw new Error(data.message || 'حدث خطأ أثناء رفع الصور');
                }
            })
            .catch(error => {
                uploadStatus.className = 'alert alert-danger mt-3';
                uploadStatus.textContent = error.message;
                uploadStatus.classList.remove('d-none');
            });
        });
        function showError(message) {
            uploadStatus.className = 'alert alert-danger mt-3';
            uploadStatus.textContent = message;
            uploadStatus.classList.remove('d-none');
        }
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    }
});
</script>
@endpush
@endsection 