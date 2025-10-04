<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklistable extends Model
{
    public function checklist() {
        return $this->belongsTo(Checklist::class);
    }

    public function checklistable() {
        return $this->morphTo();
    }
}
