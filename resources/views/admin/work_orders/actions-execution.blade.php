@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 fs-4">إجراءات ما بعد التنفيذ</h3>
                    <a href="{{ route('admin.work-orders.show', $workOrder) }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-right"></i> عودة
                    </a>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- الصف الأول للمرفقات -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-file-alt fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">بيان كميات التنفيذ</h4>
                                    <p class="card-text text-muted mb-4">تحميل بيان كميات التنفيذ الخاص بأمر العمل</p>
                                    <div class="d-grid">
                                        <a href="#" class="btn btn-primary">تحميل الملف</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-boxes fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">كميات المواد النهائية</h4>
                                    <p class="card-text text-muted mb-4">تحميل قائمة كميات المواد النهائية المستخدمة</p>
                                    <div class="d-grid">
                                        <a href="#" class="btn btn-primary">تحميل الملف</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-ruler fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">ورقة القياس النهائية</h4>
                                    <p class="card-text text-muted mb-4">تحميل ورقة القياس النهائية للمشروع</p>
                                    <div class="d-grid">
                                        <a href="#" class="btn btn-primary">تحميل الملف</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- الصف الثاني للمرفقات -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-vial fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">اختبارات التربة</h4>
                                    <p class="card-text text-muted mb-4">تحميل نتائج اختبارات التربة للموقع</p>
                                    <div class="d-grid">
                                        <a href="#" class="btn btn-primary">تحميل الملف</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-drafting-compass fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">الرسم الهندسي للموقع</h4>
                                    <p class="card-text text-muted mb-4">تحميل الرسومات الهندسية النهائية للموقع</p>
                                    <div class="d-grid">
                                        <a href="#" class="btn btn-primary">تحميل الملف</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-file-invoice fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">تعديل المقايسة</h4>
                                    <p class="card-text text-muted mb-4">تحميل المقايسة المعدلة بعد التنفيذ</p>
                                    <div class="d-grid">
                                        <a href="#" class="btn btn-primary">تحميل الملف</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- الصف الثالث للمرفقات -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-certificate fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">شهادة الانجاز</h4>
                                    <p class="card-text text-muted mb-4">تحميل شهادة إنجاز المشروع</p>
                                    <div class="d-grid">
                                        <a href="#" class="btn btn-primary">تحميل الملف</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-file-contract fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">نموذج 200</h4>
                                    <p class="card-text text-muted mb-4">تحميل نموذج 200 الخاص بالمشروع</p>
                                    <div class="d-grid">
                                        <a href="#" class="btn btn-primary">تحميل الملف</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-file-alt fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">نموذج 190</h4>
                                    <p class="card-text text-muted mb-4">تحميل نموذج 190 الخاص بالمشروع</p>
                                    <div class="d-grid">
                                        <a href="#" class="btn btn-primary">تحميل الملف</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- الصف الرابع للمرفقات -->
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-clipboard-check fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">اختبارات ما قبل التشغيل 211</h4>
                                    <p class="card-text text-muted mb-4">تحميل نتائج اختبارات ما قبل التشغيل</p>
                                    <div class="d-grid">
                                        <a href="#" class="btn btn-primary">تحميل الملف</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm hover-card">
                                <div class="card-body text-center p-4">
                                    <div class="icon-wrapper mb-3">
                                        <i class="fas fa-file-invoice-dollar fa-3x text-primary"></i>
                                    </div>
                                    <h4 class="card-title mb-3">رقم المستخلص</h4>
                                    <p class="card-text text-muted mb-4">تحميل نسخة من المستخلص النهائي</p>
                                    <div class="d-grid">
                                        <a href="#" class="btn btn-primary">تحميل الملف</a>
                                    </div>
                                </div>
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
    padding: 0.6rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(74, 144, 226, 0.2);
}
</style>
@endsection 