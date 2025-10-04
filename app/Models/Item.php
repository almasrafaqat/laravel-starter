<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'invoice_id', 'name', 'description', 'quantity', 'price'
    ];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function itemables() {
        return $this->morphMany(Itemable::class, 'itemable');
    }
}
