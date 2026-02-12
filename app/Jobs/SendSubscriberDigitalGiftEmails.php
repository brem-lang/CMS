<?php

namespace App\Jobs;

use App\Mail\SubscriberDigitalGiftMail;
use App\Models\DigitalProduct;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendSubscriberDigitalGiftEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Subscriber $subscriber;

    public int $digitalProductId;

    public string $body;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(Subscriber $subscriber, int $digitalProductId, string $body = '')
    {
        $this->subscriber = $subscriber;
        $this->digitalProductId = $digitalProductId;
        $this->body = $body;
    }

    public function handle(): void
    {
        $digitalProduct = DigitalProduct::find($this->digitalProductId);

        if (! $digitalProduct) {
            Log::warning('SendSubscriberDigitalGiftEmails: Digital product not found', [
                'digital_product_id' => $this->digitalProductId,
            ]);

            return;
        }

        if (! $digitalProduct->is_active || ! $digitalProduct->is_free) {
            Log::warning('SendSubscriberDigitalGiftEmails: Product is not active or not free', [
                'digital_product_id' => $digitalProduct->id,
            ]);

            return;
        }

        if (! $digitalProduct->file_path) {
            Log::warning('SendSubscriberDigitalGiftEmails: Product has no file path', [
                'digital_product_id' => $digitalProduct->id,
            ]);

            return;
        }

        $downloadUrl = URL::temporarySignedRoute(
            'digital-product.download.gift',
            now()->addDays(7),
            ['digitalProduct' => $digitalProduct->id],
            absolute: true
        );

        $productTitle = $digitalProduct->title;

        try {
            Mail::to($this->subscriber->email)->send(new SubscriberDigitalGiftMail(
                $productTitle,
                $downloadUrl,
                $this->body
            ));
            Log::info('SendSubscriberDigitalGiftEmails: Sent gift email', [
                'subscriber_id' => $this->subscriber->id,
                'email' => $this->subscriber->email,
                'digital_product_id' => $digitalProduct->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('SendSubscriberDigitalGiftEmails: Failed to send email', [
                'subscriber_id' => $this->subscriber->id,
                'email' => $this->subscriber->email,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
