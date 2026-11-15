<?php

namespace Tests\Feature;

use App\Mail\CustomerWelcomeMail;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\LoyaltyCampaign;
use App\Models\LoyaltyCard;
use App\Models\Plan;
use App\Models\Shop;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoyaltyPlatformTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_public_loyalty_card_page_renders_and_returns_404_for_unknown_token(): void
    {
        $plan = Plan::create(['name' => 'Starter', 'price' => 29, 'status' => 'active']);
        $shop = Shop::create(['name' => 'Beanery', 'plan_id' => $plan->id, 'status' => 'active']);
        $customer = Customer::create(['name' => 'Omar', 'phone' => '+966511111111', 'status' => 'active']);
        $campaign = LoyaltyCampaign::create([
            'shop_id' => $shop->id,
            'name' => 'Coffee Club',
            'required_purchases_count' => 5,
            'reward_name' => 'Free Espresso',
            'status' => 'active',
        ]);
        $card = LoyaltyCard::create([
            'customer_id' => $customer->id,
            'shop_id' => $shop->id,
            'campaign_id' => $campaign->id,
            'token' => Str::random(32),
            'current_stamps_count' => 2,
            'reward_status' => 'not_ready',
        ]);

        $this->get(route('loyalty-card.show', $card->token))
            ->assertStatus(200)
            ->assertSee('Beanery')
            ->assertSee('Omar')
            ->assertSee('Free Espresso');

        $this->get(route('loyalty-card.show', 'non-existent-token'))
            ->assertStatus(404);

        $this->get(route('loyalty-card.manifest', $card->token))
            ->assertStatus(200)
            ->assertJsonPath('display', 'standalone');

        $this->get(route('loyalty-card.apple-payload', $card->token))
            ->assertStatus(200)
            ->assertJsonPath('serialNumber', $card->token);

        $pkpassResponse = $this->get(route('loyalty-card.apple-pass', $card->token))
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/vnd.apple.pkpass');

        $tmpZip = tempnam(sys_get_temp_dir(), 'pkpass_test_');
        file_put_contents($tmpZip, $pkpassResponse->getContent());
        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($tmpZip) === true);
        foreach (['pass.json', 'manifest.json', 'signature', 'icon.png', 'icon@2x.png', 'icon@3x.png', 'logo.png', 'logo@2x.png', 'logo@3x.png'] as $entry) {
            $this->assertNotFalse($zip->locateName($entry), "Missing {$entry} in .pkpass archive");
        }
        $this->assertNotEmpty($zip->getFromName('signature'));
        $zip->close();
        @unlink($tmpZip);

        $googleResponse = $this->get(route('loyalty-card.google-pass', $card->token))
            ->assertStatus(302);
        $redirectLocation = $googleResponse->headers->get('Location');
        $this->assertStringStartsWith('https://pay.google.com/gp/v/save/', $redirectLocation);
        $jwt = substr($redirectLocation, strlen('https://pay.google.com/gp/v/save/'));
        $this->assertCount(3, explode('.', $jwt));
    }

    public function test_language_switcher_updates_session_locale(): void
    {
        $this->get(route('lang.switch', 'ar'))
            ->assertRedirect()
            ->assertSessionHas('locale', 'ar');
    }

    public function test_shop_isolation_scope_prevents_cross_tenant_data_access(): void
    {
        $shopA = Shop::create(['name' => 'Shop A', 'status' => 'active']);
        $shopB = Shop::create(['name' => 'Shop B', 'status' => 'active']);

        LoyaltyCampaign::create([
            'shop_id' => $shopA->id,
            'name' => 'Campaign A',
            'required_purchases_count' => 5,
            'reward_name' => 'Reward A',
            'status' => 'active',
        ]);

        LoyaltyCampaign::create([
            'shop_id' => $shopB->id,
            'name' => 'Campaign B',
            'required_purchases_count' => 5,
            'reward_name' => 'Reward B',
            'status' => 'active',
        ]);

        $ownerA = User::factory()->create(['shop_id' => $shopA->id]);
        $ownerA->assignRole('Shop Owner');

        $this->actingAs($ownerA);
        $visibleToA = LoyaltyCampaign::pluck('name')->all();

        $this->assertSame(['Campaign A'], $visibleToA);
    }

    public function test_mobile_api_login_stamp_and_redeem_flow(): void
    {
        $shop = Shop::create(['name' => 'API Cafe', 'status' => 'active']);
        $cashier = User::factory()->create([
            'email' => 'cashier@apicafe.test',
            'password' => 'secret123',
            'shop_id' => $shop->id,
        ]);
        $cashier->assignRole('Cashier');

        $customer = Customer::create(['name' => 'Lina', 'phone' => '+966522222222', 'status' => 'active']);
        $campaign = LoyaltyCampaign::create([
            'shop_id' => $shop->id,
            'name' => '2-Stamp Promo',
            'required_purchases_count' => 2,
            'reward_name' => 'Free Cookie',
            'status' => 'active',
        ]);
        $card = LoyaltyCard::create([
            'customer_id' => $customer->id,
            'shop_id' => $shop->id,
            'campaign_id' => $campaign->id,
            'token' => Str::random(32),
            'current_stamps_count' => 0,
            'reward_status' => 'not_ready',
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'cashier@apicafe.test',
            'password' => 'secret123',
        ])->assertStatus(200);

        $bearer = $loginResponse->json('token');
        $this->assertNotEmpty($bearer);

        $this->withToken($bearer)
            ->postJson("/api/cards/{$card->token}/stamp")
            ->assertStatus(200)
            ->assertJsonPath('data.current_stamps_count', 1);

        $this->withToken($bearer)
            ->postJson("/api/cards/{$card->token}/stamp")
            ->assertStatus(200)
            ->assertJsonPath('data.reward_status', 'ready');

        $this->withToken($bearer)
            ->postJson("/api/cards/{$card->token}/redeem")
            ->assertStatus(200)
            ->assertJsonPath('data.current_stamps_count', 0)
            ->assertJsonPath('data.reward_status', 'not_ready');
    }

    public function test_shop_qr_scanner_page_renders_without_schema_type_error(): void
    {
        $shop = Shop::create(['name' => 'Scanner Cafe', 'status' => 'active']);
        $owner = User::factory()->create(['shop_id' => $shop->id]);
        $owner->assignRole('Shop Owner');

        $this->actingAs($owner)
            ->get("/shop/{$shop->id}/q-r-scanner")
            ->assertStatus(200)
            ->assertSee('POS Customer Lookup');

        $this->actingAs($owner)
            ->get("/shop/{$shop->id}/shop-qr-code")
            ->assertStatus(200)
            ->assertSee('كيف يعمل رمز QR لعملائك؟');
    }

    public function test_customer_can_join_shop_via_qr_landing_and_receives_arabic_cairo_welcome_email(): void
    {
        Mail::fake();

        $shop = Shop::create(['name' => 'Roast & Co Coffee', 'status' => 'active']);
        $campaign = LoyaltyCampaign::create([
            'shop_id' => $shop->id,
            'name' => 'VIP Stamp Card',
            'required_purchases_count' => 8,
            'reward_name' => 'Free Signature Latte',
            'status' => 'active',
        ]);

        $this->get(route('shop.join', $shop->id))
            ->assertStatus(200)
            ->assertSee('Roast & Co Coffee')
            ->assertSee('Free Signature Latte');

        $this->get(route('shop.join.poster', $shop->id))
            ->assertStatus(200)
            ->assertSee('Roast & Co Coffee')
            ->assertSee('Free Signature Latte');

        $response = $this->post(route('shop.join.store', $shop->id), [
            'name' => 'Fahad Al-Saud',
            'email' => 'fahad@example.test',
            'phone' => '+966559998877',
        ]);

        $customer = Customer::where('phone', '+966559998877')->firstOrFail();
        $card = LoyaltyCard::withoutGlobalScopes()
            ->where('customer_id', $customer->id)
            ->where('shop_id', $shop->id)
            ->firstOrFail();

        $response->assertRedirect(route('loyalty-card.show', ['token' => $card->token, 'joined' => 1]));

        Mail::assertSent(CustomerWelcomeMail::class, function (CustomerWelcomeMail $mail) use ($customer, $shop) {
            $html = $mail->render();

            return $mail->hasTo($customer->email)
                && str_contains($html, 'Cairo')
                && str_contains($html, 'ThreeDigits Loyalty')
                && str_contains($html, e($shop->name))
                && str_contains($html, e($customer->name));
        });
    }

    public function test_branch_creation_persists_manager_and_status_and_shop_logo_url_resolves_uploads(): void
    {
        $shop = Shop::create([
            'name' => 'Emerald Roasters',
            'logo' => 'shops/shop-emerald.png',
            'status' => 'active',
        ]);

        $this->assertStringEndsWith('/uploads/shops/shop-emerald.png', $shop->logo_url);

        $manager = User::factory()->create(['shop_id' => $shop->id]);
        $manager->assignRole('Branch Manager');

        $branch = Branch::create([
            'shop_id' => $shop->id,
            'name' => 'Olaya Branch',
            'address' => 'Olaya St, Riyadh',
            'phone' => '+966500001122',
            'manager_id' => $manager->id,
            'status' => 'inactive',
        ]);

        $this->assertSame($manager->id, $branch->fresh()->manager_id);
        $this->assertSame('inactive', $branch->fresh()->status);
        $this->assertTrue($branch->manager->is($manager));
    }

    public function test_admin_and_shop_filament_resources_render_successfully_in_arabic(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('Super Admin');

        $shop = Shop::create(['name' => 'QA Test Shop', 'status' => 'active']);
        $owner = User::factory()->create(['shop_id' => $shop->id]);
        $owner->assignRole('Shop Owner');

        foreach (['/admin', '/admin/plans', '/admin/shops', '/admin/subscriptions', '/admin/users'] as $adminUrl) {
            $this->actingAs($superAdmin)->get($adminUrl)->assertStatus(200);
        }

        foreach ([
            "/shop/{$shop->id}",
            "/shop/{$shop->id}/branches",
            "/shop/{$shop->id}/customers",
            "/shop/{$shop->id}/loyalty-campaigns",
            "/shop/{$shop->id}/loyalty-cards",
            "/shop/{$shop->id}/loyalty-transactions",
            "/shop/{$shop->id}/users",
        ] as $shopUrl) {
            $this->actingAs($owner)->get($shopUrl)->assertStatus(200);
        }
    }
}
