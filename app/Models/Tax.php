<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'taxable_id',
        'taxable_type',
        'tax_type',
        'tax_name',
        'tax_value',
        'tax_amount'
    ];

    protected $casts = [
        'tax_value' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    public function taxable()
    {
        return $this->morphTo();
    }
}
