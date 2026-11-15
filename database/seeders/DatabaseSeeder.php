<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        // Seed default plans
        \App\Models\Plan::updateOrCreate(
            ['name' => 'Starter'],
            [
                'price' => 29.00,
                'features' => ['1 Branch', 'Up to 500 Customers', '1 Active Campaign'],
                'status' => 'active',
            ]
        );

        \App\Models\Plan::updateOrCreate(
            ['name' => 'Business'],
            [
                'price' => 79.00,
                'features' => ['Up to 5 Branches', 'Unlimited Customers', 'Unlimited Campaigns', 'Advanced Analytics'],
                'status' => 'active',
            ]
        );

        \App\Models\Plan::updateOrCreate(
            ['name' => 'Enterprise'],
            [
                'price' => 199.00, // Or custom
                'features' => ['Unlimited Branches', 'Custom Integrations', 'Dedicated Support'],
                'status' => 'active',
            ]
        );

        $starterPlan = \App\Models\Plan::where('name', 'Starter')->first();
        $businessPlan = \App\Models\Plan::where('name', 'Business')->first();
        $enterprisePlan = \App\Models\Plan::where('name', 'Enterprise')->first();

        $dummyShops = [
            [
                'name' => 'Roast & Co Coffee',
                'email' => 'hello@roastandco.test',
                'phone' => '+966501112233',
                'city' => 'Riyadh',
                'country' => 'Saudi Arabia',
                'plan_id' => $businessPlan?->id,
                'default_language' => 'en',
                'status' => 'active',
            ],
            [
                'name' => 'Bite Burger House',
                'email' => 'contact@biteburger.test',
                'phone' => '+966502223344',
                'city' => 'Jeddah',
                'country' => 'Saudi Arabia',
                'plan_id' => $businessPlan?->id,
                'default_language' => 'ar',
                'status' => 'active',
            ],
            [
                'name' => 'Aura Beauty Salon',
                'email' => 'booking@aurasalon.test',
                'phone' => '+966503334455',
                'city' => 'Dubai',
                'country' => 'UAE',
                'plan_id' => $starterPlan?->id,
                'default_language' => 'en',
                'status' => 'active',
            ],
            [
                'name' => 'Pulse Fitness Club',
                'email' => 'info@pulsefitness.test',
                'phone' => '+966504445566',
                'city' => 'Dammam',
                'country' => 'Saudi Arabia',
                'plan_id' => $enterprisePlan?->id,
                'default_language' => 'en',
                'status' => 'active',
            ],
            [
                'name' => 'Crumb Artisan Bakery',
                'email' => 'orders@crumbbakery.test',
                'phone' => '+966505556677',
                'city' => 'Riyadh',
                'country' => 'Saudi Arabia',
                'plan_id' => $starterPlan?->id,
                'default_language' => 'ar',
                'status' => 'active',
            ],
            [
                'name' => 'GreenLeaf Pharmacy',
                'email' => 'care@greenleaf.test',
                'phone' => '+966506667788',
                'city' => 'Amman',
                'country' => 'Jordan',
                'plan_id' => $businessPlan?->id,
                'default_language' => 'ar',
                'status' => 'active',
            ],
        ];

        foreach ($dummyShops as $index => $shopData) {
            $shop = \App\Models\Shop::updateOrCreate(
                ['name' => $shopData['name']],
                $shopData
            );

            $branch = \App\Models\Branch::firstOrCreate(
                ['shop_id' => $shop->id, 'name' => 'Main Branch'],
                ['phone' => $shopData['phone'], 'status' => 'active']
            );

            $campaign = \App\Models\LoyaltyCampaign::firstOrCreate(
                ['shop_id' => $shop->id, 'name' => 'VIP Stamp Card'],
                [
                    'type' => 'stamps',
                    'required_purchases_count' => 8,
                    'reward_name' => 'Free Signature Item',
                    'status' => 'active',
                ]
            );

            if ($index === 0) {
                $owner = User::firstOrCreate(
                    ['email' => 'owner@roastandco.test'],
                    [
                        'name' => 'Roast Owner',
                        'password' => \Illuminate\Support\Facades\Hash::make('password'),
                        'shop_id' => $shop->id,
                        'branch_id' => $branch->id,
                        'email_verified_at' => now(),
                    ]
                );
                $owner->assignRole('Shop Owner');

                $customer1 = \App\Models\Customer::firstOrCreate(
                    ['phone' => '+966501000001'],
                    ['name' => 'Ahmed Al-Qahtani', 'email' => 'ahmed@example.test', 'language' => 'ar', 'status' => 'active']
                );
                $customer2 = \App\Models\Customer::firstOrCreate(
                    ['phone' => '+966501000002'],
                    ['name' => 'Sara Al-Otaibi', 'email' => 'sara@example.test', 'language' => 'en', 'status' => 'active']
                );

                \App\Models\LoyaltyCard::updateOrCreate(
                    ['token' => 'demo-roast-card-1'],
                    [
                        'customer_id' => $customer1->id,
                        'shop_id' => $shop->id,
                        'campaign_id' => $campaign->id,
                        'current_stamps_count' => 5,
                        'reward_status' => 'not_ready',
                        'last_activity_date' => now()->subHours(3),
                    ]
                );

                \App\Models\LoyaltyCard::updateOrCreate(
                    ['token' => 'demo-roast-reward-ready'],
                    [
                        'customer_id' => $customer2->id,
                        'shop_id' => $shop->id,
                        'campaign_id' => $campaign->id,
                        'current_stamps_count' => 8,
                        'reward_status' => 'ready',
                        'last_activity_date' => now()->subHour(),
                    ]
                );
            }

            if ($index === 1) {
                $customer3 = \App\Models\Customer::firstOrCreate(
                    ['phone' => '+966501000003'],
                    ['name' => 'Khalid Al-Harbi', 'email' => 'khalid@example.test', 'language' => 'ar', 'status' => 'active']
                );

                \App\Models\LoyaltyCard::updateOrCreate(
                    ['token' => 'demo-burger-card-1'],
                    [
                        'customer_id' => $customer3->id,
                        'shop_id' => $shop->id,
                        'campaign_id' => $campaign->id,
                        'current_stamps_count' => 3,
                        'reward_status' => 'not_ready',
                        'last_activity_date' => now()->subDay(),
                    ]
                );
            }
        }
    }
}
