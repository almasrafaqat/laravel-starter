<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'title', 'invoice_number', 'template_id', 'date', 'valid_until', 'customer_id', 'company_id', 'creator_id',
        'payment_status', 'status', 'paid_on', 'payment_method', 'reference', 'description',
        'timeframe', 'importance', 'amount_paid', 'subtotal', 'tax_rate', 'tax_amount', 'total',
        'balance_due', 'currency', 'currency_rate', 'total_pkr', 'notes'
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function items() {
        return $this->hasMany(Item::class);
    }

    public function discounts() {
        return $this->morphMany(Discount::class, 'discountable');
    }

    public function reminders() {
        return $this->morphMany(Reminder::class, 'remindable');
    }

    public function links() {
        return $this->morphMany(Link::class, 'linkable');
    }

    public function charities() {
        return $this->hasMany(Charity::class);
    }

    public function checklistables() {
        return $this->morphMany(Checklistable::class, 'checklistable');
    }
}
