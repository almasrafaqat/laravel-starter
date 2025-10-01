<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyMeta extends Model
{
    protected $fillable = [
        'company_id',
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

    // Relationship: CompanyMeta belongs to Company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
