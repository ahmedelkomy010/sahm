<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Material extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'work_order_id',
        'work_order_number', 
        'subscriber_name',
        'code',
        'description',
        'planned_quantity',
        'spent_quantity',
        'executed_quantity',
        'quantity_difference',
        'planned_spent_difference',
        'executed_spent_difference',
        'unit',
        'line',
        'check_in_file',
        'check_out_file',
        'date_gatepass',
        'stock_in',
        'stock_in_file',
        'stock_out',
        'stock_out_file',
        'gate_pass_file',
        'store_in_file',
        'store_out_file',
        'ddo_file',
    ];
    
    protected $casts = [
        'date_gatepass' => 'date',
        'planned_quantity' => 'decimal:2',
        'spent_quantity' => 'decimal:2',
        'executed_quantity' => 'decimal:2',
        'quantity_difference' => 'decimal:2',
        'planned_spent_difference' => 'decimal:2',
        'executed_spent_difference' => 'decimal:2',
    ];

    protected $attributes = [
        'planned_quantity' => 0,
        'spent_quantity' => 0,
        'executed_quantity' => 0,
        'quantity_difference' => 0,
        'planned_spent_difference' => 0,
        'executed_spent_difference' => 0,
        'unit' => 'قطعة',
        'line' => '',
    ];
    
    /**
     * العلاقة مع أمر العمل
     */
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * Set the planned quantity attribute.
     */
    public function setPlannedQuantityAttribute($value)
    {
        $this->attributes['planned_quantity'] = $value ?? 0;
        $this->calculateQuantityDifference();
    }

    /**
     * Set the spent quantity attribute.
     */
    public function setSpentQuantityAttribute($value)
    {
        $this->attributes['spent_quantity'] = $value ?? 0;
    }

    /**
     * Set the executed quantity attribute.
     */
    public function setExecutedQuantityAttribute($value)
    {
        $this->attributes['executed_quantity'] = $value ?? 0;
        $this->calculateQuantityDifference();
    }

    /**
     * Calculate the quantity difference between planned and executed.
     */
    public function calculateQuantityDifference()
    {
        $planned = $this->attributes['planned_quantity'] ?? 0;
        $executed = $this->attributes['executed_quantity'] ?? 0;
        $this->attributes['quantity_difference'] = $planned - $executed;
    }

    /**
     * Get the quantity difference as a formatted string.
     */
    public function getQuantityDifferenceFormattedAttribute()
    {
        $difference = $this->quantity_difference;
        if ($difference > 0) {
            return "زيادة مخططة: " . number_format($difference, 2);
        } elseif ($difference < 0) {
            return "تنفيذ زائد: " . number_format(abs($difference), 2);
        } else {
            return "متطابقة";
        }
    }

    /**
     * نطاق للمواد التي تحتوي على ملفات
     */
    public function scopeWithFiles($query)
    {
        return $query->where(function($q) {
            $q->whereNotNull('check_in_file')
              ->orWhereNotNull('check_out_file')
              ->orWhereNotNull('stock_in_file')
              ->orWhereNotNull('stock_out_file')
              ->orWhereNotNull('gate_pass_file')
              ->orWhereNotNull('store_in_file')
              ->orWhereNotNull('store_out_file')
              ->orWhereNotNull('ddo_file');
        });
    }

    /**
     * نطاق للمواد التي لا تحتوي على ملفات
     */
    public function scopeWithoutFiles($query)
    {
        return $query->where(function($q) {
            $q->whereNull('check_in_file')
              ->whereNull('check_out_file')
              ->whereNull('stock_in_file')
              ->whereNull('stock_out_file')
              ->whereNull('gate_pass_file')
              ->whereNull('store_in_file')
              ->whereNull('store_out_file')
              ->whereNull('ddo_file');
        });
    }

    /**
     * الحصول على رابط ملف الدخول
     */
    public function getCheckInFileUrlAttribute()
    {
        return $this->check_in_file ? Storage::url($this->check_in_file) : null;
    }

    /**
     * الحصول على رابط ملف الخروج
     */
    public function getCheckOutFileUrlAttribute()
    {
        return $this->check_out_file ? Storage::url($this->check_out_file) : null;
    }

    /**
     * الحصول على رابط ملف الدخول للمخزن
     */
    public function getStockInFileUrlAttribute()
    {
        return $this->stock_in_file ? Storage::url($this->stock_in_file) : null;
    }

    /**
     * الحصول على رابط ملف الخروج من المخزن
     */
    public function getStockOutFileUrlAttribute()
    {
        return $this->stock_out_file ? Storage::url($this->stock_out_file) : null;
    }

    /**
     * الحصول على رابط ملف تصريح المرور
     */
    public function getGatePassFileUrlAttribute()
    {
        return $this->gate_pass_file ? Storage::url($this->gate_pass_file) : null;
    }

    /**
     * الحصول على رابط ملف الدخول للمتجر
     */
    public function getStoreInFileUrlAttribute()
    {
        return $this->store_in_file ? Storage::url($this->store_in_file) : null;
    }

    /**
     * الحصول على رابط ملف الخروج من المتجر
     */
    public function getStoreOutFileUrlAttribute()
    {
        return $this->store_out_file ? Storage::url($this->store_out_file) : null;
    }

    /**
     * الحصول على رابط ملف DDO
     */
    public function getDdoFileUrlAttribute()
    {
        return $this->ddo_file ? Storage::url($this->ddo_file) : null;
    }

    /**
     * التحقق من وجود أي ملفات مرفقة
     */
    public function hasAttachments()
    {
        return $this->check_in_file || $this->check_out_file || $this->stock_in_file || 
               $this->stock_out_file || $this->gate_pass_file || $this->store_in_file || 
               $this->store_out_file || $this->ddo_file;
    }

    /**
     * الحصول على جميع المرفقات
     */
    public function getAttachments()
    {
        $attachments = [];
        
        if ($this->check_in_file) {
            $attachments['check_in'] = [
                'name' => ' CHECK LIST',
                'url' => $this->check_in_file_url,
                'file' => $this->check_in_file
            ];
        }
        
        if ($this->check_out_file) {
            $attachments['check_out'] = [
                'name' => 'STORE OUT', 
                'url' => $this->check_out_file_url,
                'file' => $this->check_out_file
            ];
        }
        
        if ($this->stock_in_file) {
            $attachments['stock_in'] = [
                'name' => 'STORE IN',
                'url' => $this->stock_in_file_url,
                'file' => $this->stock_in_file
            ];
        }
        
        if ($this->stock_out_file) {
            $attachments['stock_out'] = [
                'name' => 'STORE OUT',
                'url' => $this->stock_out_file_url,
                'file' => $this->stock_out_file
            ];
        }
        
        if ($this->gate_pass_file) {
            $attachments['gate_pass'] = [
                'name' => 'GATE PASS',
                'url' => $this->gate_pass_file_url,
                'file' => $this->gate_pass_file
            ];
        }
        
        if ($this->store_in_file) {
            $attachments['store_in'] = [
                'name' => 'STORE OUT',
                'url' => $this->store_in_file_url,
                'file' => $this->store_in_file
            ];
        }
        
        if ($this->store_out_file) {
            $attachments['store_out'] = [
                'name' => 'STORE IN',
                'url' => $this->store_out_file_url,
                'file' => $this->store_out_file
            ];
        }
        
        if ($this->ddo_file) {
            $attachments['ddo'] = [
                'name' => ' DDO',
                'url' => $this->ddo_file_url,
                'file' => $this->ddo_file
            ];
        }
        
        return $attachments;
    }
}
