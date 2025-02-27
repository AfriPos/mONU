<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    protected $client;
    protected $baseUrl;
    protected $clientId;
    protected $secret;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = config('paypal.mode') === 'live'
            ? 'https://api.paypal.com'
            : 'https://api.sandbox.paypal.com';

        $this->clientId = config('paypal.client_id');
        $this->secret = config('paypal.secret');
    }

    // Get PayPal Access Token
    public function getAccessToken()
    {
        $response = $this->client->post("{$this->baseUrl}/v1/oauth2/token", [
            'auth' => [$this->clientId, $this->secret],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return $data['access_token'];
    }

    // Create PayPal Order (for payment)
    public function createOrder($amount, $currency = 'USD')
    {

        if ($amount <= 0) {
            return response()->json(['error' => 'Amount must be greater than zero'], 400);
        }
        
        $accessToken = $this->getAccessToken();

        $response = $this->client->post("{$this->baseUrl}/v2/checkout/orders", [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => $currency,
                            'value' => number_format($amount, 2, '.', '')
                        ],
                    ],
                ],
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    // Capture PayPal Order (Complete Payment)
    public function captureOrder($orderId)
    {
        $accessToken = $this->getAccessToken();

        $response = $this->client->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture", [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
