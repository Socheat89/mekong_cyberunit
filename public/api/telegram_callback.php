<?php
// public/api/telegram_callback.php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

function debugLog($msg) {
    $logFile = __DIR__ . '/debug_hits.txt';
    $date = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$date] $msg\n", FILE_APPEND);
}

try {
    // Dynamic Path Resolution
    $configPath = realpath(__DIR__ . '/../../config/telegram.php');
    if (!$configPath || !file_exists($configPath)) {
        throw new Exception("Config not found");
    }
    
    $tgConfig = require $configPath;
    $TELEGRAM_BOT_TOKEN = $tgConfig['bot_token'] ?? '';
    
    // 1. Get Webhook Data
    $content = file_get_contents("php://input");
    if (!$content) exit;
    
    $update = json_decode($content, true);
    if (!isset($update['callback_query'])) exit;

    $callback = $update['callback_query'];
    $callbackId = $callback['id'];
    $message = $callback['message'] ?? null;
    $chatId = $message['chat']['id'] ?? null;
    $messageId = $message['message_id'] ?? null;
    $data = $callback['data'] ?? ''; 

    debugLog("HIT: $data");

    // Unified Separator Logic
    $sep = null;
    foreach(['::', ':', '_'] as $s) { if(strpos($data, $s) !== false) { $sep = $s; break; } }
    if(!$sep) throw new Exception("Invalid format");

    list($action, $ref) = explode($sep, $data);
    $action = strtolower(trim($action));
    $ref = trim($ref);

    // 2. Load and Update
    require_once __DIR__ . '/TransactionLogger.php';
    require_once __DIR__ . '/../../core/classes/Database.php';
    
    $tx = TransactionLogger::get($ref);
    $db = Database::getInstance();
    $dbTx = $db->fetchOne("SELECT * FROM payment_approvals WHERE reference_id = ?", [$ref]);

    if (!$tx && !$dbTx) {
        answerCallback($callbackId, "❌ Not Found", $TELEGRAM_BOT_TOKEN);
        exit;
    }

    $newStatus = ($action === 'approve') ? 'APPROVED' : 'REJECTED';
    
    // Update BOTH JSON and DB to keep them in sync
    TransactionLogger::save($ref, ['status' => $newStatus, 'processed_at' => time()]);
    try {
        $db->update('payment_approvals', ['status' => strtolower($newStatus)], 'reference_id = ?', [$ref]);
    } catch (Exception $e) {
        // DB update might fail if record only exists in JSON, skip silently
    }

    // 3. UI Updates
    $icon = ($action === 'approve') ? '✅' : '❌';
    $statusText = strtoupper($newStatus);
    $originalText = $message['text'] ?? ($message['caption'] ?? 'Payment Notification');
    
    $newText = preg_replace('/New Payment (Waiting )?Approval/i', "$icon Payment $statusText $icon", $originalText);
    $newText = preg_replace('/Please verify.*/is', '', $newText);
    $newText .= "\n\n<b>Admin Action: $statusText</b>\nTime: " . date('H:i:s');

    editMessage($chatId, $messageId, $newText, $TELEGRAM_BOT_TOKEN);
    answerCallback($callbackId, "Success: $statusText", $TELEGRAM_BOT_TOKEN);

    debugLog("DONE: $ref -> $statusText");

} catch (Exception $e) {
    debugLog("ERROR: " . $e->getMessage());
}

function answerCallback($id, $text, $token) {
    if (!$id) return;
    $url = "https://api.telegram.org/bot$token/answerCallbackQuery";
    $postData = ['callback_query_id' => $id, 'text' => $text];
    $options = ['http' => ['header' => "Content-type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' => http_build_query($postData)]];
    @file_get_contents($url, false, stream_context_create($options));
}

function editMessage($chatId, $messageId, $text, $token) {
    if (!$chatId || !$messageId) return;
    $url = "https://api.telegram.org/bot$token/editMessageText";
    $postData = ['chat_id' => $chatId, 'message_id' => $messageId, 'text' => $text, 'parse_mode' => 'HTML'];
    $options = ['http' => ['header' => "Content-type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' => http_build_query($postData)]];
    @file_get_contents($url, false, stream_context_create($options));
}
?>
