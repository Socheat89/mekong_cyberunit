<?php
require_once __DIR__ . '/core/classes/Database.php';
$db = Database::getInstance();

echo "<h2>Tables:</h2>";
$tables = $db->fetchAll("SHOW TABLES");
foreach ($tables as $table) {
    $tableName = array_values($table)[0];
    echo "<h3>Table: $tableName</h3>";
    $columns = $db->fetchAll("DESCRIBE $tableName");
    echo "<table border='1'><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        foreach ($column as $value) {
            echo "<td>$value</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}
?>
