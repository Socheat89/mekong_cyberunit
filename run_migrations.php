<?php
require_once __DIR__ . '/core/classes/Database.php';
$db = Database::getInstance();

try {
    echo "Starting migrations...<br>";

    // 1. Add expires_at to tenant_systems
    echo "Checking tenant_systems table...<br>";
    $columns = $db->fetchAll("SHOW COLUMNS FROM tenant_systems LIKE 'expires_at'");
    if (empty($columns)) {
        echo "Adding 'expires_at' column to 'tenant_systems'...<br>";
        $db->query("ALTER TABLE tenant_systems ADD COLUMN expires_at DATETIME NULL AFTER subscribed_at");
    }

    // 2. system_modules table with feature control
    echo "Ensuring 'system_modules' table exists...<br>";
    $db->query("CREATE TABLE IF NOT EXISTS system_modules (
        id INT AUTO_INCREMENT PRIMARY KEY,
        system_id INT NOT NULL,
        module_name VARCHAR(50) NOT NULL,
        feature_key VARCHAR(50) NULL,
        FOREIGN KEY (system_id) REFERENCES systems(id) ON DELETE CASCADE
    )");

    // Fix columns: check if feature_key exists
    $columns = $db->fetchAll("SHOW COLUMNS FROM system_modules LIKE 'feature_key'");
    if (empty($columns)) {
        echo "Adding 'feature_key' to 'system_modules'...<br>";
        $db->query("ALTER TABLE system_modules ADD COLUMN feature_key VARCHAR(50) NULL AFTER module_name");
    }

    // Fix indexes: check if old unique_system_module exists
    $indexes = $db->fetchAll("SHOW INDEX FROM system_modules WHERE Key_name = 'unique_system_module'");
    if (!empty($indexes)) {
        echo "Cleaning up old index...<br>";
        $db->query("ALTER TABLE system_modules DROP INDEX unique_system_module");
    }

    // Add new unique index if missing
    $indexes = $db->fetchAll("SHOW INDEX FROM system_modules WHERE Key_name = 'unique_system_feature'");
    if (empty($indexes)) {
        echo "Adding new feature-level unique index...<br>";
        $db->query("ALTER TABLE system_modules ADD UNIQUE KEY unique_system_feature (system_id, module_name, feature_key)");
    }

    echo "Migrations completed successfully!";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage();
}
?>
