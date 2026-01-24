<?php
// public/api/debug_state.php
// Tool to diagnose "Split Brain" issues where Telegram sees one thing and Web sees another
header('Content-Type: text/html');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Transaction State Debugger</h2>";

// 1. Resolve Path
$relative = __DIR__ . '/../../logs/transactions.json';
$resolved = realpath($relative);

echo "<strong>Target Path (Relative):</strong> " . htmlspecialchars($relative) . "<br>";
echo "<strong>Resolved Path:</strong> " . ($resolved ? htmlspecialchars($resolved) : "❌ Could not resolve path (File missing?)") . "<br>";

echo "<hr>";

if (!$resolved || !file_exists($resolved)) {
    echo "<h3 style='color:red;'>❌ Log File NOT Found on Server</h3>";
    echo "This explains why it's not working. The file is missing or permissions prevent reading.<br>";
    echo "Attempting to create it... ";
    
    // Try to create
    $default = [];
    if (@file_put_contents($relative, json_encode($default))) {
        echo "<span style='color:green;'>SUCCESS! Created file.</span>";
    } else {
        echo "<span style='color:red;'>FAILED. Permission denied.</span>";
        echo "<br><em>Please manually create the folder 'logs' in your project root and chmod 777.</em>";
    }
} else {
    echo "<h3 style='color:green;'>✅ Log File Exists</h3>";
    echo "Permissions: " . substr(sprintf('%o', fileperms($resolved)), -4) . "<br>";
    echo "Size: " . filesize($resolved) . " bytes<br>";
    echo "Last Modified: " . date("Y-m-d H:i:s", filemtime($resolved)) . "<br>";
    
    echo "<h3>Recent Transactions:</h3>";
    $content = file_get_contents($resolved);
    $json = json_decode($content, true);
    
    if (empty($json)) {
        echo "<em>File is empty or invalid JSON.</em>";
    } else {
        echo "<table border='1' cellpadding='5' style='border-collapse:collapse;'>";
        echo "<tr><th>MD5 Ref</th><th>Status</th><th>Amount</th><th>Time</th></tr>";
        foreach ($json as $ref => $data) {
            $statusColor = 'black';
            if ($data['status'] == 'APPROVED') $statusColor = 'green';
            if ($data['status'] == 'PENDING') $statusColor = 'orange';
            if ($data['status'] == 'REJECTED') $statusColor = 'red';
            
            echo "<tr>";
            echo "<td><small>$ref</small></td>";
            echo "<td style='color:$statusColor; font-weight:bold;'>{$data['status']}</td>";
            echo "<td>\${$data['amount']}</td>";
            $ts = $data['timestamp'] ?? time();
            echo "<td>" . date("H:i:s", $ts) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}
?>
