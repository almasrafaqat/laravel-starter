<?php

namespace App\Trait\Relations;

use App\Models\Company;
use App\Models\PlanMeta;

trait PlanRelation
{
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_plan')
            ->withPivot('starts_at', 'ends_at')
            ->withTimestamps();
    }

    public function metas()
    {
        return $this->hasMany(PlanMeta::class);
    }
}
