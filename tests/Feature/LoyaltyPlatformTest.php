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
        foreach (['pass.json', 'manifest.json', 'signature', 'icon.png', 'icon@2x.png', 'icon@3x.png', 'logo.png', 'logo@2x.png', 'logo@3x.png', 'strip.png', 'strip@2x.png', 'strip@3x.png'] as $entry) {
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
            ->assertSee('البحث عن بطاقة العميل');

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

        $this->assertSame('roast-co-coffee', $shop->slug);

        $this->get("/join/{$shop->id}")
            ->assertStatus(301)
            ->assertRedirect(route('shop.join', $shop->slug));

        $this->get(route('shop.join', $shop->slug))
            ->assertStatus(200)
            ->assertSee('Roast & Co Coffee')
            ->assertSee('Free Signature Latte');

        $this->get(route('shop.join.poster', $shop->slug))
            ->assertStatus(200)
            ->assertSee('Roast & Co Coffee')
            ->assertSee('Free Signature Latte');

        $response = $this->post(route('shop.join.store', $shop->slug), [
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
            "/shop/{$shop->id}/getting-started",
            "/shop/{$shop->id}/wallet-notifications",
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

    public function test_homepage_and_merchant_self_registration_and_funnel_tracking(): void
    {
        $starter = Plan::create(['name' => 'Starter', 'price' => 9, 'features' => ['1 Branch'], 'status' => 'active']);
        $business = Plan::create(['name' => 'Business', 'price' => 19, 'features' => ['Up to 5 Branches'], 'status' => 'active']);

        // Homepage in Arabic (default) & English: no /admin link, no Luxury SaaS copy, no crossed-out pricing
        $this->get('/')
            ->assertStatus(200)
            ->assertDontSee('href="' . url('/admin') . '"', false)
            ->assertDontSee('Luxury Digital Loyalty')
            ->assertDontSee('line-through')
            ->assertSee('خلّي زبائنك يرجعوا')
            ->assertSee('مقهى الياسمين')
            ->assertSee('31 ديسمبر 2026');

        $this->get('/ar')
            ->assertStatus(200)
            ->assertSee('lang="ar" dir="rtl"', false)
            ->assertSee('<link rel="canonical" href="' . route('home.ar') . '">', false)
            ->assertSee('<link rel="alternate" hreflang="en" href="' . route('home.en') . '">', false)
            ->assertSee('ابدأ مجاناً')
            ->assertSee(route('legal.privacy.ar'), false)
            ->assertSee(route('legal.terms.ar'), false)
            ->assertDontSee('pending publication');

        $this->get('/en')
            ->assertStatus(200)
            ->assertSee('lang="en" dir="ltr"', false)
            ->assertSee('<link rel="canonical" href="' . route('home.en') . '">', false)
            ->assertSee('<link rel="alternate" hreflang="ar" href="' . route('home.ar') . '">', false)
            ->assertSee('Digital loyalty stamp cards for cafés, restaurants, and salons.')
            ->assertSee('Customers join by scanning a QR code. Your team adds stamps at checkout. No app download required.')
            ->assertSee('Start free')
            ->assertSee('Jasmine Café')
            ->assertSee('December 31, 2026')
            ->assertSee('A product by Three Digit Software')
            ->assertSee(route('legal.privacy.en'), false)
            ->assertSee(route('legal.terms.en'), false)
            ->assertDontSee('pending publication');

        // Legal pages in AR and EN
        foreach (['/ar/privacy', '/en/privacy', '/ar/terms', '/en/terms'] as $legalUrl) {
            $this->get($legalUrl)->assertStatus(200);
        }

        // Language switch from /register preserves selected plan
        $this->from(route('merchant.register', ['plan' => 'business']))
            ->get(route('lang.switch', ['locale' => 'ar', 'plan' => 'business']))
            ->assertRedirect(route('merchant.register', ['plan' => 'business']));

        $this->get(route('merchant.register', ['plan' => 'business']))
            ->assertStatus(200)
            ->assertSee('أنشئ حساب متجري')
            ->assertSee(route('legal.privacy.ar'), false)
            ->assertSee(route('legal.terms.ar'), false);

        $this->get(route('lang.switch', 'en'));
        $this->get(route('merchant.register', ['plan' => 'business']))
            ->assertStatus(200)
            ->assertSee('Create my store account')
            ->assertSee(route('legal.privacy.en'), false)
            ->assertSee(route('legal.terms.en'), false);

        // Customer can join a shop with country code (+963 Syria default) + local digits (email optional)
        $phoneOnlyShop = Shop::create(['name' => 'No Email Cafe', 'status' => 'active']);
        $this->get(route('shop.join', $phoneOnlyShop->slug))
            ->assertStatus(200)
            ->assertSee('value="+963" selected', false)
            ->assertSee(route('legal.privacy.en'), false)
            ->assertSee(route('legal.terms.en'), false);

        $this->post(route('shop.join.store', $phoneOnlyShop->slug), [
            'name' => 'Invalid Phone User',
            'country_code' => '+963',
            'phone' => 'not-a-phone',
        ])->assertSessionHasErrors('phone');

        $this->post(route('shop.join.store', $phoneOnlyShop->slug), [
            'name' => 'Salma Hassan',
            'country_code' => '+963',
            'phone' => '0944112233',
        ])->assertRedirect();
        $this->assertDatabaseHas('customers', ['phone' => '+963944112233', 'email' => null]);

        // Funnel tracking endpoint
        $this->postJson(route('funnel.track'), [
            'event' => 'demo_completed',
            'properties' => ['email' => 'should-be-stripped@test.com'],
        ])->assertStatus(204);

        // Merchant registration retains selected plan and creates Shop + Subscription + Owner
        $this->get(route('merchant.register', ['plan' => 'business']))
            ->assertStatus(200)
            ->assertSee('Business');

        $response = $this->post(route('merchant.register.store'), [
            'shop_name' => 'Cedar Espresso Bar',
            'name' => 'Tariq Mansour',
            'email' => 'tariq@cedarespresso.test',
            'password' => 'StrongPass123!',
            'plan' => 'business',
        ]);

        $shop = Shop::where('slug', 'cedar-espresso-bar')->firstOrFail();
        $user = User::where('email', 'tariq@cedarespresso.test')->firstOrFail();

        $this->assertSame($business->id, $shop->plan_id);
        $this->assertSame($shop->id, $user->shop_id);
        $this->assertTrue($user->hasRole('Shop Owner'));
        $this->assertDatabaseHas('subscriptions', [
            'shop_id' => $shop->id,
            'plan_id' => $business->id,
            'ends_at' => '2026-12-31',
            'status' => 'active',
        ]);

        $response->assertRedirect("/shop/{$shop->id}/getting-started");
        $this->actingAs($user)->get("/shop/{$shop->id}/getting-started")->assertStatus(200);
    }

    public function test_apple_wallet_web_service_registers_lists_and_serves_updated_passes(): void
    {
        $shop = Shop::create(['name' => 'Push Cafe', 'status' => 'active']);
        $customer = Customer::create(['name' => 'Zaid Ali', 'phone' => '+963944556677', 'status' => 'active']);
        $campaign = LoyaltyCampaign::create([
            'shop_id' => $shop->id,
            'name' => 'Push Stamps',
            'required_purchases_count' => 6,
            'reward_name' => 'Free Latte',
            'status' => 'active',
        ]);
        $card = LoyaltyCard::create([
            'customer_id' => $customer->id,
            'shop_id' => $shop->id,
            'campaign_id' => $campaign->id,
            'token' => Str::random(32),
            'current_stamps_count' => 3,
            'reward_status' => 'not_ready',
        ]);

        $passType = config('services.wallet.apple_pass_type_id', 'pass.com.threedigitsoftware.loyalty');
        $deviceId = 'device-lib-12345';

        // Unauthorized without ApplePass token
        $this->postJson("/api/apple-wallet/v1/devices/{$deviceId}/registrations/{$passType}/{$card->token}", [
            'pushToken' => 'apns-token-abc',
        ])->assertStatus(401);

        // Register device pushToken
        $this->withHeaders(['Authorization' => "ApplePass {$card->token}"])
            ->postJson("/api/apple-wallet/v1/devices/{$deviceId}/registrations/{$passType}/{$card->token}", [
                'pushToken' => 'apns-token-abc',
            ])->assertStatus(201);

        $this->assertDatabaseHas('wallet_registrations', [
            'loyalty_card_id' => $card->id,
            'device_library_identifier' => $deviceId,
            'push_token' => 'apns-token-abc',
        ]);

        // List updated serialNumbers
        $this->getJson("/api/apple-wallet/v1/devices/{$deviceId}/registrations/{$passType}")
            ->assertStatus(200)
            ->assertJsonPath('serialNumbers.0', $card->token);

        // Fetch updated .pkpass
        $this->withHeaders(['Authorization' => "ApplePass {$card->token}"])
            ->get("/api/apple-wallet/v1/passes/{$passType}/{$card->token}")
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/vnd.apple.pkpass');

        // Unregister device
        $this->withHeaders(['Authorization' => "ApplePass {$card->token}"])
            ->deleteJson("/api/apple-wallet/v1/devices/{$deviceId}/registrations/{$passType}/{$card->token}")
            ->assertStatus(200);

        $this->assertDatabaseMissing('wallet_registrations', [
            'loyalty_card_id' => $card->id,
            'device_library_identifier' => $deviceId,
        ]);
    }

    public function test_landing_pages_render_canonical_hreflang_and_localized_ctas(): void
    {
        Plan::create(['name' => 'Starter', 'price' => 19, 'status' => 'active', 'features' => ['1 Branch']]);

        $arResponse = $this->get(route('home.ar'))
            ->assertStatus(200)
            ->assertSee('<link rel="canonical" href="' . route('home.ar') . '">', false)
            ->assertSee('<link rel="alternate" hreflang="ar" href="' . route('home.ar') . '">', false)
            ->assertSee('<link rel="alternate" hreflang="en" href="' . route('home.en') . '">', false)
            ->assertSee('جرّب البطاقة')
            ->assertSee('ابدأ مجاناً مع البداية')
            ->assertSee('31 ديسمبر 2026')
            ->assertDontSee('31 كانون الأول 2026');

        $this->assertSame(200, $arResponse->getStatusCode());

        $this->get(route('home.en'))
            ->assertStatus(200)
            ->assertSee('<link rel="canonical" href="' . route('home.en') . '">', false)
            ->assertSee('<link rel="alternate" hreflang="ar" href="' . route('home.ar') . '">', false)
            ->assertSee('<link rel="alternate" hreflang="en" href="' . route('home.en') . '">', false)
            ->assertSee('Start free with Starter');
    }

    public function test_merchant_register_validation_is_localized_and_accessible(): void
    {
        Plan::create(['name' => 'Starter', 'price' => 19, 'status' => 'active']);

        $arRes = $this->withSession(['locale' => 'ar'])
            ->followingRedirects()
            ->from(route('merchant.register', ['plan' => 'starter']))
            ->post(route('merchant.register.store'), [
                'shop_name' => '',
                'name' => '',
                'email' => 'not-an-email',
                'password' => '123',
                'plan' => 'starter',
            ]);

        $arRes->assertStatus(200)
            ->assertSee('يرجى إدخال اسم المتجر.')
            ->assertSee('يرجى إدخال اسمك الكامل.')
            ->assertSee('يرجى إدخال بريد إلكتروني صحيح.')
            ->assertSee('يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.')
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('aria-describedby="shop_name-error"', false);

        $enRes = $this->withSession(['locale' => 'en'])
            ->followingRedirects()
            ->from(route('merchant.register', ['plan' => 'starter']))
            ->post(route('merchant.register.store'), [
                'shop_name' => '',
                'name' => '',
                'email' => '',
                'password' => '',
                'plan' => 'starter',
            ]);

        $enRes->assertStatus(200)
            ->assertSee('Please enter your store name.')
            ->assertSee('Please enter your full name.')
            ->assertSee('Please enter your email address.')
            ->assertSee('Please enter a password.');
    }

    public function test_shop_login_uses_start_free_cta_and_language_switch(): void
    {
        $this->get('/shop/login?lang=ar')
            ->assertStatus(200)
            ->assertSee('ابدأ مجاناً')
            ->assertDontSee('أنشئ بطاقة متجرك')
            ->assertSee('lang=en', false);

        $this->get('/shop/login?lang=en')
            ->assertStatus(200)
            ->assertSee('Start free')
            ->assertDontSee('Create your store’s card')
            ->assertSee('lang=ar', false);
    }
}
