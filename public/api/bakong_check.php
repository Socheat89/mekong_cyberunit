<?php
// public/api/bakong_check.php
header('Content-Type: application/json');
require_once __DIR__ . '/../../core/classes/BakongRelay.php';

$md5 = $_GET['md5'] ?? '';

if (empty($md5)) {
    echo json_encode(['success' => false, 'error' => 'Missing MD5']);
    exit;
}

try {
    $bakong = new BakongRelay();
    $result = $bakong->checkTransaction($md5);

    if ($result['success']) {
        $data = $result['data']['data'] ?? [];
        $status = $data['trackingStatus'] ?? ($data['status'] ?? 'PENDING');
        
        echo json_encode([
            'success' => true,
            'status' => $status
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => $result['error']]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
