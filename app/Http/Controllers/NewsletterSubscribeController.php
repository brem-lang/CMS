<?php

namespace App\Http\Controllers;

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

        Subscriber::create(['email' => $validated['email']]);

        return response()->json(['success' => true]);
    }
}
