<?php

namespace App\Models;

use App\Trait\Relations\CompanyRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use CompanyRelation, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'website',
        'logo',
        'tax_number',
        'registration_number',
        'country',
        'state',
        'city',
        'zip_code',
        'description',
        'language',
        'currency',
        'is_active',
        'is_default',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];


    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'company_customer');
    }

    public function mailSettings()
    {
        return $this->belongsToMany(MailSetting::class, 'company_mail_setting');
    }



    // Used by GraphQL @method(name: "logoUrl")
    public function logoUrl(): ?string
    {
        if (!$this->logo) {
            return null;
        }

        // If already an absolute URL, return as-is
        if (preg_match('#^https?://#i', $this->logo)) {
            return $this->logo;
        }

        // Otherwise, generate a public URL from storage path
        return Storage::disk('public')->url($this->logo);
    }

  
}
