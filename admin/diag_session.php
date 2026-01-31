<?php
session_start();
echo "<h1>Admin Session Diagnostic</h1>";
echo "User ID: " . ($_SESSION['user_id'] ?? 'Not Logged In') . "<br>";
echo "Role Level: " . ($_SESSION['role_level'] ?? 'N/A') . "<br>";
echo "Tenant ID: " . ($_SESSION['tenant_id'] ?? 'N/A') . "<br>";
?>
