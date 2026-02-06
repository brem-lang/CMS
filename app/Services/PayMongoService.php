<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayMongoService
{
    protected $secretKey;

    protected $apiUrl;

    public function __construct()
    {
        $this->secretKey = config('services.paymongo.secret_key');
        $this->apiUrl = config('services.paymongo.api_url');
    }

    /**
     * Get HTTP client with SSL configuration
     */
    protected function httpClient()
    {
        $client = Http::withBasicAuth($this->secretKey, '');

        // For local development, disable SSL verification if needed
        if (app()->environment('local')) {
            $client = $client->withoutVerifying();
        }

        return $client;
    }

    /**
     * Create a payment intent
     */
    public function createPaymentIntent($amount, $currency = 'PHP', $metadata = [])
    {
        // Flatten metadata - PayMongo doesn't accept nested metadata
        $flatMetadata = [];
        foreach ($metadata as $key => $value) {
            $flatMetadata[$key] = is_array($value) ? json_encode($value) : (string) $value;
        }

        $response = $this->httpClient()
            ->post("{$this->apiUrl}/payment_intents", [
                'data' => [
                    'attributes' => [
                        'amount' => $amount * 100, // Convert to centavos
                        'payment_method_allowed' => [
                            'gcash',
                            'grab_pay',
                            'paymaya',
                            'qrph',
                            'card',
                        ],
                        'currency' => $currency,
                        'metadata' => $flatMetadata,
                    ],
                ],
            ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        throw new \Exception('Failed to create payment intent: '.$response->body());
    }

    /**
     * Create a payment method
     */
    public function createPaymentMethod($type, $details = [])
    {
        $response = $this->httpClient()
            ->post("{$this->apiUrl}/payment_methods", [
                'data' => [
                    'attributes' => [
                        'type' => $type,
                        'details' => $details,
                    ],
                ],
            ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        throw new \Exception('Failed to create payment method: '.$response->body());
    }

    /**
     * Attach payment method to payment intent
     */
    public function attachPaymentMethod($paymentIntentId, $paymentMethodId, $returnUrl, $clientKey)
    {
        $response = $this->httpClient()
            ->post("{$this->apiUrl}/payment_intents/{$paymentIntentId}/attach", [
                'data' => [
                    'attributes' => [
                        'payment_method' => $paymentMethodId,
                        'client_key' => $clientKey,
                        'return_url' => $returnUrl,
                    ],
                ],
            ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        throw new \Exception('Failed to attach payment method: '.$response->body());
    }

    /**
     * Create a payment source for GCash/PayMaya
     */
    public function createSource($amount, $type, $currency = 'PHP', $redirect = [])
    {
        // Map payment method types to valid PayMongo source types
        // Note: PayMongo only supports gcash and grab_pay as source types
        // Maya and ShopeePay may need to use grab_pay or payment intent instead
        $sourceTypeMap = [
            'gcash' => 'gcash',
            'grabpay' => 'grab_pay',
            'maya' => 'grab_pay', // Maya uses grab_pay source type in PayMongo
            'shopeepay' => 'grab_pay', // ShopeePay uses grab_pay source type in PayMongo
        ];

        $sourceType = $sourceTypeMap[$type] ?? $type;

        $response = $this->httpClient()
            ->post("{$this->apiUrl}/sources", [
                'data' => [
                    'attributes' => [
                        'amount' => $amount * 100,
                        'currency' => $currency,
                        'type' => $sourceType,
                        'redirect' => $redirect,
                    ],
                ],
            ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        throw new \Exception('Failed to create source: '.$response->body());
    }

    /**
     * Retrieve payment intent
     */
    public function getPaymentIntent($paymentIntentId)
    {
        $response = $this->httpClient()
            ->get("{$this->apiUrl}/payment_intents/{$paymentIntentId}");

        if ($response->successful()) {
            return $response->json()['data'];
        }

        return null;
    }

    /**
     * Retrieve source
     */
    public function getSource($sourceId)
    {
        $response = $this->httpClient()
            ->get("{$this->apiUrl}/sources/{$sourceId}");

        if ($response->successful()) {
            return $response->json()['data'];
        }

        return null;
    }

    /**
     * Create a checkout session
     *
     * @param  array  $lineItems  Array of line items with name, quantity, amount, currency
     * @param  string  $successUrl  URL to redirect after successful payment
     * @param  string  $cancelUrl  URL to redirect after cancelled payment
     * @param  array  $paymentMethodTypes  Array of payment method types (e.g., ['gcash', 'grab_pay', 'paymaya', 'shopeepay'])
     * @param  string  $description  Optional description for the checkout session
     * @param  array  $metadata  Optional metadata
     * @return array Checkout session data
     */
    public function createCheckoutSession($lineItems, $successUrl, $cancelUrl, $paymentMethodTypes = ['gcash', 'grab_pay', 'paymaya'], $description = null, $metadata = [], $customerInfo = [])
    {
        // Flatten metadata - PayMongo doesn't accept nested metadata
        $flatMetadata = [];
        foreach ($metadata as $key => $value) {
            $flatMetadata[$key] = is_array($value) ? json_encode($value) : (string) $value;
        }

        // Prepare line items - ensure amounts are in centavos
        $preparedLineItems = [];
        foreach ($lineItems as $item) {
            $preparedItem = [
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'amount' => isset($item['amount']) ? (int) ($item['amount'] * 100) : (int) ($item['price'] * 100 * $item['quantity']), // Convert to centavos
                'currency' => $item['currency'] ?? 'PHP',
            ];

            // Include description if provided (e.g., size and color information)
            if (isset($item['description']) && ! empty($item['description'])) {
                $preparedItem['description'] = $item['description'];
            }

            // Include images if provided
            if (isset($item['images']) && is_array($item['images']) && ! empty($item['images'])) {
                $preparedItem['images'] = $item['images'];
            }

            $preparedLineItems[] = $preparedItem;
        }

        $attributes = [
            'line_items' => $preparedLineItems,
            'payment_method_types' => $paymentMethodTypes,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ];

        if ($description) {
            $attributes['description'] = $description;
        }

        if (! empty($flatMetadata)) {
            $attributes['metadata'] = $flatMetadata;
        }

        // Add customer information (billing) if provided
        if (! empty($customerInfo)) {
            $billing = [];

            if (isset($customerInfo['name']) && ! empty($customerInfo['name'])) {
                $billing['name'] = $customerInfo['name'];
            }

            if (isset($customerInfo['email']) && ! empty($customerInfo['email'])) {
                $billing['email'] = $customerInfo['email'];
            }

            if (isset($customerInfo['phone']) && ! empty($customerInfo['phone'])) {
                $billing['phone'] = $customerInfo['phone'];
            }

            if (! empty($billing)) {
                $attributes['billing'] = $billing;
            }
        }

        $response = $this->httpClient()
            ->post("{$this->apiUrl}/checkout_sessions", [
                'data' => [
                    'attributes' => $attributes,
                ],
            ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        // Parse error response for better error messages
        $errorMessage = 'Failed to create checkout session: '.$response->body();
        $errors = $response->json('errors');

        if (! empty($errors)) {
            foreach ($errors as $error) {
                // Check for payment method configuration errors
                if (($error['code'] ?? null) === 'invalid_request_body' &&
                    str_contains($error['detail'] ?? '', 'Payment method is not configured')) {
                    $configuredMethods = implode(', ', config('services.paymongo.payment_methods', ['N/A']));
                    $attemptedMethods = implode(', ', $paymentMethodTypes);
                    $errorMessage = "Payment method configuration error: {$error['detail']}. Configured: [{$configuredMethods}], Attempted: [{$attemptedMethods}].";
                    Log::error('PayMongo Checkout Session Error: '.$errorMessage, [
                        'response' => $response->json(),
                        'configured_methods' => config('services.paymongo.payment_methods'),
                        'attempted_methods' => $paymentMethodTypes,
                    ]);
                    break;
                }
            }

            // Log all errors for debugging
            if (! str_contains($errorMessage, 'Payment method configuration error')) {
                Log::error('PayMongo Checkout Session Error', [
                    'response' => $response->json(),
                    'attributes' => $attributes,
                ]);
            }
        }

        throw new \Exception($errorMessage);
    }

    /**
     * Retrieve checkout session
     *
     * @param  string  $checkoutSessionId
     * @return array|null Checkout session data or null if not found
     */
    public function getCheckoutSession($checkoutSessionId)
    {
        $response = $this->httpClient()
            ->get("{$this->apiUrl}/checkout_sessions/{$checkoutSessionId}");

        if ($response->successful()) {
            return $response->json()['data'];
        }

        return null;
    }
}
