<?php
// core/classes/BakongRelay.php

class BakongRelay {
    private $token;
    private $baseUrl;
    private $bankAccount;
    private $merchantName;
    private $merchantCity;
    private $storeLabel;
    private $phoneNumber;
    private $terminalLabel;

    public function __construct() {
        $config = require __DIR__ . '/../../config/bakong.php';
        $this->token = $config['api_token'];
        $this->baseUrl = rtrim($config['base_url'], '/');
        $this->bankAccount = $config['bank_account'];
        $this->merchantName = $config['merchant_name'];
        $this->merchantCity = $config['merchant_city'] ?? 'Phnom Penh';
        $this->storeLabel = $config['store_label'] ?? 'Main Store';
        $this->phoneNumber = $config['phone_number'] ?? '85516367859';
        $this->terminalLabel = $config['terminal_label'] ?? 'Web Checkout';
    }

    public function generateQR($amount, $currency = 'USD') {
        $endpoint = "{$this->baseUrl}/v1/generate_qr";
        
        // Ensure amount is formatted correctly for the API
        $amount = (float)$amount;
        
        $payload = [
            'bank_account' => $this->bankAccount,
            'merchant_name' => $this->merchantName,
            'merchant_city' => $this->merchantCity,
            'amount' => $amount,
            'currency' => strtoupper($currency),
            'store_label' => $this->storeLabel,
            'phone_number' => $this->phoneNumber,
            'bill_number' => 'BILL' . date('ymd') . rand(1000, 9999), // Standard bill number prefix
            'terminal_label' => $this->terminalLabel,
            'static' => false 
        ];

        return $this->post($endpoint, $payload);
    }

    public function checkTransaction($md5) {
        $endpoint = "{$this->baseUrl}/v1/check_transaction_by_md5";
        $payload = [
            'md5' => $md5
        ];

        return $this->post($endpoint, $payload);
    }

    public function generateQRImage($qrString) {
        $endpoint = "{$this->baseUrl}/v1/generate_khqr_image";
        $payload = [
            'qr' => $qrString
        ];

        return $this->post($endpoint, $payload);
    }

    protected function post($url, $data) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: ' . $this->token
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix for local XAMPP
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => 'API returned status code ' . $httpCode . ($error ? ' | ' . $error : ''),
                'response' => json_decode($response, true)
            ];
        }

        return [
            'success' => true,
            'data' => json_decode($response, true)
        ];
    }
}
