<?php
require_once 'core/classes/Database.php';
$db = Database::getInstance();
echo "Roles:\n";
print_r($db->fetchAll('SELECT * FROM roles'));
echo "\nUsers:\n";
print_r($db->fetchAll('SELECT u.id, u.username, u.tenant_id, r.name as role, r.level FROM users u JOIN roles r ON u.role_id = r.id'));
?>
