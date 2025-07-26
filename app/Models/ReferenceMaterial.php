<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceMaterial extends Model
{
    protected $fillable = [
        'code',
        'description',
        'unit',
        'city',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $attributes = [
        'unit' => 'قطعة',
        'city' => 'الرياض',
        'is_active' => true
    ];

    /**
     * Scope to filter by city
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope to filter by project (converts project to city name)
     */
    public function scopeByProject($query, $project)
    {
        $cityName = $this->getProjectCityName($project);
        return $query->where('city', $cityName);
    }

    /**
     * Get city name from project identifier
     */
    protected function getProjectCityName($project)
    {
        return match($project) {
            'riyadh' => 'الرياض',
            'madinah' => 'المدينة المنورة',
            default => 'الرياض'
        };
    }
}
