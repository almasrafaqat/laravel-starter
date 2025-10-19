<?php


namespace App\GraphQL\Queries;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class CompanyQuery
{
    public function myCompany($_, array $args)
    {
        // $company = Company::findOrFail($args['id']);

        $user = auth()->user();
        if (!$user) {
            throw new \Nuwave\Lighthouse\Exceptions\AuthenticationException('Unauthenticated.');
        }

        return $user->companies()->with('plans.metas')->first();
    }

    public function myCompanies($_, array $args)
    {
        $user = auth()->user();
        if (!$user) {
            throw new \Nuwave\Lighthouse\Exceptions\AuthenticationException('Unauthenticated.');
        }
        // Return all companies the user belongs to, with their plans and plan metas
        return $user->companies()->with(['metas', 'plans.metas', 'mailSettings'])->get();
    }
}
