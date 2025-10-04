<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charity extends Model
{
    protected $fillable = [
        'invoice_id', 'cause_name', 'type', 'value', 'amount_usd', 'amount_pkr', 'paid', 'remaining',
        'currency_rate', 'is_contributed', 'contribution_date', 'notes'
    ];

    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function charitable() {
        return $this->morphTo();
    }
}
