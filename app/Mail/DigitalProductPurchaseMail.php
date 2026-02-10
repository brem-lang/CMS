<?php

namespace App\Mail;

use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DigitalProductPurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public OrderItem $orderItem,
        public string $receiptId,
        public string $downloadUrl
    ) {
        $this->orderItem->load(['order', 'digitalProduct']);
    }

    public function envelope(): Envelope
    {
        $title = $this->orderItem->digitalProduct?->title ?? 'Digital Product';

        return new Envelope(
            subject: 'Your digital download: ' . $title . ' — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.digital-product-purchase',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
