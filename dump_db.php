<?php
require_once 'core/classes/Database.php';
$db = Database::getInstance();
$tenants = $db->fetchAll("SELECT id, name, subdomain FROM tenants");
$users = $db->fetchAll("SELECT id, username, tenant_id, role_id FROM users");
$roles = $db->fetchAll("SELECT id, name, level FROM roles");
file_put_contents('db_dump.txt', "TENANTS:\n" . print_r($tenants, true) . "\n\nUSERS:\n" . print_r($users, true) . "\n\nROLES:\n" . print_r($roles, true));
echo "Dumped to db_dump.txt";
?>
