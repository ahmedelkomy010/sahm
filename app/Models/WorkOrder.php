<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'work_type',
        'work_description',
        'approval_date',
        'subscriber_name',
        'district',
        'station_number',
        'consultant_name',
        'order_value_with_consultant',
        'order_value_without_consultant',
        'final_total_value',
        'execution_status',
        'actual_execution_value',
        'procedure_155_delivery_date',
        'procedure_211_date',
        'partial_deletion',
        'partial_payment_value',
        'office',
        'extract_number',
        'extract_date',
        'extract_value',
        'invoice_number',
        'purchase_order_number',
        'tax_value',
        'notes',
        'project',
        'user_id',
        'execution_file',
        'execution_notes',
        'single_meter_installation',
        'double_meter_installation',
        'excavation_unsurfaced_soil',
        'excavation_surfaced_soil',
        'excavation_unsurfaced_rock',
        'excavation_surfaced_rock',
        'excavation_precise',
        'excavation_unsurfaced_soil_open',
        'excavation_surfaced_soil_open',
        'excavation_unsurfaced_rock_open',
        'excavation_surfaced_rock_open',
        'open_excavation',
        'excavation_details_table',
        'entry_sheet',
        'actual_execution_value_consultant',
        'actual_execution_value_without_consultant',
        'first_partial_payment_without_tax',
        'second_partial_payment_with_tax',
        'municipality',
        'electrical_works',
        'installations_data',
        'installations_locked',
        'installations_locked_at',
        'installations_locked_by',
        'pre_operation_tests',
        'daily_civil_works_data',
    ];

    protected $casts = [
        'approval_date' => 'date',
        'procedure_155_delivery_date' => 'date',
        'procedure_211_date' => 'date',
        'extract_date' => 'date',
        'partial_deletion' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'single_meter_installation' => 'string',
        'double_meter_installation' => 'string',
        'excavation_unsurfaced_soil' => 'array',
        'excavation_surfaced_soil' => 'array',
        'excavation_unsurfaced_rock' => 'array',
        'excavation_surfaced_rock' => 'array',
        'excavation_precise' => 'array',
        'excavation_unsurfaced_soil_open' => 'array',
        'excavation_surfaced_soil_open' => 'array',
        'excavation_unsurfaced_rock_open' => 'array',
        'excavation_surfaced_rock_open' => 'array',
        'open_excavation' => 'array',
        'excavation_details_table' => 'array',
        'invoice_images' => 'array',
        'electrical_works' => 'array',
        'installations_data' => 'array',
        'daily_civil_works_data' => 'array',
    ];

    // Relationships
    public function files()
    {
        return $this->hasMany(WorkOrderFile::class);
    }

    public function civilWorksFiles()
    {
        return $this->hasMany(WorkOrderFile::class)->where('file_category', 'civil_works_execution');
    }

    public function civilWorksAttachments()
    {
        return $this->hasMany(WorkOrderFile::class)->where('file_category', 'civil_works_attachments');
    }

    public function installationsFiles()
    {
        return $this->hasMany(WorkOrderFile::class)->where('file_category', 'installations');
    }

    public function electricalWorksFiles()
    {
        return $this->hasMany(WorkOrderFile::class)->where('file_category', 'electrical_works');
    }

    public function basicAttachments()
    {
        return $this->hasMany(WorkOrderFile::class)->where('file_category', 'basic_attachments');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    public function survey()
    {
        return $this->hasOne(Survey::class);
    }

    public function license()
    {
        return $this->hasOne(License::class)->withDefault();
    }

    public function licenses()
    {
        return $this->hasMany(License::class);
    }

    public function invoiceAttachments()
    {
        return $this->hasMany(InvoiceAttachment::class);
    }

    /**
     * Get the work items for this work order.
     */
    public function workItems()
    {
        return $this->belongsToMany(WorkItem::class, 'work_order_items')
                    ->withPivot('planned_quantity', 'actual_quantity', 'notes')
                    ->withTimestamps();
    }

    /**
     * Get the work order items for this work order.
     */
    public function workOrderItems()
    {
        return $this->hasMany(WorkOrderItem::class);
    }

    /**
     * Get the materials for this work order (new system).
     */
    public function workOrderMaterials()
    {
        return $this->hasMany(WorkOrderMaterial::class);
    }

    /**
     * العلاقة مع المواد
     */
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    /**
     * العلاقة مع المخالفات
     */
    public function violations()
    {
        return $this->hasMany(LicenseViolation::class);
    }

    public function excavationDetails()
    {
        return $this->hasMany(ExcavationDetail::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'completed');
    }

    // Accessors & Mutators
    public function getStatusLabelAttribute()
    {
        return [
            'pending' => 'قيد الانتظار',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
        ][$this->status] ?? $this->status;
    }

    public function getPriorityLabelAttribute()
    {
        return [
            'low' => 'منخفض',
            'medium' => 'متوسط',
            'high' => 'عالي',
            'urgent' => 'عاجل',
        ][$this->priority] ?? $this->priority;
    }

    // Accessor للتركيبات
    public function getInstallationsAttribute()
    {
        $data = $this->installations_data;
        
        // إذا كانت البيانات string، حولها إلى array
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            $data = is_array($decoded) ? $decoded : [];
        }
        
        // إذا كانت البيانات array، تأكد من صحة البيانات
        if (is_array($data)) {
            // التأكد من أن القيم الرقمية تظهر كما هي وليس كـ "0"
            foreach ($data as $key => $item) {
                if (isset($item['quantity'])) {
                    // الاحتفاظ بالقيم الرقمية الصحيحة فقط
                    if (is_numeric($item['quantity']) && $item['quantity'] > 0) {
                        $data[$key]['quantity'] = (string) intval($item['quantity']);
                    } else {
                        $data[$key]['quantity'] = '';
                    }
                }
            }
            return $data;
        }
        
        // إذا كانت البيانات null أو غير صالحة، أرجع array فارغ
        return [];
    }
}