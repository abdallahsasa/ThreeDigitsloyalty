# ThreeDigits Loyalty — Application Documentation

## 1. What Is This App & What Is Its Purpose?

**ThreeDigits Loyalty** is a **multi-tenant SaaS digital loyalty card platform** built with **Laravel** and **Filament**.

Its purpose is to help retail shops, cafés, restaurants, and multi-branch businesses replace traditional paper punch cards with **QR-code-based digital stamp & reward cards**. Shop staff (cashiers and owners) scan a customer's QR code—or look up the customer by phone number—to issue purchase stamps and redeem unlocked rewards in real time, while platform administrators manage merchant subscriptions and pricing plans.

---

## 2. Tech Stack

| Layer | Technology |
| :--- | :--- |
| **Language / Runtime** | PHP `^8.3` |
| **Backend Framework** | Laravel `^13.8` |
| **Admin & Merchant UI** | Filament `^5.6` (multi-panel) |
| **Authorization (RBAC)** | `spatie/laravel-permission` `^8.1` + Laravel Policies |
| **API Authentication** | `laravel/sanctum` `^4.0` |
| **QR Code Generation** | `simplesoftwareio/simple-qrcode` `^4.2` |
| **Frontend / Styling** | Tailwind CSS `^4.0`, Vite `^8.0`, Blade |
| **Localization** | English (`en`, LTR) and Arabic (`ar`, RTL) |

---

## 3. Architecture & Multi-Tenancy

The application is structured around **two distinct Filament panels** and **strict tenant isolation**:

### Panels
1. **Platform Admin Panel (`/admin`)** — `App\Providers\Filament\AdminPanelProvider`
   - Accessible only to users with the `Super Admin` role (`User::canAccessPanel()`).
   - Manages SaaS **Plans**, **Shops**, **Subscriptions**, and **Users**.
   - Includes platform-wide analytics widgets (`AdminStatsOverview`, `AdminShopsChart`, `AdminSubscriptionsChart`, `AdminLatestShops`).

2. **Merchant / Shop Panel (`/shop`)** — `App\Providers\Filament\ShopPanelProvider`
   - Configured as a Filament multi-tenant panel bound to `App\Models\Shop`.
   - Each shop operates inside its own isolated workspace to manage **Branches**, **Customers**, **Loyalty Campaigns**, **Loyalty Cards**, **Loyalty Transactions**, and **Shop Staff**.
   - Includes a dedicated **QR Scanner** page (`App\Filament\Shop\Pages\QRScanner`) and shop/cashier widgets (`ShopOverview`, `ShopPurchasesChart`, `ShopCustomerGrowthChart`, `ShopRecentTransactions`, `CashierDashboardWidget`).

### Tenant Isolation
- **Global Eloquent Scope**: `App\Models\Scopes\ShopIsolationScope` is attached via `#[ScopedBy([ShopIsolationScope::class])]` to `Branch`, `Subscription`, `LoyaltyCampaign`, `LoyaltyCard`, and `LoyaltyTransaction`. Any authenticated non-`Super Admin` user with a `shop_id` automatically has queries filtered to their `shop_id`.
- **Panel Tenant Check**: `User::canAccessTenant()` verifies `$this->shop_id === $tenant->id`.
- **Policies**: Resource access is enforced via `app/Policies/*` (`BranchPolicy`, `CustomerPolicy`, `LoyaltyCampaignPolicy`, `LoyaltyCardPolicy`, `LoyaltyTransactionPolicy`, `PlanPolicy`, `ShopPolicy`, `SubscriptionPolicy`, `UserPolicy`).

---

## 4. Roles & Permissions

Defined in `database/seeders/RolesAndPermissionsSeeder.php`:

| Role | Scope & Responsibilities |
| :--- | :--- |
| **Super Admin** | Full access to `/admin`; manages SaaS plans, shops, subscriptions, and users. |
| **Shop Owner** | Full access to their shop's `/shop` tenant: branches, staff, campaigns, cards, customers, and transactions. |
| **Branch Manager** | Shop-level staff role assigned to a shop/branch. |
| **Cashier** | Operates the `/shop` QR Scanner, views/issues loyalty cards, adds purchase stamps, redeems rewards, and sees daily metrics in `CashierDashboardWidget`. |

---

## 5. Core Domain Model & Reward Lifecycle

### Key Entities
- **`Plan`**: SaaS subscription tier (`name`, `price`, `features` JSON, `status`).
- **`Shop`**: Merchant tenant (`name`, `logo`, `phone`, `email`, `address`, `country`, `city`, `status`, `plan_id`, `default_language`, soft deletes).
- **`Subscription`**: Tracks a shop's active/expired/cancelled plan period (`shop_id`, `plan_id`, `starts_at`, `ends_at`, `status`).
- **`Branch`**: Physical store locations belonging to a `Shop` (soft deletes).
- **`Customer`**: End-user profile (`name`, `phone` unique, `email`, `birthday`, `gender`, `language`, `status`, `avatar`, soft deletes).
- **`LoyaltyCampaign`**: Reward rule for a shop (`name`, `type` defaulting to `stamps`, `required_purchases_count`, `reward_name`, `reward_description`, `start_date`, `end_date`, `status`).
- **`LoyaltyCard`**: Customer's card for a specific shop & campaign (`customer_id`, `shop_id`, `campaign_id`, unique `token` [64 chars], `current_stamps_count`, `reward_status` [`not_ready`, `ready`, `redeemed`, `expired`], `last_activity_date`, soft deletes).
- **`LoyaltyTransaction`**: Immutable audit log of card activity (`customer_id`, `shop_id`, `branch_id`, `cashier_id`, `loyalty_card_id`, `type` [`purchase`, `reward_unlocked`, `reward_redeemed`, `manual_adjustment`], `points_change`, `notes`).

### Stamp & Redemption Flow (`App\Services\RewardLogicService`)
All stamp and redemption operations run inside `DB::transaction(...)`:

1. **Adding a Purchase (`addPurchase`)**:
   - Rejects the operation if `reward_status === 'ready'` (must redeem first).
   - Increments `current_stamps_count` by `1` and updates `last_activity_date`.
   - If `current_stamps_count >= campaign->required_purchases_count`, sets `reward_status = 'ready'` and records a `reward_unlocked` transaction; otherwise records a `purchase` transaction.
2. **Redeeming a Reward (`redeemReward`)**:
   - Rejects the operation if `reward_status !== 'ready'`.
   - Resets `current_stamps_count` to `0`, sets `reward_status = 'not_ready'`, updates `last_activity_date`, and records a `reward_redeemed` transaction.

### Cashier Scanning Flow (`App\Filament\Shop\Pages\QRScanner`)
Cashiers can look up a customer's `LoyaltyCard` (scoped to the current shop tenant) either by:
- Scanning/entering the card's **`token`**, or
- Searching by the customer's **`phone`** number.

Once loaded, the cashier can trigger **Add Purchase (Stamp)** or **Redeem Reward** actions with confirmation dialogs.

---

## 6. Routes & Public / API Surfaces

### Web Routes (`routes/web.php`)
- `GET /` — Public SaaS landing page displaying active pricing plans (`resources/views/welcome.blade.php` and `resources/views/components/landing/*`).
- `GET /card/{token}` (`loyalty-card.show`) — Public digital loyalty card page for customers (`LoyaltyCardController@show` → `resources/views/loyalty-card/show.blade.php`), displaying their QR code, shop, campaign progress, and reward status.
- `GET /lang/{locale}` (`lang.switch`) — Switches session locale between `en` (LTR) and `ar` (RTL).

### API Routes (`routes/api.php`)
Sanctum-authenticated mobile API (`AuthController`, `LoyaltyCardApiController`):
- `POST /api/login` — Authenticates a user and issues a Sanctum bearer token.
- `GET /api/cards/{token}` — Public token lookup returning card progress, shop, campaign, and recent transactions.
- `POST /api/logout` — Revokes the current Sanctum token (`auth:sanctum`).
- `GET /api/user` — Returns the authenticated user with shop, branch, and roles (`auth:sanctum`).
- `GET /api/cards` — Lists tenant-scoped loyalty cards, filterable by `?phone=` or `?shop_id=` (`auth:sanctum`).
- `POST /api/cards/{token}/stamp` — Adds a purchase stamp via `RewardLogicService` (`auth:sanctum`, shop staff).
- `POST /api/cards/{token}/redeem` — Redeems an unlocked reward via `RewardLogicService` (`auth:sanctum`, shop staff).

---

## 7. Local Setup & Commands

```bash
# Initial setup (installs PHP & JS deps, creates .env, generates key, runs migrations, builds assets)
composer setup

# Run local dev stack (Laravel server, queue listener, Pail logs, Vite)
composer dev

# Run test suite
composer test
```

### Default Seeded Super Admin (`RolesAndPermissionsSeeder`)
- **URL**: `/admin`
- **Email**: `admin@threedigits.com`
- **Password**: `password`
