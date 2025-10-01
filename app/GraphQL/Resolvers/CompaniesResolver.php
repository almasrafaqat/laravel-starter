<?php

namespace App\GraphQL\Resolvers;

use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Auth;

class CompaniesResolver
{
    public function __invoke($root, array $args, $context, $resolveInfo)
    {
        $user = Auth::user();
      

        // return $user->companies()->with('plans.metas')->get();
        // call service layer
        // return Company::all();
        // return Company::with(['users', 'plans'])->get();
        return CompanyService::getAllCompaniesWithRelations();
    }
}
