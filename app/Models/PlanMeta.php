<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanMeta extends Model
{
    protected $fillable = [
        'plan_id',
        'meta_key',
        'meta_value',
        'meta_data',
        'is_active',
        'is_default',
        'meta_order',
        'meta_group',
        'meta_type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'meta_data' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'meta_order' => 'integer',
    ];

    // Relationship: PlanMeta belongs to Plan
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
