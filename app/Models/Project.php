<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'contract_number',
        'description',
        'status'
    ];

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
} 