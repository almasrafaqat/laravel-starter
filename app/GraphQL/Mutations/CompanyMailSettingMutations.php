<?php


namespace App\GraphQL\Mutations;

use App\Models\Company;
use App\Models\MailSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyMailSettingMutations
{
    /**
     * Create a new SMTP configuration
     */
    public function create($rootValue, array $args)
    {
        try {
            $user = Auth::user();
            $input = $args['input'];

            // Verify company belongs to user
            $company = Company::where('id', $input['company_id'])
                ->where('created_by', $user->id)
                ->firstOrFail();

            DB::beginTransaction();

            $mailSetting = $company->mailSettings()->create([
                'type' => $input['type'] ?? 'smtp',
                'host' => $input['host'],
                'port' => $input['port'],
                'username' => $input['username'],
                'password' => $input['password'],
                'from_name' => $input['from_name'],
                'from_email' => $input['from_email'],
                'encryption' => $input['encryption'] ?? null,
                'default' => !empty($input['is_default']) ? '1' : '0',
                'is_default' => !empty($input['is_default']),
                'is_active' => $input['is_active'] ?? true,
                'created_by' => $user->id,
            ]);



            if (!empty($input['is_default']) && $input['is_default']) {
                $mailSetting::where('id', '!=', $mailSetting->id)
                    ->update(['is_default' => false]);
            }


            DB::commit();

            return $mailSetting;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating SMTP setting: ' . $e->getMessage());
            throw new \Exception('Failed to create SMTP configuration: ' . $e->getMessage());
        }
    }

    /**
     * Update existing SMTP configuration
     */
    public function update($rootValue, array $args)
    {
        try {
            $user = Auth::user();
            $id = $args['id'];
            $input = $args['input'];

            // Find the mail setting and verify ownership
            $mailSetting = MailSetting::whereHas('companies', function ($q) use ($user) {
                $q->where('companies.created_by', $user->id);
            })->findOrFail($id);

            DB::beginTransaction();

            // If setting this as default, unset other defaults
            if (!empty($input['is_default']) && $input['is_default']) {
                $mailSetting::where('id', '!=', $mailSetting->id)
                    ->update(['is_default' => false]);
            }

            // Update fields
            $updateData = array_filter([
                'type' => $input['type'] ?? $mailSetting->type,
                'host' => $input['host'] ?? $mailSetting->host,
                'port' => $input['port'] ?? $mailSetting->port,
                'username' => $input['username'] ?? $mailSetting->username,
                'password' => $input['password'] ?? $mailSetting->password,
                'from_name' => $input['from_name'] ?? $mailSetting->from_name,
                'from_email' => $input['from_email'] ?? $mailSetting->from_email,
                'encryption' => $input['encryption'] ?? $mailSetting->encryption,
                'is_active' => $input['is_active'] ?? $mailSetting->is_active,
                'updated_by' => $user->id,
            ], fn($value) => $value !== null);

            if (isset($input['is_default'])) {
                $updateData['is_default'] = $input['is_default'];
                $updateData['default'] = $input['is_default'] ? '1' : '0';
            }

            $mailSetting->update($updateData);

            DB::commit();

            return $mailSetting->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating SMTP setting: ' . $e->getMessage());
            throw new \Exception('Failed to update SMTP configuration: ' . $e->getMessage());
        }
    }

    /**
     * Delete SMTP configuration
     */
    public function delete($rootValue, array $args)
    {
        try {
            $user = Auth::user();
            $id = $args['id'];

            // Find and verify ownership
            $mailSetting = MailSetting::whereHas('companies', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            })->findOrFail($id);

            // Prevent deleting default SMTP
            if ($mailSetting->is_default) {
                throw new \Exception('Cannot delete the default SMTP configuration. Please set another SMTP as default first.');
            }

            $mailSetting->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting SMTP setting: ' . $e->getMessage());
            throw new \Exception('Failed to delete SMTP configuration: ' . $e->getMessage());
        }
    }

    /**
     * Set SMTP as default
     */
    public function setDefault($rootValue, array $args)
    {
        try {
            $user = Auth::user();
            $id = $args['id'];

            // Find and verify ownership
            $mailSetting = MailSetting::whereHas('companies', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            })->findOrFail($id);

            DB::beginTransaction();

            // Unset all defaults for this company
            MailSetting::query()->update(['is_default' => false]);

            // Set this one as default
            $mailSetting->update([
                'is_default' => true,
                'default' => '1',
                'updated_by' => $user->id,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'SMTP configuration set as default successfully.',
                'data' => $mailSetting->fresh(),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error setting default SMTP: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to set default SMTP: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Toggle SMTP active status
     */
    public function toggleActive($rootValue, array $args)
    {
        try {
            $user = Auth::user();
            $id = $args['id'];
            $isActive = $args['is_active'];

            // Find and verify ownership
            $mailSetting = MailSetting::whereHas('companies', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            })->findOrFail($id);

            // Prevent deactivating default SMTP
            if ($mailSetting->is_default && !$isActive) {
                throw new \Exception('Cannot deactivate the default SMTP configuration. Please set another SMTP as default first.');
            }

            $mailSetting->update([
                'is_active' => $isActive,
                'updated_by' => $user->id,
            ]);

            return [
                'success' => true,
                'message' => 'SMTP configuration ' . ($isActive ? 'activated' : 'deactivated') . ' successfully.',
                'data' => $mailSetting->fresh(),
            ];
        } catch (\Exception $e) {
            Log::error('Error toggling SMTP active status: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to toggle SMTP status: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
