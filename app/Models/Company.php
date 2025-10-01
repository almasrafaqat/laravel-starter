<?php

namespace App\Models;

use App\Trait\Relations\CompanyRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use CompanyRelation, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'website',
        'logo',
        'tax_number',
        'registration_number',
        'country',
        'state',
        'city',
        'zip_code',
        'description',
        'language',
        'currency',
        'is_active',
        'is_default',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];
}
