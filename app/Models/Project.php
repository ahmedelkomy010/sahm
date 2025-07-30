<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة
     */
    protected $fillable = [
        'name',
        'contract_number',
        'project_type',
        'location',
        'description',
        'status',
    ];

    /**
     * القيم الافتراضية للحقول
     */
    protected $attributes = [
        'status' => 'active',
    ];

    /**
     * التحقق من صحة البيانات
     */
    public static $rules = [
        'name' => 'required|string|max:255',
        'contract_number' => 'required|string|max:255|unique:projects',
        'project_type' => 'required|in:civil,electrical,mixed',
        'location' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:active,completed,on_hold',
    ];

    /**
     * العلاقة مع المرفقات
     */
    public function attachments()
    {
        return $this->hasMany(ProjectAttachment::class);
    }

    /**
     * العلاقة مع أوامر العمل
     */
    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    /**
     * الحصول على نص حالة المشروع
     */
    public function getStatusTextAttribute()
    {
        return [
            'active' => 'نشط',
            'completed' => 'مكتمل',
            'on_hold' => 'متوقف',
        ][$this->status] ?? $this->status;
    }

    /**
     * الحصول على نص نوع المشروع
     */
    public function getProjectTypeTextAttribute()
    {
        return [
            'civil' => 'أعمال مدنية',
            'electrical' => 'أعمال كهربائية',
            'mixed' => 'أعمال مختلطة',
        ][$this->project_type] ?? $this->project_type;
    }

    /**
     * الحصول على لون حالة المشروع
     */
    public function getStatusColorAttribute()
    {
        return [
            'active' => 'green',
            'completed' => 'blue',
            'on_hold' => 'yellow',
        ][$this->status] ?? 'gray';
    }
} 