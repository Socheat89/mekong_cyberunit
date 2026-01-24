<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnostic Tool</h1>";

echo "<h2>Environment Info</h2>";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "<br>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "<br>";

echo "<h2>Checking Database Config</h2>";
$configPath = __DIR__ . '/config/database.php';
if (file_exists($configPath)) {
    $dbConfig = require $configPath;
    echo "Config file found.<br>";
    echo "Host: " . $dbConfig['host'] . "<br>";
    echo "Database: " . $dbConfig['database'] . "<br>";
    echo "Username: " . $dbConfig['username'] . "<br>";
    // Don't echo password for security, but check if it's set
    echo "Password set: " . (empty($dbConfig['password']) ? 'NO' : 'YES') . "<br>";
    
    echo "<h2>Testing PDO Connection</h2>";
    try {
        $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
        $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<span style='color:green'>Database Connection Success!</span><br>";
        
        $stmt = $pdo->query("SELECT COUNT(*) FROM tenants");
        $count = $stmt->fetchColumn();
        echo "Tenants count: $count<br>";
        
    } catch (PDOException $e) {
        echo "<span style='color:red'>Database Connection Failed: " . $e->getMessage() . "</span><br>";
    }
} else {
    echo "<span style='color:red'>Config file NOT found at $configPath</span><br>";
}

echo "<h2>Checking File Structure</h2>";
$files = ['core/classes/Database.php', 'core/classes/Tenant.php', 'index.php'];
foreach ($files as $f) {
    echo "$f: " . (file_exists(__DIR__ . '/' . $f) ? 'EXISTS' : 'MISSING') . "<br>";
}
?>
