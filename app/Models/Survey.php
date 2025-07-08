<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Survey extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'survey_number',
        'work_order_id',
        'created_by',
        'assigned_to',
        'status',
        'survey_type',
        'description',
        'survey_date',
        'survey_data',
        'city',
        'district',
        'address',
        'latitude',
        'longitude',
        'requires_action',
        'recommendations',
        'findings',
        'start_coordinates',
        'end_coordinates',
        'has_obstacles',
        'obstacles_notes'
    ];

    protected $casts = [
        'has_obstacles' => 'boolean',
        'requires_action' => 'boolean',
        'survey_date' => 'date',
        'survey_data' => 'array',
        'findings' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    protected $attributes = [
        'status' => 'pending',
        'has_obstacles' => false,
        'requires_action' => false
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function files()
    {
        return $this->hasMany(WorkOrderFile::class, 'survey_id');
    }

    // إزالة علاقة history مؤقتاً حيث أن نموذج SurveyHistory غير موجود
    // public function history()
    // {
    //     return $this->hasMany(SurveyHistory::class);
    // }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($survey) {
            if (empty($survey->survey_number)) {
                $survey->survey_number = 'SRV-' . date('Ymd') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
            if (empty($survey->created_by)) {
                $survey->created_by = auth()->id();
            }
        });
    }
} 