<?php

namespace App\Filament\Shop\Pages;

use App\Models\Shop;
use Filament\Facades\Filament;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CardBranding extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.shop.pages.card-branding';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return (bool) ($user && ($user->hasRole('Super Admin') || $user->hasRole('Shop Owner') || $user->hasRole('Branch Manager')));
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'تصميم البطاقة والشعار' : 'Card Design & Logo';
    }

    public function getTitle(): string
    {
        return app()->getLocale() === 'ar' ? 'تصميم بطاقة الولاء والشعار' : 'Loyalty Card Design & Logo';
    }

    public function mount(): void
    {
        /** @var Shop|null $shop */
        $shop = Filament::getTenant();

        $this->form->fill([
            'category' => $shop?->resolvedCategory() ?? 'cafe',
            'logo' => $shop?->logo,
            'card_bg_color' => $shop?->resolvedCardBgColor() ?? '#743EE2',
            'card_accent_color' => $shop?->resolvedCardAccentColor() ?? '#3B42D5',
            'card_text_color' => $shop?->resolvedCardTextColor() ?? '#FFFFFF',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        $isAr = app()->getLocale() === 'ar';

        return $schema
            ->statePath('data')
            ->components([
                Select::make('category')
                    ->label($isAr ? 'تصنيف النشاط (يحدد أيقونة البوستر المطبوع)' : 'Store Category (sets your printed QR poster icon)')
                    ->options(Shop::categoryOptions($isAr ? 'ar' : 'en'))
                    ->default('cafe')
                    ->live()
                    ->required(),

                FileUpload::make('logo')
                    ->label($isAr ? 'شعار المتجر (يظهر على بطاقة العميل ومحفظة الهاتف)' : 'Store Logo (shown on customer card & phone wallet)')
                    ->disk('uploads')
                    ->directory('shops')
                    ->image()
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->getUploadedFileNameForStorageUsing(
                        fn (\Illuminate\Http\UploadedFile $file): string => 'shop-' . time() . '-' . Str::random(10) . '.' . $file->extension()
                    )
                    ->helperText($isAr ? 'ارفع شعاراً مربعاً واضحاً (PNG أو WebP). سيظهر بجانب شعار ThreeDigits Loyalty.' : 'Upload a clear square logo (PNG or WebP). Displayed alongside the ThreeDigits Loyalty logo.'),

                ColorPicker::make('card_bg_color')
                    ->label($isAr ? 'اللون الأساسي للبطاقة' : 'Card Primary Color')
                    ->default('#743EE2')
                    ->live()
                    ->required(),

                ColorPicker::make('card_accent_color')
                    ->label($isAr ? 'لون التدرج والأختام' : 'Accent & Stamp Color')
                    ->default('#3B42D5')
                    ->live()
                    ->required(),

                ColorPicker::make('card_text_color')
                    ->label($isAr ? 'لون النص في رأس البطاقة' : 'Header Text Color')
                    ->default('#FFFFFF')
                    ->live()
                    ->required(),
            ]);
    }

    public function applyPreset(string $bg, string $accent, string $text): void
    {
        $this->data['card_bg_color'] = $bg;
        $this->data['card_accent_color'] = $accent;
        $this->data['card_text_color'] = $text;
    }

    public function save(): void
    {
        /** @var Shop|null $shop */
        $shop = Filament::getTenant();
        if (! $shop) {
            return;
        }

        $state = $this->form->getState();

        $category = strtolower(trim((string) ($state['category'] ?? 'cafe')));
        if (! in_array($category, Shop::CATEGORIES, true)) {
            $category = 'cafe';
        }

        $shop->update([
            'category' => $category,
            'logo' => $state['logo'] ?? null,
            'card_bg_color' => Shop::sanitizeHexColor($state['card_bg_color'] ?? null, '#743EE2'),
            'card_accent_color' => Shop::sanitizeHexColor($state['card_accent_color'] ?? null, '#3B42D5'),
            'card_text_color' => Shop::sanitizeHexColor($state['card_text_color'] ?? null, '#FFFFFF'),
        ]);

        Notification::make()
            ->title(app()->getLocale() === 'ar' ? 'تم حفظ تصميم البطاقة والشعار بنجاح' : 'Card design and logo saved')
            ->success()
            ->send();
    }
}
