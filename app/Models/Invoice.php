<?php

namespace App\Models;

use App\Services\TimeConversionService;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'title',
        'invoice_number',
        'template_id',
        'date',
        'valid_until',
        'customer_id',
        'company_id',
        'creator_id',
        'payment_status',
        'status',
        'paid_on',
        'payment_method',
        'reference',
        'description',
        'timeframe',
        'importance',
        'amount_paid',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total',
        'balance_due',
        'currency',
        'currency_rate',
        'total_pkr',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'valid_until' => 'date',
        'amount_paid' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_pkr' => 'decimal:2',
        'currency_rate' => 'decimal:6'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }
    public function taxes()
    {
        return $this->morphMany(Tax::class, 'taxable');
    }

    public function reminders()
    {
        return $this->morphMany(Reminder::class, 'remindable');
    }

    public function links()
    {
        return $this->morphMany(Link::class, 'linkable');
    }

    public function charities()
    {
        return $this->hasMany(Charity::class);
    }

    public function checklistables()
    {
        return $this->morphMany(Checklistable::class, 'checklistable');
    }

    /**Order data user timezone */
    public function getFormattedDateAttribute()
    {
        $timeConversionService = app(TimeConversionService::class);
        $timezone = $this->getInvoiceTimezone();

        return $timeConversionService->getFormattedDate($this->created_at, $timezone);
    }

    private function getInvoiceTimezone()
    {
        return config('app.timezone');
    }
}
