<?php
// public/api/sync_telegram.php
// Manually fetch updates from Telegram Bot (useful for Localhost testing)
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/../../core/classes/Database.php';
require_once __DIR__ . '/TransactionLogger.php';

$config = require __DIR__ . '/../../config/telegram.php';
$token = $config['bot_token'];

echo "<h2>🔄 Telegram Manual Sync</h2>";

// 1. Attempt to fetch updates
$url = "https://api.telegram.org/bot$token/getUpdates?offset=-10";
$response = @file_get_contents($url);

if ($response === FALSE) {
    $error = error_get_last();
    if (strpos($error['message'] ?? '', '409 Conflict') !== false) {
        echo "<div style='color:red; padding:10px; border:1px solid red; margin-bottom:10px;'>
            <b>⚠️ 409 Conflict:</b> Webhook is active on another server (Live Server).<br>
            <i>getUpdates</i> cannot be used while a webhook is set.
            <br><br>
            <form method='POST'>
                <input type='hidden' name='action' value='delete_webhook'>
                <button type='submit' style='padding:8px; background:orange; cursor:pointer;'>Temporarily Disable Webhook (for Local Testing)</button>
            </form>
        </div>";
    } else {
        die("Error: " . ($error['message'] ?? 'Unknown socket error'));
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'delete_webhook') {
    $delUrl = "https://api.telegram.org/bot$token/deleteWebhook";
    file_get_contents($delUrl);
    echo "✅ Webhook Disabled. Refreshing updates... <script>setTimeout(() => location.href=location.pathname, 1500);</script>";
    exit;
}

if ($response) {
    $updates = json_decode($response, true);
    if (!$updates['ok']) {
        die("Telegram API Error: " . ($updates['description'] ?? 'Unknown'));
    }

    $processed = 0;
    echo "<ul>";
    foreach ($updates['result'] as $upd) {
        $callback = $upd['callback_query'] ?? null;
        if (!$callback) continue;

        $data = $callback['data'];
        
        // Detect separator
        $sep = null;
        foreach(['::', ':', '_'] as $s) { if(strpos($data, $s) !== false) { $sep = $s; break; } }
        if (!$sep) continue;

        list($action, $ref) = explode($sep, $data);
        $action = strtolower(trim($action));
        $ref = trim($ref);

        $newStatus = ($action === 'approve') ? 'APPROVED' : 'REJECTED';
        
        // Update JSON
        TransactionLogger::save($ref, ['status' => $newStatus, 'processed_at' => time()]);
        
        // Update DB
        try {
            $db = Database::getInstance();
            $db->update('payment_approvals', ['status' => strtolower($newStatus)], 'reference_id = ?', [$ref]);
        } catch (Exception $e) {}

        echo "<li>✅ Processed: <b>$ref</b> -> <b>$newStatus</b></li>";
        $processed++;
    }
    echo "</ul>";

    if ($processed === 0) {
        echo "<p>No new updates found in the last 10 messages.</p>";
    } else {
        echo "<p style='color:green; font-weight:bold;'>Synced $processed transactions!</p>";
    }
}

echo "<hr>
<button onclick='location.reload()'>🔄 Refresh Updates</button>
<a href='../register.php' style='margin-left:10px; text-decoration:none; color:blue;'>⬅️ Back to Register</a>";
