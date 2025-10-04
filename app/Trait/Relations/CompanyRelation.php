<?php

namespace App\Trait\Relations;

use App\Models\CompanyMeta;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\User;

trait CompanyRelation
{
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role') // optional
            ->withTimestamps();
    }

    public function metas()
    {
        return $this->hasMany(CompanyMeta::class);
    }

    public function getMeta($key, $default = null)
    {
        $meta = $this->metas->firstWhere('key', $key);
        return $meta ? $meta->value : $default;
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'company_plan')
            ->withPivot('starts_at', 'ends_at')
            ->withTimestamps();
    }

    // Helper for current active plan
    public function currentPlan()
    {
        return $this->plans()
            ->wherePivot('starts_at', '<=', now())
            ->wherePivot('ends_at', '>=', now())
            ->first();
    }



    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
