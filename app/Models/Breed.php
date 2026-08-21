<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Breed extends Model
{
    protected $fillable = [
        'name', 'slug', 'origin_country',
        'registry_tica', 'registry_cfa', 'registry_fife',
        'size_category', 'weight_min_kg', 'weight_max_kg', 'coat_length',
        'energy_level', 'affection_level', 'child_friendly', 'grooming_needs', 'shedding_level', 'intelligence',
        'lifespan_min', 'lifespan_max', 'health_watch', 'hypoallergenic',
        'good_for_apartments', 'good_for_beginners',
        'description', 'temperament_summary', 'fun_fact', 'image',
        'popularity_rank', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'registry_tica' => 'boolean',
            'registry_cfa' => 'boolean',
            'registry_fife' => 'boolean',
            'weight_min_kg' => 'float',
            'weight_max_kg' => 'float',
            'health_watch' => 'array',
            'hypoallergenic' => 'boolean',
            'good_for_apartments' => 'boolean',
            'good_for_beginners' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
