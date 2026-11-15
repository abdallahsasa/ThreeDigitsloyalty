<?php

$models = [
    'app/Models/Shop.php',
    'app/Models/Customer.php',
    'app/Models/LoyaltyCard.php',
    'app/Models/Branch.php',
];

$useStatement = 'use Illuminate\Database\Eloquent\SoftDeletes;';

foreach ($models as $file) {
    if (!file_exists($file)) continue;

    $content = file_get_contents($file);

    if (str_contains($content, 'SoftDeletes')) {
        continue;
    }

    $content = preg_replace(
        '/(use Illuminate\\\\Database\\\\Eloquent\\\\Model;)/',
        "$1\n$useStatement",
        $content
    );

    $content = preg_replace(
        '/(class [A-Z][a-zA-Z0-9]+ extends Model\n\{)\s*(use HasFactory;)/s',
        "$1\n    use HasFactory, SoftDeletes;",
        $content
    );

    file_put_contents($file, $content);
    echo "Added SoftDeletes to $file\n";
}
