<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [

        'schedule_date',
        'timezone',
        'message',
        'expiry_date',
        'recurrence',
        'status',
        'is_sent',
        'method'
    ];

    public function remindable()
    {
        return $this->morphTo();
    }
}
