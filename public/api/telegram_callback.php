<?php
// public/api/telegram_callback.php
// Handles button clicks from Telegram (Approve/Reject)
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

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
    exit; // Silent fail on callback
}

$tgConfig = require $configPath;
$TELEGRAM_BOT_TOKEN = $tgConfig['bot_token'] ?? '';
// ---------------------

// HARDENED PATH LOGIC V7
$logFile = __DIR__ . '/../../logs/transactions.json';

// Log for debugging (Check this file on server!)
function debugLog($msg) {
    file_put_contents(__DIR__ . '/debug_hits.txt', date("Y-m-d H:i:s") . " - " . $msg . "\n", FILE_APPEND);
}


// 1. Get Webhook Data
$content = file_get_contents("php://input");
debugLog("HIT detected. Payload: " . $content);

$update = json_decode($content, true);

if (!isset($update['callback_query'])) {
    debugLog("ERROR: Not a callback_query.");
    exit; 
}

$callback = $update['callback_query'];
$callbackId = $callback['id'];
$chatId = $callback['message']['chat']['id'];
$messageId = $callback['message']['message_id'];
$data = $callback['data']; 

debugLog("Processing Callback: ID=$callbackId, Data=$data");

if (strpos($data, ':') === false) {
    debugLog("ERROR: Data format invalid (no colon).");
    exit;
}

list($action, $ref) = explode(':', $data);

// 2. Load Transactions
require_once __DIR__ . '/TransactionLogger.php';
debugLog("Loading transaction: $ref");

$tx = TransactionLogger::get($ref);

if (!$tx) {
    debugLog("ERROR: Transaction $ref NOT FOUND in log.");
    answerCallback($callbackId, "❌ Transaction not found!", $TELEGRAM_BOT_TOKEN);
    exit;
}

// 3. Process Action
$newStatus = ($action === 'approve') ? 'APPROVED' : 'REJECTED';
debugLog("Updating status to: $newStatus");

if (TransactionLogger::save($ref, ['status' => $newStatus, 'processed_at' => time()])) {
    debugLog("SUCCESS: Log file updated.");
} else {
    debugLog("ERROR: Failed to write to Log file.");
}

// 4. Update Telegram Message
$icon = ($action === 'approve') ? '✅' : '❌';
$statusText = ($action === 'approve') ? 'APPROVED' : 'REJECTED';
$originalText = $callback['message']['text'] ?? 'Payment Notification';

$lines = explode("\n", $originalText);
$newLines = [];
foreach($lines as $line) {
    if (strpos($line, 'New Payment Waiting') !== false) {
        $newLines[] = "$icon **Payment $statusText** $icon";
    } elseif (strpos($line, 'Please verify') !== false || strpos($line, 'Click below') !== false || strpos($line, 'approve_payment.php') !== false) {
        continue; 
    } else {
        $newLines[] = $line;
    }
}
$newText = implode("\n", $newLines);
$newText .= "\n\nProcessed by Admin at " . date('H:i:s');

debugLog("Editing Telegram message...");
editMessage($chatId, $messageId, $newText, $TELEGRAM_BOT_TOKEN);

debugLog("Answering callback...");
answerCallback($callbackId, "Transaction $statusText", $TELEGRAM_BOT_TOKEN);

debugLog("Workflow Complete.");


// --- HELPER FUNCTIONS ---
function answerCallback($id, $text, $token) {
    $url = "https://api.telegram.org/bot$token/answerCallbackQuery";
    $postData = ['callback_query_id' => $id, 'text' => $text];
    
    $options = ['http' => ['header' => "Content-type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' => http_build_query($postData)]];
    $context  = stream_context_create($options);
    @file_get_contents($url, false, $context);
}

function editMessage($chatId, $messageId, $text, $token) {
    $url = "https://api.telegram.org/bot$token/editMessageText";
    $postData = [
        'chat_id' => $chatId, 
        'message_id' => $messageId, 
        'text' => $text, 
        'parse_mode' => 'Markdown',
        'reply_markup' => json_encode(['inline_keyboard' => []]) 
    ];
    
    $options = ['http' => ['header' => "Content-type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' => http_build_query($postData)]];
    $context  = stream_context_create($options);
    @file_get_contents($url, false, $context);
}
?>
