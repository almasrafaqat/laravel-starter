<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'invoice_id',
        'name',
        'description',
        'quantity',
        'price',
        'subtotal',
        'is_discounted',
        'is_excluded_invoice_discount',
        'is_taxed',
        'is_excluded_invoice_taxed',
        'total'
    ];



    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function itemables()
    {
        return $this->morphMany(Itemable::class, 'itemable');
    }

    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }
    public function taxes()
    {
        return $this->morphMany(Tax::class, 'taxable');
    }
}
