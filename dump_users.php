<?php
require_once 'core/classes/Database.php';
$db = Database::getInstance();
$users = $db->fetchAll("SELECT u.username, r.name as role, r.level FROM users u JOIN roles r ON u.role_id = r.id");
file_put_contents('user_list.txt', print_r($users, true));
echo "User list saved to user_list.txt";
?>
