<?php

$files = [
    'app/Filament/Resources/Shops/Schemas/ShopForm.php' => [
        'field' => 'logo',
        'dir' => 'shops',
        'prefix' => 'shop',
    ],
    'app/Filament/Shop/Resources/Customers/Schemas/CustomerForm.php' => [
        'field' => 'avatar',
        'dir' => 'customers',
        'prefix' => 'customer',
    ],
    'app/Filament/Shop/Resources/Branches/Schemas/BranchForm.php' => [
        'field' => 'image',
        'dir' => 'branches',
        'prefix' => 'branch',
    ],
    'app/Filament/Shop/Resources/LoyaltyCampaigns/Schemas/LoyaltyCampaignForm.php' => [
        'field' => 'image',
        'dir' => 'campaigns',
        'prefix' => 'campaign',
    ]
];

foreach ($files as $file => $config) {
    if (!file_exists($file)) continue;

    $content = file_get_contents($file);

    // Add import if missing
    if (strpos($content, 'use Filament\Forms\Components\FileUpload;') === false) {
        $content = str_replace('use Filament\Forms\Components\TextInput;', "use Filament\Forms\Components\FileUpload;\nuse Filament\Forms\Components\TextInput;", $content);
    }

    $field = $config['field'];
    $dir = $config['dir'];
    $prefix = $config['prefix'];

    // Replace old TextInput for the field if it exists, or just FileUpload if it was partially updated
    $searchTextInput = "TextInput::make('$field')";
    $searchFileUpload = "FileUpload::make('$field')";

    $replacement = "FileUpload::make('$field')
                    ->disk('uploads')
                    ->directory('$dir')
                    ->image()
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->getUploadedFileNameForStorageUsing(
                        fn (\Illuminate\Http\UploadedFile \$file): string => '$prefix-' . time() . '-' . \Illuminate\Support\Str::random(10) . '.' . \$file->extension()
                    )";

    if (strpos($content, $searchFileUpload) !== false && strpos($content, 'disk(\'uploads\')') !== false) {
        // already done partially, let's fix it if we can, actually let's use regex to replace the whole FileUpload block
        $content = preg_replace("/FileUpload::make\('$field'\).*?getUploadedFileNameForStorageUsing\([^)]+\)\s*\),/s", $replacement . ',', $content);
    } else if (strpos($content, $searchTextInput) !== false) {
        // replace TextInput with FileUpload
        $content = preg_replace("/TextInput::make\('$field'\)(?:[^\n]+)?(,\n|;)/", $replacement . "$1", $content);
    }

    file_put_contents($file, $content);
    echo "Updated $file\n";
}
