<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itemable extends Model
{
    public function itemable() {
        return $this->morphTo();
    }

    public function item() {
        return $this->belongsTo(Item::class);
    }
}
