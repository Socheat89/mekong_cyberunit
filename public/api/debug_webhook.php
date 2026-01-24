<?php
// public/api/debug_webhook.php
header('Content-Type: text/html');

echo "<h1>Telegram Webhook Debugger</h1>";

$configPath = __DIR__ . '/../../config/telegram.php';
if (!file_exists($configPath)) {
    // Try robust find
    $root = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__, 2);
    $configPath = $root . '/Mekong_CyberUnit/config/telegram.php';
}

if (!file_exists($configPath)) {
    die("❌ Config not found.");
}

$config = require $configPath;
$token = $config['bot_token'];

echo "<strong>Bot Token:</strong> " . substr($token, 0, 10) . "...<br>";

// 1. Get Webhook Info
$url = "https://api.telegram.org/bot$token/getWebhookInfo";
$info = file_get_contents($url);
$json = json_decode($info, true);

if ($json && $json['ok']) {
    $r = $json['result'];
    echo "<h3>Current Webhook Status:</h3>";
    echo "URL: <strong>" . ($r['url'] ?: "(Not Set)") . "</strong><br>";
    echo "Has Custom Cert: " . ($r['has_custom_certificate'] ? 'Yes' : 'No') . "<br>";
    echo "Pending Updates: " . $r['pending_update_count'] . "<br>";
    if (isset($r['last_error_date'])) {
        echo "Last Error: " . date("Y-m-d H:i:s", $r['last_error_date']) . "<br>";
        echo "Error Msg: " . $r['last_error_message'] . "<br>";
    }
    
    echo "<hr>";
    echo "<h3>Set New Webhook</h3>";
    
    // Auto-detect
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
    $host = $_SERVER['HTTP_HOST'];
    $currentUrl = "$protocol://$host$_SERVER[REQUEST_URI]";
    $callbackUrl = str_replace('debug_webhook.php', 'telegram_callback.php', $currentUrl);
    
    // Warning for Localhost
    $isLocal = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false);
    
    if ($isLocal) {
        echo "<div style='background:#fffbeb; color:#92400e; padding:10px; border:1px solid #fcd34d; margin-bottom:10px;'>";
        echo "⚠️ <strong>You are on Localhost.</strong> Telegram cannot connect to this address.<br>";
        echo "Please enter your <strong>Real Website Domain</strong> below to set the webhook for your live server.";
        echo "</div>";
        $callbackUrl = "https://mekongcyberunit.app/public/api/telegram_callback.php"; // Suggestion
    }
    
    echo "<form method='POST'>";
    echo "<label>Callback URL to set:</label><br>";
    echo "<input type='text' name='set_url' value='$callbackUrl' style='width:100%; padding:8px; margin:5px 0;'><br>";
    echo "<button type='submit' style='padding:10px; background:blue; color:white; cursor:pointer;'>Set Webhook</button>";
    echo "</form>";
    
    if (isset($_POST['set_url'])) {
        $setUrl = "https://api.telegram.org/bot$token/setWebhook?url=" . urlencode($_POST['set_url']);
        $res = file_get_contents($setUrl);
        echo "<pre>$res</pre>";
        echo "✅ Webhook Updated! Refresh page to verify.";
    }
    
} else {
    echo "❌ Failed to connect to Telegram API.";
}
?>
