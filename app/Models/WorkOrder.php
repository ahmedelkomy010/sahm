<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    public static $rules = [
        'order_number' => 'required|string|max:255',
        'work_type' => 'required|string|max:999',
        'work_description' => 'required|string',
        'approval_date' => 'required|date',
        'subscriber_name' => 'required|string|max:255',
        'district' => 'required|string|max:255',
        'order_value_with_consultant' => 'required|numeric|min:0',
        'order_value_without_consultant' => 'required|numeric|min:0',
        'execution_status' => 'required|in:1,2,3,4,5,6,7,8,9',
        'municipality' => 'nullable|string|max:255',
        'office' => 'nullable|string|max:255',
        'task_number' => 'nullable|string|max:255',
        'station_number' => 'nullable|string|max:255',
        'consultant_name' => 'nullable|string|max:255',
        'city' => 'required|string|max:255',
        'manual_days' => 'required|numeric|min:0',
        'materials' => 'required|array',
        'materials.*.material_code' => 'required|string|max:255',
        'materials.*.material_description' => 'required|string|max:255',
        'materials.*.planned_quantity' => 'required|numeric|min:0',
        'materials.*.unit' => 'required|string|max:50',
        'materials.*.notes' => 'nullable|string',
    ];

    protected $fillable = [
        'is_received',
        'received_at',
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
        'execution_status_date',
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
        'excavation_unsurfaced_soil_price',
        'excavation_surfaced_soil_price',
        'excavation_unsurfaced_rock_price',
        'excavation_surfaced_rock_price',
        'excavation_unsurfaced_soil_open_price',
        'excavation_surfaced_soil_open_price',
        'excavation_unsurfaced_rock_open_price',
        'excavation_surfaced_rock_open_price',
        'excavation_details_table',
        'entry_sheet',
        'entry_sheet_1',
        'entry_sheet_2',
        'actual_execution_value_consultant',
        'actual_execution_value_without_consultant',
        'first_partial_payment_without_tax',
        'second_partial_payment_with_tax',
        'municipality',
        'task_number',

        'pre_operation_tests',
        'license_id',
        'city',
        'manual_days',
        'materials_notes',
        'delay_penalties',
        'safety_notes',
        'safety_status',
        'safety_officer',
        'inspection_date',
        'non_compliance_reasons',
        'non_compliance_attachments',
        'safety_permits_images',
        'safety_team_images',
        'safety_equipment_images',
        'safety_general_images',
        'safety_tbt_images',
        'removal_scrap_materials',
    ];

    protected $casts = [
        'is_received' => 'boolean',
        'received_at' => 'datetime',
        'approval_date' => 'date',
        'procedure_155_delivery_date' => 'date',
        'procedure_211_date' => 'date',
        'extract_date' => 'date',
        'payment_date' => 'datetime',
        'partial_deletion' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'inspection_date' => 'date',
        'execution_status_date' => 'datetime',
        'safety_permits_images' => 'array',
        'safety_team_images' => 'array',
        'safety_equipment_images' => 'array',
        'safety_general_images' => 'array',
        'safety_tbt_images' => 'array',
        'non_compliance_attachments' => 'array',
        'removal_scrap_materials' => 'array',
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
        'excavation_unsurfaced_soil_price' => 'array',
        'excavation_surfaced_soil_price' => 'array',
        'excavation_unsurfaced_rock_price' => 'array',
        'excavation_surfaced_rock_price' => 'array',
        'excavation_details_table' => 'array',
        'invoice_images' => 'array',


    ];

    // Relationships
    public function files()
    {
        return $this->hasMany(WorkOrderFile::class);
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

    /**
     * العلاقة مع مخالفات السلامة
     */
    public function safetyViolations()
    {
        return $this->hasMany(SafetyViolation::class);
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
                    ->withPivot('quantity', 'unit_price', 'executed_quantity', 'notes')
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

    /**
     * Get daily execution notes for this work order
     */
    public function dailyExecutionNotes()
    {
        return $this->hasMany(DailyExecutionNote::class);
    }

    /**
     * Get daily execution note for a specific date
     */
    public function getDailyExecutionNoteForDate($date)
    {
        return $this->dailyExecutionNotes()->where('execution_date', $date)->first();
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

    // Accessor لرقم أمر العمل
    public function getWorkOrderNumberAttribute()
    {
        return $this->order_number;
    }

    // Helper method للحصول على قيم الحفريات بطريقة آمنة
    public function getExcavationValue($fieldName, $index)
    {
        $data = $this->getAttribute($fieldName);
        
        if (!is_array($data)) {
            return '';
        }
        
        if (!isset($data[$index])) {
            return '';
        }
        
        $value = $data[$index];
        
        // إذا كانت القيمة array، أرجع فراغ
        if (is_array($value)) {
            return '';
        }
        
        // إذا كانت القيمة string أو number، أرجعها
        return (string) $value;
    }

    // Helper method عام للحصول على قيم الحقول بطريقة آمنة
    public function getSafeFieldValue($fieldName, $default = '')
    {
        $value = $this->getAttribute($fieldName);
        
        // إذا كانت القيمة array، أرجع القيمة الافتراضية
        if (is_array($value)) {
            return $default;
        }
        
        // إذا كانت القيمة null، أرجع القيمة الافتراضية
        if ($value === null) {
            return $default;
        }
        
        // أرجع القيمة كـ string
        return (string) $value;
    }

    // Helper method للحصول على قيم أسعار الحفريات بطريقة آمنة  
    public function getExcavationPrice($fieldName, $index)
    {
        $data = $this->getAttribute($fieldName);
        
        if (!is_array($data)) {
            return '';
        }
        
        if (!isset($data[$index])) {
            return '';
        }
        
        $value = $data[$index];
        
        // إذا كانت القيمة array، أرجع فراغ
        if (is_array($value)) {
            return '';
        }
        
        // تحويل للرقم وإرجاع string
        return number_format((float) $value, 2, '.', '');
    }

    // Helper method للحصول على قيم الحفريات بالمفتاح بطريقة آمنة
    public function getExcavationValueByKey($fieldName, $key)
    {
        $data = $this->getAttribute($fieldName);
        
        if (!is_array($data)) {
            return '';
        }
        
        if (!isset($data[$key])) {
            return '';
        }
        
        $value = $data[$key];
        
        // إذا كانت القيمة array، أرجع فراغ
        if (is_array($value)) {
            return '';
        }
        
        // إذا كانت القيمة string أو number، أرجعها
        return (string) $value;
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