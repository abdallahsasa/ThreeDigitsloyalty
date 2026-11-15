<?php

$replacements = [
    'app/Filament/Resources/Plans/PlanResource.php' => "'heroicon-o-clipboard-document-list'",
    'app/Filament/Resources/Shops/ShopResource.php' => "'heroicon-o-building-storefront'",
    'app/Filament/Resources/Subscriptions/SubscriptionResource.php' => "'heroicon-o-credit-card'",
    'app/Filament/Resources/Users/UserResource.php' => "'heroicon-o-users'",
    'app/Filament/Shop/Resources/Customers/CustomerResource.php' => "'heroicon-o-user-group'",
    'app/Filament/Shop/Resources/LoyaltyCampaigns/LoyaltyCampaignResource.php' => "'heroicon-o-megaphone'",
    'app/Filament/Shop/Resources/Users/UserResource.php' => "'heroicon-o-user-circle'",
    'app/Filament/Shop/Resources/Branches/BranchResource.php' => "'heroicon-o-map-pin'",
    'app/Filament/Shop/Resources/LoyaltyTransactions/LoyaltyTransactionResource.php' => "'heroicon-o-arrows-right-left'",
    'app/Filament/Shop/Resources/LoyaltyCards/LoyaltyCardResource.php' => "'heroicon-o-identification'",
];

foreach ($replacements as $file => $newIcon) {
    if (!file_exists($file)) continue;

    $content = file_get_contents($file);
    // Replace the default icon
    $content = str_replace('Heroicon::OutlinedRectangleStack', $newIcon, $content);
    
    file_put_contents($file, $content);
    echo "Updated icon for $file\n";
}
