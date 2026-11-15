<?php

namespace App\Mail;

use App\Models\Customer;
use App\Models\LoyaltyCard;
use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Customer $customer,
        public Shop $shop,
        public LoyaltyCard $card,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "مرحباً بك في {$this->shop->name} | ThreeDigits Loyalty",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-welcome',
            with: [
                'customer' => $this->customer,
                'shop' => $this->shop,
                'card' => $this->card,
                'cardUrl' => route('loyalty-card.show', $this->card->token),
            ],
        );
    }
}
