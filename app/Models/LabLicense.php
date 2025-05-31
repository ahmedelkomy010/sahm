<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultant',
        'urs',
        'contractor',
        'date',
        'permit_no',
        'permit_date',
        'street_type_terabi',
        'street_type_asphalt',
        'street_type_blat',
        'lab_check',
        'year',
        'work_type',
        'depth',
        'soil_compaction',
        'mc1rc2',
        'max_density',
        'asphalt_percent',
        'gradation',
        'marshall',
        'tile_eval',
        'soil_class',
        'proctor',
        'concrete',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'permit_date' => 'date',
        'street_type_terabi' => 'boolean',
        'street_type_asphalt' => 'boolean',
        'street_type_blat' => 'boolean',
        'year' => 'integer',
    ];
} 