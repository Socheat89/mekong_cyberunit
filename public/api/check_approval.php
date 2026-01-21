<?php
// public/api/check_approval.php
header('Content-Type: application/json');
require_once __DIR__ . '/../../core/classes/Database.php';

$ref = $_GET['ref'] ?? '';

if (empty($ref)) {
    echo json_encode(['success' => false, 'error' => 'Missing reference']);
    exit;
}

try {
    $db = Database::getInstance();
    $config = require __DIR__ . '/../../config/telegram.php';
    $token = $config['bot_token'];

    // 1. AUTOMATIC SYNC: Check Telegram Updates before returning status (For Localhost Support)
    $tgUrl = "https://api.telegram.org/bot$token/getUpdates";
    $tgResponse = @file_get_contents($tgUrl);
    
    if ($tgResponse) {
        $updates = json_decode($tgResponse, true);
        if ($updates['ok'] && !empty($updates['result'])) {
            foreach ($updates['result'] as $update) {
                if (isset($update['callback_query'])) {
                    $callbackData = $update['callback_query']['data'];
                    
                    if ($callbackData === "approve_$ref") {
                        $db->query("UPDATE payment_approvals SET status = 'approved' WHERE reference_id = ? AND status = 'pending'", [$ref]);
                    } elseif ($callbackData === "reject_$ref") {
                        $db->query("UPDATE payment_approvals SET status = 'rejected' WHERE reference_id = ? AND status = 'pending'", [$ref]);
                    }
                }
            }
        }
    }

    // 2. Fetch final status from Database
    $request = $db->fetchOne("SELECT status FROM payment_approvals WHERE reference_id = ?", [$ref]);

    if ($request) {
        echo json_encode(['success' => true, 'status' => $request['status']]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Reference not found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
