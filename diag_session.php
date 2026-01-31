<?php
// diag_session.php
session_start();
$_SESSION['test'] = 'hello';
echo "Session ID: " . session_id() . "<br>";
echo "Session Test: " . ($_SESSION['test'] ?? 'not set') . "<br>";
echo "Cookie: " . ($_COOKIE[session_name()] ?? 'none') . "<br>";
echo "Role Level: " . ($_SESSION['role_level'] ?? 'not set') . "<br>";
?>
