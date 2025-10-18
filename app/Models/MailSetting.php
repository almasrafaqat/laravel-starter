<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailSetting extends Model
{
    protected $fillable = [
        'transport',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_email',
        'from_name',
        'default',
        'is_default',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'default' => 'boolean',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_mail_setting');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
