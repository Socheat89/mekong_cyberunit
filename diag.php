<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnostic Tool v2</h1>";

// 1. Check Error Log
echo "<h2>1. Recent Server Errors (error_log)</h2>";
$logFile = __DIR__ . '/error_log';
if (file_exists($logFile)) {
    $lines = array_slice(file($logFile), -10);
    echo "<pre style='background:#fee2e2; padding:10px; border:1px solid #ef4444;'>";
    foreach ($lines as $line) echo htmlspecialchars($line);
    echo "</pre>";
} else {
    echo "No error_log file found in " . __DIR__ . "<br>";
}

// 2. Check DB Config
echo "<h2>2. Database Configuration Test</h2>";
$configPath = __DIR__ . '/config/database.php';
if (file_exists($configPath)) {
    try {
        $dbConfig = require $configPath;
        echo "Config file loaded successfully.<br>";
        echo "DB Name: " . $dbConfig['database'] . "<br>";
        
        $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
        $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
        echo "<span style='color:green'>PDO Connection: OK</span><br>";
    } catch (Throwable $e) {
        echo "<span style='color:red'>DB Error: " . $e->getMessage() . "</span><br>";
    }
} else {
    echo "Config missing at $configPath<br>";
}

// 3. Environment
echo "<h2>3. Environment</h2>";
echo "Host: " . $_SERVER['HTTP_HOST'] . "<br>";
echo "PHP Version: " . phpversion() . "<br>";
?>
