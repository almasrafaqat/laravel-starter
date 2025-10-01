<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;
use App\Models\PlanMeta;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        // Define plans
        $plans = [
            [
                'name' => 'Starter',
                'description' => 'Basic plan for small teams',
                'price' => 0,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_default' => true,
                'max_users' => 3,
                'max_projects' => 2,
                'features' => 'Basic Support,Limited Projects',
                'currency' => 'USD',
                'duration_days' => 30,
            ],
            [
                'name' => 'Pro',
                'description' => 'Pro plan for growing businesses',
                'price' => 49.99,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_default' => false,
                'max_users' => 10,
                'max_projects' => 10,
                'features' => 'Priority Support,Unlimited Projects',
                'currency' => 'USD',
                'duration_days' => 30,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Enterprise plan for large companies',
                'price' => 199.99,
                'billing_cycle' => 'yearly',
                'is_active' => true,
                'is_default' => false,
                'max_users' => 100,
                'max_projects' => 100,
                'features' => 'Dedicated Support,Custom Integrations',
                'currency' => 'USD',
                'duration_days' => 365,
            ],
            [
                'name' => 'Demo',
                'description' => 'Demo plan for testing',
                'price' => 0,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_default' => false,
                'max_users' => 1,
                'max_projects' => 1,
                'features' => 'Demo Only',
                'currency' => 'USD',
                'duration_days' => 7,
            ],
            [
                'name' => 'Free',
                'description' => 'Free forever plan',
                'price' => 0,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_default' => false,
                'max_users' => 1,
                'max_projects' => 1,
                'features' => 'Limited Support,Limited Projects',
                'currency' => 'USD',
                'duration_days' => 30,
            ],
            [
                'name' => 'Rest',
                'description' => 'Rest plan for special cases',
                'price' => 9.99,
                'billing_cycle' => 'monthly',
                'is_active' => false,
                'is_default' => false,
                'max_users' => 2,
                'max_projects' => 2,
                'features' => 'Basic Support',
                'currency' => 'USD',
                'duration_days' => 30,
            ],
        ];

        // Create plans and metas
        foreach ($plans as $planData) {
            $plan = Plan::updateOrCreate(
                ['name' => $planData['name']],
                $planData
            );

            // Add some metas for each plan
            $metas = [
                [
                    'meta_key' => 'storage_limit',
                    'meta_value' => $plan->name === 'Enterprise' ? '1TB' : '10GB',
                    'meta_data' => json_encode(['unit' => 'GB']),
                    'is_active' => true,
                    'is_default' => $plan->is_default,
                    'meta_order' => 1,
                    'meta_group' => 'limits',
                    'meta_type' => 'string',
                ],
                [
                    'meta_key' => 'support_level',
                    'meta_value' => $plan->name === 'Enterprise' ? 'dedicated' : 'standard',
                    'meta_data' => null,
                    'is_active' => true,
                    'is_default' => $plan->is_default,
                    'meta_order' => 2,
                    'meta_group' => 'support',
                    'meta_type' => 'string',
                ],
            ];

            foreach ($metas as $meta) {
                PlanMeta::updateOrCreate(
                    [
                        'plan_id' => $plan->id,
                        'meta_key' => $meta['meta_key'],
                    ],
                    array_merge($meta, ['plan_id' => $plan->id])
                );
            }
        }

        // Create a sample company
        $company = Company::firstOrCreate(
            ['name' => 'Demo Company'],
            [
                'email' => 'demo@company.com',
                'phone' => '1234567890',
                'address' => '123 Demo Street',
                'country' => 'US',
                'language' => 'en',
                'currency' => 'USD',
                'is_active' => true,
                'is_default' => true,
            ]
        );

        // Attach the "Starter" plan to the company
        $starterPlan = Plan::where('name', 'Starter')->first();
        if ($starterPlan && $company) {
            DB::table('company_plan')->updateOrInsert(
                [
                    'company_id' => $company->id,
                    'plan_id' => $starterPlan->id,
                ],
                [
                    'starts_at' => now(),
                    'ends_at' => now()->addDays($starterPlan->duration_days),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Attach a user to the company (first user found)
        $user = \App\Models\User::first();
        if ($user && $company) {
            DB::table('company_user')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'company_id' => $company->id,
                ],
                [
                    'role' => 'owner',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
