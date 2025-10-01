<?php


namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Auth;

class CompanyQuery
{
    public function myCompany($_, array $args)
    {
        $user = auth()->user();
        if (!$user) {
            throw new \Nuwave\Lighthouse\Exceptions\AuthenticationException('Unauthenticated.');
        }
        return $user->companies()->with('plans.metas')->first();
    }
}
