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
        'from_address',
        'from_name',
    ];

    protected $casts = [
        'default' => 'boolean',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_mail_setting');
    }
}
