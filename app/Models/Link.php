<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'link', 'is_active', 'expires_at'
    ];

    public function linkable() {
        return $this->morphTo();
    }
}
