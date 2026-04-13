<?php
/**
 * Simple Stripe payment processor - pure PHP with cURL, no external libraries.
 * Handles payment intents and charges for rental package purchases.
 */
class Stripe {
    private $secretKey;
    private $publishableKey;
    private $apiVersion = '2023-10-16';
    private $apiBase = 'https://api.stripe.com/v1';
    private $lastError = '';

    public function __construct() {
        $this->secretKey = defined('STRIPE_SECRET_KEY') ? STRIPE_SECRET_KEY : '';
        $this->publishableKey = defined('STRIPE_PUBLISHABLE_KEY') ? STRIPE_PUBLISHABLE_KEY : '';
    }

    /**
     * Check if Stripe is configured
     */
    public static function isConfigured() {
        return defined('STRIPE_SECRET_KEY') 
            && trim(STRIPE_SECRET_KEY) !== '' 
            && defined('STRIPE_PUBLISHABLE_KEY') 
            && trim(STRIPE_PUBLISHABLE_KEY) !== '';
    }

    /**
     * Get publishable key for frontend
     */
    public function getPublishableKey() {
        return $this->publishableKey;
    }

    /**
     * Create a payment intent
     * @param int $amount Amount in smallest currency unit (e.g., cents for USD, cents for LKR)
     * @param string $currency Currency code (e.g., 'usd', 'lkr')
     * @param array $metadata Additional data to attach to the payment
     * @return array|false Payment intent data or false on failure
     */
    public function createPaymentIntent($amount, $currency = 'lkr', $metadata = []) {
        $data = [
            'amount' => (int) $amount,
            'currency' => strtolower($currency),
            'metadata' => $metadata
        ];

        return $this->request('POST', '/payment_intents', $data);
    }

    /**
     * Retrieve a payment intent
     * @param string $paymentIntentId The payment intent ID
     * @return array|false Payment intent data or false on failure
     */
    public function retrievePaymentIntent($paymentIntentId) {
        return $this->request('GET', '/payment_intents/' . $paymentIntentId);
    }

    /**
     * Create a charge (direct charge with token/source)
     * @param int $amount Amount in smallest currency unit
     * @param string $currency Currency code
     * @param string $source Token or source ID (e.g., tok_visa from Stripe.js)
     * @param array $metadata Additional data
     * @return array|false Charge data or false on failure
     */
    public function createCharge($amount, $currency, $source, $metadata = []) {
        $data = [
            'amount' => (int) $amount,
            'currency' => strtolower($currency),
            'source' => $source,
            'metadata' => $metadata
        ];

        return $this->request('POST', '/charges', $data);
    }

    /**
     * Get last error message
     */
    public function getLastError() {
        return $this->lastError;
    }

    /**
     * Make API request to Stripe
     * @param string $method HTTP method (GET, POST)
     * @param string $endpoint API endpoint (e.g., '/payment_intents')
     * @param array $data Request data
     * @return array|false Response data or false on failure
     */
    private function request($method, $endpoint, $data = []) {
        $this->lastError = '';

        if (empty($this->secretKey)) {
            $this->lastError = 'Stripe secret key not configured';
            return false;
        }

        $url = $this->apiBase . $endpoint;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->secretKey,
            'Stripe-Version: ' . $this->apiVersion,
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->flattenArray($data)));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            $this->lastError = 'cURL error: ' . $curlError;
            return false;
        }

        $decoded = json_decode($response, true);

        if ($httpCode >= 400) {
            $this->lastError = $decoded['error']['message'] ?? 'Unknown error';
            error_log('Stripe API error: ' . $this->lastError);
            return false;
        }

        return $decoded;
    }

    /**
     * Flatten nested arrays for Stripe API (e.g., metadata[key] = value)
     */
    private function flattenArray($array, $prefix = '') {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix ? $prefix . '[' . $key . ']' : $key;
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey));
            } else {
                $result[$newKey] = $value;
            }
        }
        return $result;
    }
}
