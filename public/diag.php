<?php
require_once __DIR__ . '/../core/classes/Database.php';

try {
    $db = Database::getInstance();
    $plans = $db->fetchAll("SELECT * FROM systems");
    echo "<h1>Database Connection: SUCCESS</h1>";
    echo "<p>Total Plans in DB: " . count($plans) . "</p>";
    foreach ($plans as $plan) {
        echo "<li>" . htmlspecialchars($plan['name']) . " (Status: " . $plan['status'] . ")</li>";
    }
} catch (Exception $e) {
    echo "<h1>Database Connection: FAILED</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
