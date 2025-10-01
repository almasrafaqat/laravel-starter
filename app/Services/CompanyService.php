<?php

namespace App\Services;

use App\Models\Company;

class CompanyService
{
    public static function getAllCompaniesWithRelations()
    {
        $companies = Company::with(['users', 'plans'])->get();
        
        return self::Transformer($companies);
    }

    public static function Transformer($companies)
    {
        return $companies->map(function ($company) {
            return [
                'id' => $company->id,
                'name' => $company->name,
                'users' => $company->users,
                'plans' => $company->plans,
            ];
        });
    }
}
