<?php
// bakong_debug_v2.php - Advanced connection debugger with Browser Spoofing
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Bakong Connection Debugger (v2 - Browser Spoofing)</h1>";

// 1. Load Config
$configPath = __DIR__ . '/config/bakong.php';
if (!file_exists($configPath)) {
    $configPath = __DIR__ . '/../config/bakong.php';
}

if (!file_exists($configPath)) {
    die("<p style='color:red'>Error: config/bakong.php not found.</p>");
}
$config = require $configPath;
$baseUrl = rtrim($config['base_url'], '/');
$token = $config['api_token'];

// 2. Setup cURL with "Fake" Browser Headers
$endpoint = $baseUrl . '/v1/check_transaction_by_md5'; // Using a known endpoint
echo "<h3>Testing Connection to: $endpoint</h3>";
echo "<p>Attempting to spoof Chrome (Windows 10)...</p>";

$payloadData = ['md5' => 'e1112932342342342342342342342342']; 
$payload = json_encode($payloadData);

$ch = curl_init($endpoint);

// Headers that mimic a real browser request exactly
$headers = [
    'Connection: keep-alive',
    'Pragma: no-cache',
    'Cache-Control: no-cache',
    'sec-ch-ua: "Not_A Brand";v="8", "Chromium";v="120", "Google Chrome";v="120"',
    'sec-ch-ua-mobile: ?0',
    'sec-ch-ua-platform: "Windows"',
    'Upgrade-Insecure-Requests: 1',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Content-Type: application/json',
    'Accept: application/json, text/plain, */*',
    'Sec-Fetch-Site: none',
    'Sec-Fetch-Mode: cors',
    'Sec-Fetch-User: ?1',
    'Sec-Fetch-Dest: empty',
    'Accept-Language: en-US,en;q=0.9',
    'Authorization: Bearer ' . $token
];

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate'); // Accept compression like a browser

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
$curlErrno = curl_errno($ch);
curl_close($ch);

if ($curlErrno) {
    echo "<div style='background:#fee; padding:10px; border:1px solid red'>";
    echo "<h3>❌ Connection Failed (cURL Error)</h3>";
    echo "<p><strong>Error:</strong> $curlError</p>";
    echo "</div>";
} else {
    echo "<div style='background:#eef; padding:10px; border:1px solid blue'>";
    echo "<h3>Response Received (HTTP $httpCode)</h3>";
    echo "<p><strong>Raw Response:</strong></p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    
    if ($httpCode >= 200 && $httpCode < 300) {
         echo "<h2 style='color:green'>✅ SUCCESS! Advanced Spoofing Worked.</h2>";
         echo "<p>We can fix the main code by updating the User-Agent headers.</p>";
    } elseif ($httpCode == 403) {
        echo "<h2 style='color:red'>❌ STILL 403 FORBIDDEN</h2>";
        echo "<p><strong>Conclusion:</strong> The IP Address itself is blacklisted. No amount of code changes will fix this. You MUST whitelist the IP or change servers.</p>";
    } else {
         echo "<h4 style='color:orange'>Server returned status $httpCode</h4>";
    }
    echo "</div>";
}
?>
