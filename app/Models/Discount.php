<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'discount_type', 'discount', 'discount_amount'
    ];

    public function discountable() {
        return $this->morphTo();
    }
}
