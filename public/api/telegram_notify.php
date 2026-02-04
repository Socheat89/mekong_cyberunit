<?php
// public/api/telegram_notify.php
// VERSION: V5_FINAL_TELEGRAM_ONLY
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
error_reporting(E_ALL);
ini_set('display_errors', 0); // Suppress errors in JSON output

// --- CONFIGURATION ---
$root = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__, 2);
$possiblePaths = [
    $root . '/Mekong_CyberUnit/config/telegram.php',
    $root . '/config/telegram.php',
    dirname(__DIR__, 2) . '/config/telegram.php',
    __DIR__ . '/../../config/telegram.php'
];

$configPath = null;
foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        $configPath = $path;
        break;
    }
}

if (!$configPath) {
    $searched = implode(" || ", $possiblePaths);
    echo json_encode(['success' => false, 'error' => 'Telegram config not found. Searched: ' . $searched]);
    exit;
}

$tgConfig = require $configPath;

$TELEGRAM_BOT_TOKEN = $tgConfig['bot_token'] ?? '';
$TELEGRAM_CHAT_ID = $tgConfig['chat_id'] ?? '';
// ---------------------

// Determine log path robustly
$logPathCandidates = [
    dirname($configPath) . '/../logs/transactions.json',
    $root . '/Mekong_CyberUnit/logs/transactions.json',
    __DIR__ . '/../../logs/transactions.json'
];

$logFile = $logPathCandidates[2]; // Default relative
foreach ($logPathCandidates as $path) {
    // We want the directory to exist or be creatable
    $dir = dirname($path);
    if (is_dir($dir) || @mkdir($dir, 0777, true)) {
        $logFile = $path;
        break;
    }
}


// Ensure log directory exists one last time explicitly
if (!is_dir(dirname($logFile))) {
    @mkdir(dirname($logFile), 0777, true);
}

// 1. Get POST Data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'No data received']);
    exit;
}

$md5 = $data['md5'] ?? '';
$amount = $data['amount'] ?? '0.00';
$plan = $data['plan'] ?? 'Unknown';
$method = $data['method'] ?? 'bakong';
$type = $data['type'] ?? 'registration'; // registration or renewal
$businessName = $data['business_name'] ?? 'New Customer';
$tenantId = $data['tenant_id'] ?? null;

if (empty($md5)) {
    echo json_encode(['success' => false, 'error' => 'MD5 reference missing']);
    exit;
}

// 2. Save Transaction as PENDING (Main Log)
require_once __DIR__ . '/TransactionLogger.php';

$txData = [
    'amount' => $amount,
    'plan' => $plan,
    'method' => $method,
    'status' => 'PENDING',
    'timestamp' => time(),
    'ip' => $_SERVER['REMOTE_ADDR'],
    'type' => $type,
    'business_name' => $businessName,
    'tenant_id' => $tenantId
];

TransactionLogger::save($md5, $txData);

// 2b. Database Backup (Secondary Log)
try {
    require_once __DIR__ . '/../../core/classes/Database.php';
    $db = Database::getInstance();
    $db->insert('payment_approvals', [
        'reference_id' => $md5,
        'plan' => $plan,
        'amount' => $amount,
        'status' => 'pending'
    ]);
} catch (Exception $e) {}

// 3. Send Telegram Notification
if ($TELEGRAM_BOT_TOKEN === 'YOUR_BOT_TOKEN_HERE' || empty($TELEGRAM_BOT_TOKEN)) {
    echo json_encode(['success' => true, 'status' => 'PENDING', 'message' => 'Token not configured/empty, simulated mode.']);
    exit;
}

$title = ($type === 'renewal') ? "🔄 Subscription Renewal Request" : "🔔 New Registration Payment";

$message = "<b>$title</b>\n\n";
$message .= "<b>🏢 Business:</b> " . htmlspecialchars($businessName) . "\n";
$message .= "<b>💰 Amount:</b> $" . htmlspecialchars($amount) . "\n";
$message .= "<b>📦 Plan:</b> " . htmlspecialchars(ucfirst($plan)) . "\n";
$message .= "<b>💳 Method:</b> " . htmlspecialchars(ucfirst($method)) . "\n";
$message .= "<b>🔑 Ref:</b> <code>" . htmlspecialchars($md5) . "</code>\n";
$message .= "<b>⏰ Time:</b> " . date('Y-m-d H:i:s') . "\n\n";
$message .= "Please verify and approve this transaction.";

// Inline callback buttons allow direct approval inside Telegram
$keyboard = [
    'inline_keyboard' => [
        [
            ['text' => '✅ Approve', 'callback_data' => "approve::$md5"],
            ['text' => '❌ Reject', 'callback_data' => "reject::$md5"]
        ]
    ]
];

$url = "https://api.telegram.org/bot$TELEGRAM_BOT_TOKEN/sendMessage";
$postData = [
    'chat_id' => $TELEGRAM_CHAT_ID,
    'text' => $message,
    'parse_mode' => 'HTML',
    'reply_markup' => json_encode($keyboard)
];

// Send using file_get_contents (simple POST)
$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($postData),
        'ignore_errors' => true 
    ]
];
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    $error = error_get_last();
    $errorMsg = $error['message'] ?? 'Unknown socket error';
    echo json_encode(['success' => false, 'error' => "Telegram Send Failed: $errorMsg"]);
} else {
    $response = json_decode($result, true);
    if (isset($response['ok']) && $response['ok']) {
        echo json_encode(['success' => true, 'status' => 'WAITING_APPROVAL']);
    } else {
        $apiErr = $response['description'] ?? 'Unknown API error';
        echo json_encode(['success' => false, 'error' => "Telegram API Error: $apiErr"]);
    }
}
?>
