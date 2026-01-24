<?php
// public/api/check_approval.php
// VERSION: V5_ROBUST_PATH
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
error_reporting(0); 

$md5 = $_GET['md5'] ?? '';

if (empty($md5)) {
    echo json_encode(['success' => false, 'status' => 'INVALID']);
    exit;
}

// HARDENED PATH LOGIC V7 (Using Class)
require_once __DIR__ . '/TransactionLogger.php';

$tx = TransactionLogger::get($md5);

if ($tx) {
    $status = $tx['status'];
    
    if ($status === 'APPROVED') {
        echo json_encode(['success' => true, 'status' => 'SUCCESS']);
    } elseif ($status === 'REJECTED') {
        echo json_encode(['success' => true, 'status' => 'REJECTED']); // Handle rejection
    } else {
        echo json_encode(['success' => true, 'status' => 'PENDING']);
    }
} else {
    echo json_encode(['success' => false, 'status' => 'NOT_FOUND', 'debug' => TransactionLogger::getPath()]);
}
?>
