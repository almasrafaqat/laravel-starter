<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'category_id', 'is_active', 'order'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function checklistables() {
        return $this->hasMany(Checklistable::class);
    }
}
