<?php

namespace App\GraphQL\Mutations;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompanyMutation
{
    public function createCompany($rootValue, array $args)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            
            // Check if user is trying to set as default
            if (isset($args['input']['is_default']) && $args['input']['is_default']) {
                // Remove default flag from other companies
                Company::whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })->update(['is_default' => false]);
            }

            $company = Company::create([
                ...$args['input'],
                'created_by' => $user->id,
            ]);

            // Attach the user to the company
            $company->users()->attach($user->id);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Company created successfully',
                'company' => $company->load(['users', 'plans']),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Failed to create company: ' . $e->getMessage(),
                'company' => null,
            ];
        }
    }

    public function updateCompany($rootValue, array $args)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $company = Company::findOrFail($args['input']['id']);

            // Check if user has access to this company
            if (!$company->users->contains($user->id)) {
                throw new \Exception('You do not have permission to update this company');
            }

            // Handle default company flag
            if (isset($args['input']['is_default']) && $args['input']['is_default']) {
                Company::whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })->update(['is_default' => false]);
            }

            $updateData = $args['input'];
            unset($updateData['id']);
            $updateData['updated_by'] = $user->id;

            $company->update($updateData);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Company updated successfully',
                'company' => $company->fresh()->load(['users', 'plans']),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Failed to update company: ' . $e->getMessage(),
                'company' => null,
            ];
        }
    }

    public function deleteCompany($rootValue, array $args)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $company = Company::findOrFail($args['id']);

            // Check if user has access to this company
            if (!$company->users->contains($user->id)) {
                throw new \Exception('You do not have permission to delete this company');
            }

            // Prevent deleting if it's the default company
            if ($company->is_default) {
                throw new \Exception('Cannot delete the default company. Please set another company as default first.');
            }

            $company->deleted_by = $user->id;
            $company->save();
            $company->delete(); // Soft delete

            DB::commit();

            return [
                'success' => true,
                'message' => 'Company deleted successfully',
                'company' => null,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Failed to delete company: ' . $e->getMessage(),
                'company' => null,
            ];
        }
    }

    public function setDefaultCompany($rootValue, array $args)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $company = Company::findOrFail($args['id']);

            // Check if user has access to this company
            if (!$company->users->contains($user->id)) {
                throw new \Exception('You do not have permission to set this company as default');
            }

            // Remove default flag from all user's companies
            Company::whereHas('users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })->update(['is_default' => false]);

            // Set this company as default
            $company->update(['is_default' => true]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Default company set successfully',
                'company' => $company->fresh()->load(['users', 'plans']),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            
            return [
                'success' => false,
                'message' => 'Failed to set default company: ' . $e->getMessage(),
                'company' => null,
            ];
        }
    }
}