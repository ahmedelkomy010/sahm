<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialProjectRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'client_name',
        'project',
        'contract_number',
        'extract_number',
        'office',
        'extract_type',
        'po_number',
        'invoice_number',
        'total_value',
        'extract_entity',
        'tax_value',
        'penalties',
        'advance_payment_tax',
        'net_value',
        'preparation_date',
        'year',
        'extract_status',
        'reference_number',
        'payment_date',
        'payment_amount',
        'payment_status',
        'procedures',
        'attachment_path',
    ];

    protected $casts = [
        'total_value' => 'decimal:2',
        'tax_value' => 'decimal:2',
        'penalties' => 'decimal:2',
        'advance_payment_tax' => 'decimal:2',
        'net_value' => 'decimal:2',
        'payment_amount' => 'decimal:2',
        'preparation_date' => 'date:Y-m-d',
        'payment_date' => 'date:Y-m-d',
        'year' => 'integer',
    ];

    protected $dateFormat = 'Y-m-d';

    // تحويل التواريخ إلى string بصيغة Y-m-d عند الإرجاع
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }

    // العلاقة مع المشروع
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
