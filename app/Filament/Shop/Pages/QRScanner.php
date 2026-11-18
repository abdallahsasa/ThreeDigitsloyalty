<?php

namespace App\Filament\Shop\Pages;

use App\Models\Customer;
use App\Models\LoyaltyCard;
use App\Services\RewardLogicService;
use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class QRScanner extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-qr-code';

    protected string $view = 'filament.shop.pages.q-r-scanner';

    public static function getNavigationLabel(): string
    {
        return __('QR Scanner');
    }

    public function getTitle(): string
    {
        return __('QR Scanner');
    }

    public ?string $token = null;

    public ?string $phone = null;

    public ?LoyaltyCard $scannedCard = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('token')
                    ->label(__('Scan QR Code'))
                    ->autofocus()
                    ->placeholder('e.g. sKSwXjvQ8M...')
                    ->helperText(__('Click here and scan the customer QR code.'))
                    ->suffixAction(
                        Action::make('searchByToken')
                            ->icon('heroicon-m-magnifying-glass')
                            ->action(fn () => $this->searchCard())
                    ),
                TextInput::make('phone')
                    ->label(__('Or Search by Phone'))
                    ->placeholder('+963 / +966 ...')
                    ->helperText(__('Enter the customer phone number.'))
                    ->suffixAction(
                        Action::make('searchByPhone')
                            ->icon('heroicon-m-magnifying-glass')
                            ->action(fn () => $this->searchCardByPhone())
                    ),
            ]);
    }

    public function lookup(): void
    {
        if (filled($this->token)) {
            $this->searchCard();

            return;
        }

        if (filled($this->phone)) {
            $this->searchCardByPhone();

            return;
        }

        Notification::make()
            ->title(app()->getLocale() === 'ar' ? 'أدخل رمز البطاقة أو رقم الجوال أولاً' : 'Enter a QR token or phone number first')
            ->warning()
            ->send();
    }

    public function searchCard(): void
    {
        if (!filled($this->token)) {
            return;
        }

        $rawToken = trim((string) $this->token);
        if (str_contains($rawToken, '/card/')) {
            $path = parse_url($rawToken, PHP_URL_PATH) ?: $rawToken;
            $rawToken = trim((string) basename($path));
        }

        $tenant = Filament::getTenant();

        $this->scannedCard = LoyaltyCard::with(['customer', 'campaign', 'transactions' => fn ($q) => $q->with('branch')->latest()->take(5)])
            ->where('token', $rawToken)
            ->where('shop_id', $tenant->id)
            ->first();

        if (!$this->scannedCard) {
            Notification::make()
                ->title(app()->getLocale() === 'ar' ? 'لم يتم العثور على البطاقة' : 'Card not found')
                ->danger()
                ->send();
        } else {
            Notification::make()
                ->title(app()->getLocale() === 'ar' ? 'تم تحميل البطاقة بنجاح' : 'Card loaded successfully')
                ->success()
                ->send();
        }
    }

    public function searchCardByPhone(): void
    {
        if (!filled($this->phone)) {
            return;
        }

        $tenant = Filament::getTenant();
        $phone = trim((string) $this->phone);

        $customer = Customer::where('phone', $phone)->first();
        if (!$customer) {
            Notification::make()
                ->title(app()->getLocale() === 'ar' ? 'لم يتم العثور على العميل' : 'Customer not found')
                ->danger()
                ->send();

            return;
        }

        $this->scannedCard = LoyaltyCard::with(['customer', 'campaign', 'transactions' => fn ($q) => $q->with('branch')->latest()->take(5)])
            ->where('customer_id', $customer->id)
            ->where('shop_id', $tenant->id)
            ->latest()
            ->first();

        if (!$this->scannedCard) {
            Notification::make()
                ->title(app()->getLocale() === 'ar' ? 'لا توجد بطاقة نشطة لهذا العميل في متجرك' : 'Customer has no active cards in this shop')
                ->danger()
                ->send();
        } else {
            Notification::make()
                ->title(app()->getLocale() === 'ar' ? 'تم تحميل البطاقة بنجاح' : 'Card loaded successfully')
                ->success()
                ->send();
        }
    }

    public function addPurchase(RewardLogicService $service): void
    {
        if (!$this->scannedCard) {
            return;
        }

        try {
            $card = $service->addPurchase(
                $this->scannedCard,
                auth()->user(),
                auth()->user()->branch
            );
            $this->scannedCard = $card->load(['customer', 'campaign', 'transactions' => fn ($q) => $q->with('branch')->latest()->take(5)]);

            Notification::make()
                ->title(app()->getLocale() === 'ar' ? 'تمت إضافة الختم بنجاح' : 'Stamp added successfully')
                ->success()
                ->send();
        } catch (Exception $e) {
            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function redeemReward(RewardLogicService $service): void
    {
        if (!$this->scannedCard) {
            return;
        }

        try {
            $card = $service->redeemReward(
                $this->scannedCard,
                auth()->user(),
                auth()->user()->branch
            );
            $this->scannedCard = $card->load(['customer', 'campaign', 'transactions' => fn ($q) => $q->with('branch')->latest()->take(5)]);

            Notification::make()
                ->title(app()->getLocale() === 'ar' ? 'تم استبدال المكافأة بنجاح' : 'Reward redeemed successfully')
                ->success()
                ->send();
        } catch (Exception $e) {
            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function clearCard(): void
    {
        $this->token = null;
        $this->phone = null;
        $this->scannedCard = null;
        $this->form->fill();
    }
}
