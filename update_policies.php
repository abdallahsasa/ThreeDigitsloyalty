<?php

$policies = [
    // Shop Models (Tenant-aware)
    'app/Policies/BranchPolicy.php' => <<<'PHP'
<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Shop Owner');
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $branch->shop_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Shop Owner');
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $branch->shop_id;
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $branch->shop_id;
    }
}
PHP,
    'app/Policies/CustomerPolicy.php' => <<<'PHP'
<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function view(User $user, Customer $customer): bool
    {
        // Actually Customer is global, but relation via LoyaltyCards defines if shop has access.
        // For simplicity, if they can viewAny, they can view.
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->hasRole('Shop Owner');
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasRole('Shop Owner');
    }
}
PHP,
    'app/Policies/LoyaltyCampaignPolicy.php' => <<<'PHP'
<?php

namespace App\Policies;

use App\Models\LoyaltyCampaign;
use App\Models\User;

class LoyaltyCampaignPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Shop Owner');
    }

    public function view(User $user, LoyaltyCampaign $loyaltyCampaign): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $loyaltyCampaign->shop_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Shop Owner');
    }

    public function update(User $user, LoyaltyCampaign $loyaltyCampaign): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $loyaltyCampaign->shop_id;
    }

    public function delete(User $user, LoyaltyCampaign $loyaltyCampaign): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $loyaltyCampaign->shop_id;
    }
}
PHP,
    'app/Policies/LoyaltyCardPolicy.php' => <<<'PHP'
<?php

namespace App\Policies;

use App\Models\LoyaltyCard;
use App\Models\User;

class LoyaltyCardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function view(User $user, LoyaltyCard $loyaltyCard): bool
    {
        return ($user->hasRole('Shop Owner') || $user->hasRole('Cashier')) && $user->shop_id === $loyaltyCard->shop_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function update(User $user, LoyaltyCard $loyaltyCard): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $loyaltyCard->shop_id;
    }

    public function delete(User $user, LoyaltyCard $loyaltyCard): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $loyaltyCard->shop_id;
    }
}
PHP,
    'app/Policies/LoyaltyTransactionPolicy.php' => <<<'PHP'
<?php

namespace App\Policies;

use App\Models\LoyaltyTransaction;
use App\Models\User;

class LoyaltyTransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function view(User $user, LoyaltyTransaction $loyaltyTransaction): bool
    {
        return ($user->hasRole('Shop Owner') || $user->hasRole('Cashier')) && $user->shop_id === $loyaltyTransaction->shop_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Shop Owner') || $user->hasRole('Cashier');
    }

    public function update(User $user, LoyaltyTransaction $loyaltyTransaction): bool
    {
        return false; // Transactions are immutable
    }

    public function delete(User $user, LoyaltyTransaction $loyaltyTransaction): bool
    {
        return false; // Transactions are immutable
    }
}
PHP,

    // Admin Models
    'app/Policies/PlanPolicy.php' => <<<'PHP'
<?php

namespace App\Policies;

use App\Models\Plan;
use App\Models\User;

class PlanPolicy
{
    public function viewAny(User $user): bool { return false; }
    public function view(User $user, Plan $plan): bool { return false; }
    public function create(User $user): bool { return false; }
    public function update(User $user, Plan $plan): bool { return false; }
    public function delete(User $user, Plan $plan): bool { return false; }
}
PHP,
    'app/Policies/ShopPolicy.php' => <<<'PHP'
<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;

class ShopPolicy
{
    public function viewAny(User $user): bool { return false; }
    public function view(User $user, Shop $shop): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $shop->id;
    }
    public function create(User $user): bool { return false; }
    public function update(User $user, Shop $shop): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $shop->id;
    }
    public function delete(User $user, Shop $shop): bool { return false; }
}
PHP,
    'app/Policies/SubscriptionPolicy.php' => <<<'PHP'
<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function viewAny(User $user): bool { return false; }
    public function view(User $user, Subscription $subscription): bool
    {
        return $user->hasRole('Shop Owner') && $user->shop_id === $subscription->shop_id;
    }
    public function create(User $user): bool { return false; }
    public function update(User $user, Subscription $subscription): bool { return false; }
    public function delete(User $user, Subscription $subscription): bool { return false; }
}
PHP,
];

foreach ($policies as $file => $content) {
    if (file_exists($file)) {
        file_put_contents($file, $content);
        echo "Updated $file\n";
    } else {
        echo "Missing $file\n";
    }
}
