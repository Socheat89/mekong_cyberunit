<?php
require_once 'core/classes/Database.php';
$db = Database::getInstance();

try {
    // 1. Ensure Tenant 1 exists (System)
    $systemTenant = $db->fetchOne("SELECT id FROM tenants WHERE id = 1");
    if (!$systemTenant) {
        $db->query("INSERT INTO tenants (id, name, subdomain, status) VALUES (1, 'System Admin', 'admin', 'active')");
    }

    // 2. Ensure Super Admin role exists (Level 3)
    $superRole = $db->fetchOne("SELECT id FROM roles WHERE level = 3");
    if (!$superRole) {
        $db->query("INSERT INTO roles (name, level, description) VALUES ('super_admin', 3, 'Master Admin')");
        $roleId = $db->getConnection()->lastInsertId();
    } else {
        $roleId = $superRole['id'];
    }

    // 3. Create/Update Admin User
    $username = 'admin';
    $password = 'admin123';
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $user = $db->fetchOne("SELECT id FROM users WHERE username = ? AND tenant_id = 1", [$username]);
    if ($user) {
        $db->update('users', ['password_hash' => $passwordHash, 'role_id' => $roleId], 'id = ?', [$user['id']]);
        echo "Admin user updated. Password: $password\n";
    } else {
        $db->insert('users', [
            'tenant_id' => 1,
            'username' => $username,
            'email' => 'admin@mekongcyberunit.app',
            'password_hash' => $passwordHash,
            'role_id' => $roleId,
            'status' => 'active'
        ]);
        echo "Admin user created. Username: admin, Password: $password\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
