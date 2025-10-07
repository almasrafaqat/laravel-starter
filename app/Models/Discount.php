<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'discount_type',
        'discount_value',
        'discount_amount',
        'discount_name',
        'discountable_id',
        'discountable_type'
    ];
    protected $casts = [
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function discountable()
    {
        return $this->morphTo();
    }
}
