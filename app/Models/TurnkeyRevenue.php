<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnkeyRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'project',
        'contract_number',
        'extract_number',
        'office',
        'extract_type',
        'po_number',
        'invoice_number',
        'location',
        'project_type',
        'extract_value',
        'extract_entity',
        'tax_value',
        'penalties',
        'net_extract_value',
        'payment_value',
        'remaining_amount',
        'first_payment_tax',
        'extract_date',
        'year',
        'extract_status',
        'reference_number',
        'payment_date',
        'payment_status',
        'notes',
        'attachment_path',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'extract_value' => 'decimal:2',
        'tax_value' => 'decimal:2',
        'penalties' => 'decimal:2',
        'net_extract_value' => 'decimal:2',
        'payment_value' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'first_payment_tax' => 'decimal:2',
        'extract_date' => 'date',
        'payment_date' => 'date',
    ];

    /**
     * علاقة مع المستخدم الذي أنشأ السجل
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * علاقة مع المستخدم الذي عدل السجل
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
