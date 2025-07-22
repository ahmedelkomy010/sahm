<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyCivilWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'work_date',
        'work_type',
        'cable_type',
        'length',
        'width',
        'depth',
        'volume',
        'unit_price',
        'total_cost',
        'unit',
        'notes',
    ];

    protected $casts = [
        'work_date' => 'date',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'depth' => 'decimal:2',
        'volume' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    /**
     * العلاقة مع أمر العمل
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * حساب التكلفة الإجمالية تلقائياً
     */
    public function calculateTotalCost()
    {
        if ($this->volume && $this->volume > 0) {
            // للحفريات الحجمية (متر مكعب)
            return $this->volume * $this->unit_price;
        } else {
            // للحفريات الخطية (متر طولي)
            return $this->length * $this->unit_price;
        }
    }

    /**
     * حساب الحجم للحفريات الحجمية
     */
    public function calculateVolume()
    {
        if ($this->length && $this->width && $this->depth) {
            return $this->length * $this->width * $this->depth;
        }
        return 0;
    }

    /**
     * تحديث التكلفة تلقائياً قبل الحفظ
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // حساب الحجم إذا توفرت الأبعاد
            if ($model->length && $model->width && $model->depth) {
                $model->volume = $model->calculateVolume();
                $model->unit = 'متر مكعب';
            } elseif ($model->length) {
                $model->unit = 'متر';
            }

            // حساب التكلفة الإجمالية
            $model->total_cost = $model->calculateTotalCost();
        });
    }

    /**
     * الحصول على جميع البيانات لتاريخ معين
     */
    public static function getByWorkOrderAndDate($workOrderId, $date)
    {
        return self::where('work_order_id', $workOrderId)
                   ->where('work_date', $date)
                   ->orderBy('created_at')
                   ->get();
    }

    /**
     * الحصول على إجمالي التكلفة لتاريخ معين
     */
    public static function getTotalCostByDate($workOrderId, $date)
    {
        return self::where('work_order_id', $workOrderId)
                   ->where('work_date', $date)
                   ->sum('total_cost');
    }

    /**
     * الحصول على إجمالي الطول لتاريخ معين
     */
    public static function getTotalLengthByDate($workOrderId, $date)
    {
        return self::where('work_order_id', $workOrderId)
                   ->where('work_date', $date)
                   ->sum('length');
    }

    /**
     * الحصول على تواريخ العمل المتوفرة لأمر عمل معين
     */
    public static function getAvailableDatesByWorkOrder($workOrderId)
    {
        return self::where('work_order_id', $workOrderId)
                   ->distinct()
                   ->pluck('work_date')
                   ->sort()
                   ->values();
    }

    /**
     * حذف جميع البيانات لتاريخ معين
     */
    public static function clearByWorkOrderAndDate($workOrderId, $date)
    {
        return self::where('work_order_id', $workOrderId)
                   ->where('work_date', $date)
                   ->delete();
    }
}
