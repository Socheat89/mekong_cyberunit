<?php
// public/api/telegram_callback.php
require_once __DIR__ . '/../../core/classes/Database.php';
require_once __DIR__ . '/../../core/classes/TelegramBot.php';

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update || !isset($update['callback_query'])) exit;

$callbackQuery = $update['callback_query'];
$data = $callbackQuery['data'];
$messageId = $callbackQuery['message']['message_id'];
$chatId = $callbackQuery['message']['chat']['id'];

$db = Database::getInstance();
$telegram = new TelegramBot();

if (strpos($data, 'approve_') === 0) {
    $ref = str_replace('approve_', '', $data);
    $db->update('payment_approvals', ['status' => 'approved'], 'reference_id = ?', [$ref]);
    
    // Edit original message to show status
    $editUrl = "https://api.telegram.org/bot" . (require __DIR__ . '/../../config/telegram.php')['bot_token'] . "/editMessageText";
    file_get_contents($editUrl . "?" . http_build_query([
        'chat_id' => $chatId,
        'message_id' => $messageId,
        'text' => "✅ Payment <b>$ref</b> has been <b>APPROVED</b>.",
        'parse_mode' => 'HTML'
    ]));
} elseif (strpos($data, 'reject_') === 0) {
    $ref = str_replace('reject_', '', $data);
    $db->update('payment_approvals', ['status' => 'rejected'], 'reference_id = ?', [$ref]);
    
    // Edit original message to show status
    $editUrl = "https://api.telegram.org/bot" . (require __DIR__ . '/../../config/telegram.php')['bot_token'] . "/editMessageText";
    file_get_contents($editUrl . "?" . http_build_query([
        'chat_id' => $chatId,
        'message_id' => $messageId,
        'text' => "❌ Payment <b>$ref</b> has been <b>REJECTED</b>.",
        'parse_mode' => 'HTML'
    ]));
}

// Answer callback query with an alert
$responseText = (strpos($data, 'approve_') === 0) ? "✅ Approved successful!" : "❌ Rejected successful!";
$answerUrl = "https://api.telegram.org/bot" . (require __DIR__ . '/../../config/telegram.php')['bot_token'] . "/answerCallbackQuery";
$ch = curl_init($answerUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'callback_query_id' => $callbackQuery['id'],
    'text' => $responseText,
    'show_alert' => true // This will show a popup alert in Telegram
]));
curl_exec($ch);
curl_close($ch);
