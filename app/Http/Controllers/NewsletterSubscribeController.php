<?php

namespace App\Http\Controllers;

use App\Jobs\SendSubscriberDigitalGiftEmails;
use App\Models\DigitalProduct;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsletterSubscribeController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:subscribers,email'],
        ]);

        $subscriber = Subscriber::create(['email' => $validated['email']]);

        $product = DigitalProduct::where('for_subscribers', true)->first();
        if ($product) {
            SendSubscriberDigitalGiftEmails::dispatch($subscriber, $product->id, '');
        }

        return response()->json(['success' => true]);
    }
}
