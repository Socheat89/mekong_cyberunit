<?php
// public/api/sync_telegram.php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../../core/classes/Database.php';
require_once __DIR__ . '/../../core/classes/TelegramBot.php';

$config = require __DIR__ . '/../../config/telegram.php';
$token = $config['bot_token'];

// Fetch updates from Telegram (since we can't use webhooks on localhost)
$url = "https://api.telegram.org/bot$token/getUpdates";
$response = file_get_contents($url);
$updates = json_decode($response, true);

if (!$updates['ok']) {
    die("Error fetching updates: " . ($updates['description'] ?? 'Unknown error'));
}

if (empty($updates['result'])) {
    die("<h3 style='color: orange;'>No new updates found from Telegram Bot.</h3><p>Make sure you clicked 'Approve' or 'Reject' on a recent message.</p>");
}

$db = Database::getInstance();
$processedCount = 0;

echo "<h2>🔄 Telegram Sync Results</h2>";
echo "<ul>";

foreach ($updates['result'] as $update) {
    if (isset($update['callback_query'])) {
        $callbackData = $update['callback_query']['data'];
        $ref = '';
        $newStatus = '';

        if (strpos($callbackData, 'approve_') === 0) {
            $ref = str_replace('approve_', '', $callbackData);
            $newStatus = 'approved';
        } elseif (strpos($callbackData, 'reject_') === 0) {
            $ref = str_replace('reject_', '', $callbackData);
            $newStatus = 'rejected';
        }

        if ($ref && $newStatus) {
            $updated = $db->query("UPDATE payment_approvals SET status = ? WHERE reference_id = ? AND status = 'pending'", [$newStatus, $ref]);
            
            if ($updated->rowCount() > 0) {
                echo "<li style='color: green;'>✅ Processed <b>$ref</b>: <b>$newStatus</b></li>";
                $processedCount++;
            } else {
                echo "<li style='color: gray;'>ℹ️ Reference <b>$ref</b> is already processed or not found.</li>";
            }
        }
    }
}

echo "</ul>";

if ($processedCount > 0) {
    echo "<p style='color: green; font-weight: bold;'>Successfully updated $processedCount payments!</p>";
} else {
    echo "<p>No new pending approvals processed.</p>";
}

echo "<hr><button onclick='location.reload()'>Check Again</button>";
