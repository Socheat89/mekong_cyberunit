<?php
// bakong_debug.php - Upload this to your hosting root folder to test connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Bakong Connection Debugger</h1>";

// 1. Check Config
// Try to locate config/bakong.php relative to this script
$configPath = __DIR__ . '/config/bakong.php';
if (!file_exists($configPath)) {
    // Try one level up if we are in public/
    $configPath = __DIR__ . '/../config/bakong.php';
}

if (!file_exists($configPath)) {
    die("<p style='color:red'>Error: config/bakong.php not found.</p>");
}

echo "<p>✅ Config file found at: $configPath</p>";
$config = require $configPath;

$baseUrl = rtrim($config['base_url'], '/');
$token = $config['api_token'];

echo "<ul>";
echo "<li><strong>URL:</strong> $baseUrl</li>";
echo "<li><strong>Token Length:</strong> " . strlen($token) . " chars</li>";
echo "<li><strong>Server IP (Outgoing):</strong> " . $_SERVER['SERVER_ADDR'] . " (May differ from public IP)</li>";
echo "</ul>";

// 2. Check cURL
if (!function_exists('curl_init')) {
    die("<p style='color:red'>Error: cURL is not installed on this server.</p>");
}
echo "<p>✅ cURL is installed.</p>";

// 3. Test Connection
$endpoint = $baseUrl . '/v1/check_transaction_by_md5'; // Using a known endpoint
echo "<h3>Testing Connection to: $endpoint</h3>";

// Dummy MD5 payload
$payloadData = ['md5' => 'e1112932342342342342342342342342']; 
$payload = json_encode($payloadData);

$ch = curl_init($endpoint);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token,
    'Accept: application/json'
]);
// We disable SSL verify for debugging to see if it's an SSL issue or network issue
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
// curl_setopt($ch, CURLOPT_VERBOSE, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
$curlErrno = curl_errno($ch);
curl_close($ch);

if ($curlErrno) {
    echo "<div style='background:#fee; padding:10px; border:1px solid red'>";
    echo "<h3>❌ Connection Failed (cURL Error)</h3>";
    echo "<p><strong>Error Code:</strong> $curlErrno</p>";
    echo "<p><strong>Error Message:</strong> $curlError</p>";
    echo "<p><strong>Diagnosis:</strong> Your hosting server cannot reach the Bakong server. This is likely a Firewall issue or DNS issue on the hosting.</p>";
    echo "</div>";
} else {
    echo "<div style='background:#eef; padding:10px; border:1px solid blue'>";
    echo "<h3>Response Received (HTTP $httpCode)</h3>";
    echo "<p><strong>Raw Response:</strong></p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    
    if ($httpCode >= 200 && $httpCode < 300) {
         $json = json_decode($response, true);
         // Even if it returns "Transaction not found", it means connectivity is SUCCESSFUL
         echo "<h4 style='color:green'>✅ Connectivity Successful!</h4>";
         echo "<p>The server responded. Since we sent a dummy MD5, an error message inside the JSON is expected (e.g., 'Transaction not found').</p>";
    } elseif ($httpCode == 401) {
        echo "<h4 style='color:red'>❌ Authorization Failed (401)</h4>";
        echo "<p><strong>Diagnosis:</strong> Invalid Token. Please check your API Token in <code>config/bakong.php</code>.</p>";
    } elseif ($httpCode == 403) {
        echo "<h4 style='color:red'>❌ Forbidden (403)</h4>";
        echo "<p><strong>Diagnosis:</strong> Access Denied. This usually means your <strong>Hosting IP Address is not whitelisted</strong> by Bakong (NBC).</p>";
        echo "<p>Please contact Bakong support to whitelist your hosting IP.</p>";
    } elseif ($httpCode == 404) {
         echo "<h4 style='color:orange'>⚠️ Endpoint Not Found (404)</h4>";
         echo "<p>Check the Base URL in config. It might be incorrect.</p>";
    } else {
         echo "<h4 style='color:red'>❌ Server Error ($httpCode)</h4>";
         echo "<p>The Bakong server returned an unexpected error.</p>";
    }
    echo "</div>";
}
?>
