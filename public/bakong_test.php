<?php
// public/bakong_test.php
header('Content-Type: text/plain');

echo "Bakong Connectivity Test (Diagnostics)\n";
echo "======================================\n";
echo "Server Time: " . date('Y-m-d H:i:s') . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "cURL Version: " . json_encode(curl_version()) . "\n\n";

$url = 'https://api-bakong.nbc.gov.kh/v1/check_transaction_by_md5'; // Production URL
echo "Target URL: $url\n\n";

// Test 1: Simple GET (Check for 405 or 403)
echo "--- Test 1: Simple GET Request ---\n";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
// Mimic Browser
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Error: $error\n";
echo "Response Preview: " . substr(strip_tags($response), 0, 100) . "...\n\n";

// Test 2: POST with Headers (Simulate Actual Request)
echo "--- Test 2: Full POST Request (Simulated) ---\n";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['md5' => 'test_md5_hash']));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Origin: https://bakong.nbc.gov.kh',
    'Referer: https://bakong.nbc.gov.kh/',
    'Authorization: Bearer test_token'
]);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$info = curl_getinfo($ch);
curl_close($ch);

// Test 3: Stream Context (Fallback Method)
echo "--- Test 3: Stream Context (Fallback Method) ---\n";
$headers = [
    "Content-Type: application/json",
    "Origin: https://bakong.nbc.gov.kh",
    "Referer: https://bakong.nbc.gov.kh/",
    "User-Agent: okhttp/3.12.1"
];
$opts = [
    'http' => [
        'method' => 'POST',
        'header' => implode("\r\n", $headers),
        'content' => json_encode(['md5' => 'test_md5_hash']),
        'ignore_errors' => true,
        'timeout' => 15
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ]
];
$context = stream_context_create($opts);
$response = @file_get_contents($url, false, $context);
echo "Response: " . ($response ? substr(strip_tags($response), 0, 100) : "FALSE (Connection Failed)") . "\n";
echo "Headers: " . json_encode($http_response_header ?? []) . "\n";

