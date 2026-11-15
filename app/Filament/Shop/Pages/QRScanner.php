<?php

namespace App\Filament\Shop\Pages;

use Filament\Pages\Page;
use App\Models\LoyaltyCard;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Services\RewardLogicService;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;
use Exception;

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
                    ->label('Scan QR Code')
                    ->autofocus()
                    ->helperText('Click here and scan the customer QR code.')
                    ->suffixAction(
                        Action::make('searchByToken')
                            ->icon('heroicon-m-magnifying-glass')
                            ->action(fn () => $this->searchCard())
                    ),
                TextInput::make('phone')
                    ->label('Or Search by Phone')
                    ->helperText('Enter the customer phone number.')
                    ->suffixAction(
                        Action::make('searchByPhone')
                            ->icon('heroicon-m-magnifying-glass')
                            ->action(fn () => $this->searchCardByPhone())
                    ),
            ]);
    }

    public function searchCard(): void
    {
        if (!$this->token) return;

        $tenant = Filament::getTenant();

        $this->scannedCard = LoyaltyCard::with(['customer', 'campaign', 'transactions' => fn($q) => $q->latest()->take(5)])
            ->where('token', $this->token)
            ->where('shop_id', $tenant->id)
            ->first();

        if (!$this->scannedCard) {
            Notification::make()
                ->title('Card not found')
                ->danger()
                ->send();
        } else {
            Notification::make()
                ->title('Card loaded successfully')
                ->success()
                ->send();
        }
    }

    public function searchCardByPhone(): void
    {
        if (!$this->phone) return;

        $tenant = Filament::getTenant();
        
        $customer = \App\Models\Customer::where('phone', $this->phone)->first();
        if (!$customer) {
            Notification::make()->title('Customer not found')->danger()->send();
            return;
        }

        $this->scannedCard = LoyaltyCard::with(['customer', 'campaign', 'transactions' => fn($q) => $q->latest()->take(5)])
            ->where('customer_id', $customer->id)
            ->where('shop_id', $tenant->id)
            ->latest()
            ->first();

        if (!$this->scannedCard) {
            Notification::make()
                ->title('Customer has no active cards in this shop')
                ->danger()
                ->send();
        } else {
            Notification::make()
                ->title('Card loaded successfully')
                ->success()
                ->send();
        }
    }

    public function addPurchaseAction(): Action
    {
        return Action::make('addPurchase')
            ->label('Add Purchase (Stamp)')
            ->color('primary')
            ->requiresConfirmation()
            ->action(function (RewardLogicService $service) {
                try {
                    $this->scannedCard = $service->addPurchase(
                        $this->scannedCard,
                        auth()->user(),
                        auth()->user()->branch
                    );
                    Notification::make()->title('Purchase added')->success()->send();
                } catch (Exception $e) {
                    Notification::make()->title($e->getMessage())->danger()->send();
                }
            })
            ->visible(fn () => $this->scannedCard && $this->scannedCard->reward_status !== 'ready');
    }

    public function redeemRewardAction(): Action
    {
        return Action::make('redeemReward')
            ->label('Redeem Reward')
            ->color('success')
            ->requiresConfirmation()
            ->action(function (RewardLogicService $service) {
                try {
                    $this->scannedCard = $service->redeemReward(
                        $this->scannedCard,
                        auth()->user(),
                        auth()->user()->branch
                    );
                    Notification::make()->title('Reward redeemed successfully')->success()->send();
                } catch (Exception $e) {
                    Notification::make()->title($e->getMessage())->danger()->send();
                }
            })
            ->visible(fn () => $this->scannedCard && $this->scannedCard->reward_status === 'ready');
    }

    public function clearAction(): Action
    {
        return Action::make('clear')
            ->label('Scan Another')
            ->color('secondary')
            ->action(function () {
                $this->token = null;
                $this->phone = null;
                $this->scannedCard = null;
                $this->form->fill();
            })
            ->visible(fn () => $this->scannedCard !== null);
    }
}
