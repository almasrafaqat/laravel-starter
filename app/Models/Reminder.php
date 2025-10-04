<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [
        'schedule_date', 'timezone', 'message'
    ];

    public function remindable() {
        return $this->morphTo();
    }
}
