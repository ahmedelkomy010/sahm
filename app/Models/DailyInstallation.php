<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyInstallation extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'work_date',
        'installation_data',
        'total_amount',
        'total_items',
        'user_id',
        'notes'
    ];

    protected $casts = [
        'work_date' => 'date',
        'installation_data' => 'array',
        'total_amount' => 'decimal:2',
        'total_items' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the work order that owns this daily installation.
     */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /**
     * Get the user who created this daily installation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the formatted date for display.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->work_date->format('d-m-Y');
    }

    /**
     * Get the formatted amount for display.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->total_amount, 2) . ' ريال';
    }

    /**
     * Calculate total from installation data.
     */
    public function calculateTotals(): void
    {
        if (!$this->installation_data || !is_array($this->installation_data)) {
            $this->total_amount = 0;
            $this->total_items = 0;
            return;
        }

        $totalAmount = 0;
        $totalItems = 0;

        foreach ($this->installation_data as $installation) {
            $totalAmount += floatval($installation['total'] ?? 0);
            $totalItems += intval($installation['quantity'] ?? 0);
        }

        $this->total_amount = $totalAmount;
        $this->total_items = $totalItems;
    }

    /**
     * Scope for filtering by work order.
     */
    public function scopeForWorkOrder($query, $workOrderId)
    {
        return $query->where('work_order_id', $workOrderId);
    }

    /**
     * Scope for filtering by date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('work_date', $date);
    }

    /**
     * Scope for filtering by date range.
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('work_date', [$startDate, $endDate]);
    }

    /**
     * Get the latest daily installation for a work order.
     */
    public static function getLatestForWorkOrder($workOrderId)
    {
        return static::where('work_order_id', $workOrderId)
            ->orderBy('work_date', 'desc')
            ->first();
    }

    /**
     * Get daily installations for a specific month.
     */
    public static function getForMonth($workOrderId, $year, $month)
    {
        return static::where('work_order_id', $workOrderId)
            ->whereYear('work_date', $year)
            ->whereMonth('work_date', $month)
            ->orderBy('work_date', 'desc')
            ->get();
    }
}
