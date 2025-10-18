<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialAttachment extends Model
{
    protected $fillable = [
        'material_id',
        'work_order_id',
        'project',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'attachment_type',
        'description',
        'upload_date',
        'uploaded_by',
    ];

    protected $casts = [
        'upload_date' => 'date',
    ];

    /**
     * العلاقة مع المادة
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * العلاقة مع أمر العمل
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * العلاقة مع المستخدم الذي رفع الملف
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
