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
        'amount',
        'description',
        'status',
        'srgn_date',
        'kick_off_date',
        'tcc_date',
        'pac_date',
        'fat_date',
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
        'project_type' => 'required|in:OH33KV,UA33LW,SLS33KV,UG132KV,special',
        'location' => 'required|string|max:255',
        'amount' => 'nullable|numeric|min:0',
        'description' => 'nullable|string',
        'status' => 'required|in:active,completed,on_hold',
        'srgn_date' => 'nullable|date',
        'kick_off_date' => 'nullable|date',
        'tcc_date' => 'nullable|date',
        'pac_date' => 'nullable|date',
        'fat_date' => 'nullable|date',
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
            'OH33KV' => 'OH 33KV',
            'UA33LW' => 'UA 33LW',
            'SLS33KV' => 'SLS 33KV',
            'UG132KV' => 'UG 132 KV',
            'special' => 'مشروع خاص',
        ][$this->project_type] ?? $this->project_type;
    }

    /**
     * الحصول على نص نوع المشروع (method)
     */
    public function getProjectTypeText()
    {
        return [
            'OH33KV' => 'OH 33KV',
            'UA33LW' => 'UA 33LW',
            'SLS33KV' => 'SLS 33KV',
            'UG132KV' => 'UG 132 KV',
            'special' => 'مشروع خاص',
        ][$this->project_type] ?? $this->project_type;
    }

    /**
     * إضافة cast للتواريخ
     */
    protected $casts = [
        'srgn_date' => 'date',
        'kick_off_date' => 'date',
        'tcc_date' => 'date',
        'pac_date' => 'date',
        'fat_date' => 'date',
    ];

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