<?php
// public/admin/manual_verify.php
// Localhost Helper to Simulate Telegram Approval
header('Content-Type: text/html');
error_reporting(E_ALL);

// HARDENED PATH LOGIC V6
$logRelPath = '/../../logs/transactions.json';
$logFile = __DIR__ . $logRelPath;

if (file_exists($logFile)) {
    $logFile = realpath($logFile);
}

// Handle Action
if (isset($_GET['action']) && isset($_GET['ref'])) {
    $ref = $_GET['ref'];
    $act = $_GET['action'];
    
    $json = json_decode(file_get_contents($logFile), true);
    if (isset($json[$ref])) {
        $json[$ref]['status'] = ($act === 'approve') ? 'APPROVED' : 'REJECTED';
        file_put_contents($logFile, json_encode($json, JSON_PRETTY_PRINT));
        echo "<div style='padding:10px; background:#dcfce7; color:green; border-radius:5px; margin-bottom:10px;'>
                 ✅ Transaction <strong>$ref</strong> marked as <strong>" . strtoupper($act) . "</strong>!
              </div>";
    }
}

// Display
echo "<h2>🛠️ Localhost Approval Tool</h2>";
echo "<p>Since Telegram cannot call your Localhost, use this to verify payments manually.</p>";

if (file_exists($logFile)) {
    $json = json_decode(file_get_contents($logFile), true);
    echo "<table border='1' cellpadding='10' style='border-collapse:collapse; width:100%; max-width:600px;'>";
    echo "<tr style='background:#f1f5f9;'><th>Ref</th><th>Amount</th><th>Status</th><th>Action</th></tr>";
    
    foreach ($json as $ref => $data) {
        $status = $data['status'] ?? 'PENDING';
        $bg = ($status === 'APPROVED') ? '#dcfce7' : (($status === 'REJECTED') ? '#fee2e2' : '#fff7ed');
        
        echo "<tr style='background:$bg;'>";
        echo "<td><small>$ref</small></td>";
        echo "<td>\${$data['amount']}</td>";
        echo "<td><b>$status</b></td>";
        echo "<td>";
        if ($status === 'PENDING') {
            echo "<a href='?action=approve&ref=$ref' style='text-decoration:none; padding:5px 10px; background:green; color:white; border-radius:4px;'>✅ Approve</a> ";
            echo "<a href='?action=reject&ref=$ref' style='text-decoration:none; padding:5px 10px; background:red; color:white; border-radius:4px;'>❌ Reject</a>";
        } else {
            echo "<span style='color:grey;'>Done</span>";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No transactions found.";
}
?>
