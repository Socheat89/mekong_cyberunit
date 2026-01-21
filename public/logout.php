<?php
// public/logout.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get tenant subdomain before destroying session
$tenantSubdomain = $_SESSION['tenant_subdomain'] ?? null;

session_destroy();

// Always redirect to public login page
// If tenant subdomain is available, pass it as parameter for context
$redirectUrl = '/Mekong_CyberUnit/public/login.php?success=' . urlencode('Logged out successfully');
if ($tenantSubdomain) {
    $redirectUrl .= '&tenant=' . urlencode($tenantSubdomain);
}

header('Location: ' . $redirectUrl);
exit;
