<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'project',
        'city',
        'client_name',
        'project_area',
        'contract_number',
        'extract_number',
        'office',
        'extract_type',
        'po_number',
        'invoice_number',
        'extract_value',
        'tax_percentage',
        'tax_value',
        'penalties',
        'first_payment_tax',
        'net_extract_value',
        'extract_date',
        'year',
        'payment_type',
        'reference_number',
        'payment_date',
        'payment_value',
        'extract_status'
    ];

    protected $casts = [
        'extract_value' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_value' => 'decimal:2',
        'penalties' => 'decimal:2',
        'first_payment_tax' => 'decimal:2',
        'net_extract_value' => 'decimal:2',
        'payment_value' => 'decimal:2',
        'extract_date' => 'date',
        'payment_date' => 'date',
    ];
}