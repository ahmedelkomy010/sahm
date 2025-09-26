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
        'requires_action' => false,
        'survey_type' => 'site'
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
        return $this->hasMany(WorkOrderFile::class, 'survey_id')->where('file_category', 'survey_images');
    }

    public function completionFiles()
    {
        return $this->hasMany(WorkOrderFile::class, 'survey_id')->where('file_category', 'completion_files');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($survey) {
            // التأكد من وجود رقم المسح
            if (empty($survey->survey_number)) {
                $survey->survey_number = 'SV-' . time() . '-' . $survey->work_order_id;
            }
        });

        static::deleting(function ($survey) {
            // حذف الملفات المرتبطة عند حذف المسح
            foreach ($survey->files as $file) {
                if (\Storage::disk('public')->exists($file->file_path)) {
                    \Storage::disk('public')->delete($file->file_path);
                }
            }
            $survey->files()->delete();
        });
    }
} 