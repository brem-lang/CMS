<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriberDigitalGiftMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $productTitle,
        public string $downloadUrl,
        public string $body = ''
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your free download: ' . $this->productTitle . ' — ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscriber-digital-gift',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
