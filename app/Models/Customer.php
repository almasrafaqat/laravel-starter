<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [

        'name',
        'email',
        'cc',
        'bcc',
        'address',
        'phone',
        'company',
        'balance',
        'credit_balance',
        'status',
        'payment_method',
        'company_id',
        'customer_id',
        'creator_id',
        'contact_method',


    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_customer');
    }
}
