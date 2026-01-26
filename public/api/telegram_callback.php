<?php
// public/api/telegram_callback.php
header("HTTP/1.1 200 OK"); // Tell Telegram we got it immediately
header('Content-Type: application/json');
error_reporting(0); // Be silent on live for stability
ini_set('display_errors', 0);

function debugLog($msg) {
    @file_put_contents(__DIR__ . '/debug_hits.txt', "[" . date("Y-m-d H:i:s") . "] " . $msg . "\n", FILE_APPEND);
}

try {
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
    if(!$sep) throw new Exception("Invalid separator");

    list($action, $ref) = explode($sep, $data);
    $action = strtolower(trim($action));
    $ref = trim($ref);

    // 2. Load Transactions
    require_once __DIR__ . '/TransactionLogger.php';
    require_once __DIR__ . '/../../core/classes/Database.php';
    
    $db = Database::getInstance();
    $newStatus = ($action === 'approve') ? 'APPROVED' : 'REJECTED';
    
    // Update Storage Layer
    TransactionLogger::save($ref, ['status' => $newStatus, 'processed_at' => time()]);
    try {
        $db->update('payment_approvals', ['status' => strtolower($newStatus)], 'reference_id = ?', [$ref]);
    } catch (Exception $e) { /* DB might not have record if from old system */ }

    // 3. UI Feedback for Admin
    $icon = ($action === 'approve') ? '✅' : '❌';
    $statusText = strtoupper($newStatus);
    $originalText = $message['text'] ?? ($message['caption'] ?? 'Payment Notification');
    
    $newText = preg_replace('/New Payment (Waiting )?Approval/i', "$icon Payment $statusText $icon", $originalText);
    $newText = preg_replace('/Please verify.*/is', '', $newText);
    $newText .= "\n\n<b>Admin Action: $statusText</b>\nTime: " . date('H:i:s');

    // Update Message and Answer UI
    $token = '';
    $configPath = realpath(__DIR__ . '/../../config/telegram.php');
    if ($configPath && file_exists($configPath)) {
        $tgConfig = require $configPath;
        $token = $tgConfig['bot_token'] ?? '';
    }

    if (!empty($token)) {
        editMessage($chatId, $messageId, $newText, $token);
        answerCallback($callbackId, "Success: $statusText", $token);
    }

    debugLog("DONE: $ref -> $statusText");

} catch (Exception $e) {
    debugLog("ERROR: " . $e->getMessage());
}

function answerCallback($id, $text, $token) {
    $url = "https://api.telegram.org/bot$token/answerCallbackQuery";
    $postData = ['callback_query_id' => $id, 'text' => $text];
    $options = ['http' => ['header' => "Content-type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' => http_build_query($postData), 'ignore_errors' => true]];
    @file_get_contents($url, false, stream_context_create($options));
}

function editMessage($chatId, $messageId, $text, $token) {
    $url = "https://api.telegram.org/bot$token/editMessageText";
    $postData = ['chat_id' => $chatId, 'message_id' => $messageId, 'text' => $text, 'parse_mode' => 'HTML'];
    $options = ['http' => ['header' => "Content-type: application/x-www-form-urlencoded\r\n", 'method' => 'POST', 'content' => http_build_query($postData), 'ignore_errors' => true]];
    @file_get_contents($url, false, stream_context_create($options));
}
?>
