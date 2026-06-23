<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymobService
{
    private $apiKey;
    private $integrationId;
    private $iframeId;

    public function __construct()
    {
        $this->apiKey = config('services.paymob.api_key');
        $this->integrationId = config('services.paymob.integration_id');
        $this->iframeId = config('services.paymob.iframe_id');
    }

    /**
     * 1. Authentication Request
     * @return string|null Auth Token
     */
    public function getAuthToken()
    {
        try {
            $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
                'api_key' => $this->apiKey
            ]);

            if ($response->successful()) {
                return $response->json()['token'];
            }
            Log::error('Paymob Auth Error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Paymob Auth Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * 2. Order Registration Request
     * @param string $authToken
     * @param array $orderData
     * @return int|null Paymob Order ID
     */
    public function createOrder($authToken, $orderData)
    {
        try {
            $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
                'auth_token'      => $authToken,
                'delivery_needed' => 'false',
                'amount_cents'    => (int) ($orderData['amount'] * 100),
                'currency'        => 'EGP',
                'merchant_order_id'=> $orderData['reference'],
                'items'           => [], // Add items if needed, empty array is usually fine
            ]);

            if ($response->successful()) {
                return $response->json()['id'];
            }
            Log::error('Paymob Create Order Error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Paymob Create Order Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * 3. Payment Key Request
     * @param string $authToken
     * @param int $paymobOrderId
     * @param array $orderData
     * @return string|null Payment Token
     */
    public function getPaymentKey($authToken, $paymobOrderId, $orderData)
    {
        try {
            $response = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', [
                'auth_token'     => $authToken,
                'amount_cents'   => (int) ($orderData['amount'] * 100),
                'expiration'     => 3600,
                'order_id'       => $paymobOrderId,
                'billing_data'   => [
                    'apartment'    => '803',
                    'email'        => $orderData['email'] ?? 'test@example.com',
                    'floor'        => '42',
                    'first_name'   => $orderData['first_name'] ?? 'John',
                    'street'       => 'Main Street',
                    'building'     => '8028',
                    'phone_number' => $orderData['phone'] ?? '+201000000000',
                    'shipping_method' => 'PKG',
                    'postal_code'  => '11511',
                    'city'         => 'Cairo',
                    'country'      => 'EG',
                    'last_name'    => $orderData['last_name'] ?? 'Doe',
                    'state'        => 'Cairo'
                ],
                'currency'       => 'EGP',
                'integration_id' => $this->integrationId
            ]);

            if ($response->successful()) {
                return $response->json()['token'];
            }
            Log::error('Paymob Payment Key Error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Paymob Payment Key Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Helper: Generate Iframe URL
     */
    public function getIframeUrl($paymentToken)
    {
        return "https://accept.paymob.com/api/acceptance/iframes/{$this->iframeId}?payment_token={$paymentToken}";
    }

    /**
     * Process full payment request and return iframe URL
     */
    public function processPayment($orderData)
    {
        $authToken = $this->getAuthToken();
        if (!$authToken) return null;

        $paymobOrderId = $this->createOrder($authToken, $orderData);
        if (!$paymobOrderId) return null;

        $paymentToken = $this->getPaymentKey($authToken, $paymobOrderId, $orderData);
        if (!$paymentToken) return null;

        return [
            'paymob_order_id' => $paymobOrderId,
            'iframe_url'      => $this->getIframeUrl($paymentToken)
        ];
    }
}
