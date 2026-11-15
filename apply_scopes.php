<?php

$models = [
    'app/Models/Branch.php',
    'app/Models/LoyaltyCampaign.php',
    'app/Models/LoyaltyCard.php',
    'app/Models/LoyaltyTransaction.php',
    'app/Models/Subscription.php',
];

$useStatements = <<<PHP
use App\Models\Scopes\ShopIsolationScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
PHP;

foreach ($models as $file) {
    if (!file_exists($file)) continue;

    $content = file_get_contents($file);

    // Skip if already scoped
    if (str_contains($content, 'ShopIsolationScope::class')) {
        continue;
    }

    // Add use statements
    $content = preg_replace(
        '/(use Illuminate\\\\Database\\\\Eloquent\\\\Model;)/',
        "$1\n$useStatements",
        $content
    );

    // Add attribute
    $content = preg_replace(
        '/(class [A-Z][a-zA-Z0-9]+ extends Model)/',
        "#[ScopedBy([ShopIsolationScope::class])]\n$1",
        $content
    );

    file_put_contents($file, $content);
    echo "Applied scope to $file\n";
}
