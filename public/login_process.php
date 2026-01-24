<?php
// public/login_process.php
session_start();
require_once __DIR__ . '/../core/classes/Database.php';
require_once __DIR__ . '/../core/classes/Auth.php';

$isCleanDomain = ($_SERVER['HTTP_HOST'] === 'mekongcyberunit.app');
$urlPrefix = $isCleanDomain ? '' : '/Mekong_CyberUnit';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $urlPrefix/public/login.php");
    exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];

if (empty($username) || empty($password)) {
    if (isset($_POST['ajax'])) {
        echo json_encode(['success' => false, 'error' => 'Username and password are required']);
        exit;
    }
    header("Location: $urlPrefix/public/login.php?error=" . urlencode('Username and password are required'));
    exit;
}

// For login, we need to determine the tenant
// Since login is from public site, we need tenant context
// But users might login without specifying tenant
// For simplicity, assume they login with username, and we find their tenant

$db = Database::getInstance();
$user = $db->fetchOne(
    "SELECT u.*, t.subdomain, r.name as role_name, r.level as role_level 
     FROM users u 
     JOIN tenants t ON u.tenant_id = t.id 
     JOIN roles r ON u.role_id = r.id 
     WHERE u.username = ? AND u.status = 'active' AND t.status = 'active'",
    [$username]
);

if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['tenant_id'] = $user['tenant_id'];
    $_SESSION['tenant_subdomain'] = $user['subdomain'];
    $_SESSION['role_level'] = $user['role_level'];

    $redirect = '';
    // Redirect based on role
    if ($user['role_level'] == 3) { // Super admin
        $redirect = "$urlPrefix/admin/dashboard.php";
    } else {
        // Redirect to tenant dashboard
        $redirect = "$urlPrefix/{$user['subdomain']}/dashboard";
    }

    if (isset($_POST['ajax'])) {
        echo json_encode(['success' => true, 'redirect' => $redirect]);
        exit;
    }

    header('Location: ' . $redirect);
    exit;
} else {
    if (isset($_POST['ajax'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid username or password']);
        exit;
    }
    header("Location: $urlPrefix/public/login.php?error=" . urlencode('Invalid username or password'));
    exit;
}
?>