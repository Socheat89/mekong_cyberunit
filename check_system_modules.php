<?php
require_once 'core/classes/Database.php';
$db = Database::getInstance();
$res = $db->fetchOne("SHOW CREATE TABLE system_modules");
echo $res['Create Table'];
