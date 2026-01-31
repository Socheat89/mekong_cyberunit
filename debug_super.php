<?php
require_once 'core/classes/Database.php';
try {
    $db = Database::getInstance();
    echo "Super Admins:\n";
    print_r($db->fetchAll('SELECT u.*, r.level FROM users u JOIN roles r ON u.role_id = r.id WHERE r.level = 3'));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
