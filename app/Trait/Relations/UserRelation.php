<?php

namespace App\Trait\Relations;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\SocialAccount;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserRelation
{
    public function companies()
    {
        return $this->belongsToMany(Company::class)
            ->withPivot('role') // optional
            ->withTimestamps();
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }



    public function createdInvoices()
    {
        return $this->hasMany(Invoice::class, 'creator_id');
    }

    
}
