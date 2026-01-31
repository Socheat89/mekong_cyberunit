<?php
// admin/setup_master.php
require_once __DIR__ . '/../core/classes/Database.php';

echo "<h1>Master Admin Setup</h1>";

try {
    $db = Database::getInstance();
    
    // Ensure System Tenant (ID 1)
    $stmt = $db->query("SELECT id FROM tenants WHERE id = 1");
    if (!$stmt->fetch()) {
        $db->query("INSERT INTO tenants (id, name, subdomain, status) VALUES (1, 'System Master', 'admin', 'active')");
        echo "Created system tenant.<br>";
    }

    // Ensure Super Admin Role (Level 3)
    $stmt = $db->query("SELECT id FROM roles WHERE level = 3");
    $role = $stmt->fetch();
    if (!$role) {
        $db->query("INSERT INTO roles (name, level, description) VALUES ('super_admin', 3, 'Master System Administrator')");
        $roleId = $db->getConnection()->lastInsertId();
        echo "Created super_admin role.<br>";
    } else {
        $roleId = $role['id'];
    }

    // Create 'admin' user
    $username = 'admin';
    $password = 'admin123';
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->query("SELECT id FROM users WHERE username = 'admin' AND tenant_id = 1");
    if ($stmt->fetch()) {
        $db->query("UPDATE users SET password_hash = '$hash', role_id = $roleId WHERE username = 'admin' AND tenant_id = 1");
        echo "Updated existing 'admin' user.<br>";
    } else {
        $db->query("INSERT INTO users (tenant_id, username, email, password_hash, role_id, status) VALUES (1, 'admin', 'admin@mekongcyberunit.app', '$hash', $roleId, 'active')");
        echo "Created new 'admin' user.<br>";
    }

    echo "<h3>Success!</h3>";
    echo "Username: <strong>admin</strong><br>";
    echo "Password: <strong>admin123</strong><br>";
    echo "<p><a href='login.php'>Go to Admin Login</a></p>";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
