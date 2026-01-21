<?php
// public/api/bakong_qr.php
header('Content-Type: application/json');
require_once __DIR__ . '/../../core/classes/BakongRelay.php';

$plan = $_GET['plan'] ?? '';
$method = $_GET['method'] ?? 'bakong';
$amount = 0;

if ($plan === 'starter') $amount = 0.10;
elseif ($plan === 'professional') $amount = 50;
elseif ($plan === 'enterprise') $amount = 100;
else {
    echo json_encode(['success' => false, 'error' => 'Invalid plan']);
    exit;
}

// Case 1: ACLEDA (Static QR)
if ($method === 'acleda') {
    $imagePath = "/Mekong_CyberUnit/public/images/acleda_$amount.png";
    
    echo json_encode([
        'success' => true,
        'qr' => 'STATIC_ACLEDA_' . $amount,
        'md5' => 'static_acleda_' . $amount,
        'amount' => $amount,
        'image' => $imagePath,
        'is_static' => true
    ]);
    exit;
}

// Case 2: Bakong (Dynamic KHQR)
try {
    $bakong = new BakongRelay();
    $result = $bakong->generateQR($amount);

    if ($result['success']) {
        $qrData = $result['data']['data'];
        $imageResult = $bakong->generateQRImage($qrData['qr']);
        
        if ($imageResult['success']) {
            echo json_encode([
                'success' => true,
                'qr' => $qrData['qr'],
                'md5' => $qrData['md5'],
                'amount' => $amount,
                'image' => $imageResult['data']['data']['image'] ?? '',
                'is_static' => false
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to generate QR image', 'details' => $imageResult['error']]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => $result['error']]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
