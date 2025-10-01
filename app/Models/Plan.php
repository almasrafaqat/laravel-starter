<?php

namespace App\Models;

use App\Trait\Relations\PlanRelation;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use PlanRelation;
    protected $fillable = [
        'name',
        'description',
        'price',
        'billing_cycle',
        'is_active',
        'is_default',
        'max_users',
        'max_projects',
        'features',
        'currency',
        'duration_days',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'price' => 'float',
        'max_users' => 'integer',
        'max_projects' => 'integer',
        'duration_days' => 'integer',
    ];

    // Relationship: Plan has many PlanMetas
   
}
