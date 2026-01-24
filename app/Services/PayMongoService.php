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
            $flatMetadata[$key] = is_array($value) ? json_encode($value) : (string)$value;
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
        
        throw new \Exception('Failed to create payment intent: ' . $response->body());
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
        
        throw new \Exception('Failed to create payment method: ' . $response->body());
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
        
        throw new \Exception('Failed to attach payment method: ' . $response->body());
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
        
        throw new \Exception('Failed to create source: ' . $response->body());
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
}
