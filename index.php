<?php
/**
 * index.php - Front Controller (Safe Mode)
 * This version is designed to be compatible with older PHP versions (5.6+)
 * and catch all errors to prevent silent 500 errors.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

try {
    if (!isset($_SESSION)) {
        session_start();
    }
    date_default_timezone_set('Asia/Phnom_Penh');

    // Define Paths & Environment
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $host = str_replace('www.', '', $host);
    // Check if on production mekongcyberunit.app or mekongcy
    $isProduction = (strpos($host, 'mekongcyberunit.app') !== false || strpos($host, 'mekongcy') !== false);
    $urlPrefix = $isProduction ? '' : '/Mekong_CyberUnit';

    // Auto-load Core Files with absolute paths
    $baseDir = dirname(__FILE__);
    
    if (!file_exists($baseDir . '/core/classes/Database.php')) die('File missing: core/classes/Database.php');
    require_once $baseDir . '/core/classes/Database.php';
    
    if (!file_exists($baseDir . '/core/classes/Tenant.php')) die('File missing: core/classes/Tenant.php');
    require_once $baseDir . '/core/classes/Tenant.php';
    
    if (!file_exists($baseDir . '/core/classes/Auth.php')) die('File missing: core/classes/Auth.php');
    require_once $baseDir . '/core/classes/Auth.php';
    
    if (!file_exists($baseDir . '/middleware/AuthMiddleware.php')) die('File missing: middleware/AuthMiddleware.php');
    require_once $baseDir . '/middleware/AuthMiddleware.php';
    
    if (!file_exists($baseDir . '/middleware/TenantMiddleware.php')) die('File missing: middleware/TenantMiddleware.php');
    require_once $baseDir . '/middleware/TenantMiddleware.php';

    // Get Request URL
    $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $path = parse_url($requestUri, PHP_URL_PATH);

    // Normalize path (Strip subfolder if on local)
    if (!$isProduction && strpos($path, '/Mekong_CyberUnit') === 0) {
        $path = substr($path, strlen('/Mekong_CyberUnit'));
    }
    if (empty($path)) $path = '/';

    // Routing Logic
    // 1. Public Folder (Assets, APIs)
    if (strpos($path, '/public/') === 0) {
        $file = $baseDir . $path;
        if (file_exists($file) && !is_dir($file)) {
            include $file;
            exit;
        }
    }

    // 2. Tenant Routing (e.g., /socheatcofe/dashboard)
    $segments = explode('/', trim($path, '/'));
    if (count($segments) >= 2) {
        $tenantSlug = $segments[0];
        $module = $segments[1];

        // Skip reserved keywords
        if ($tenantSlug !== 'admin' && $tenantSlug !== 'public' && $tenantSlug !== 'core' && $tenantSlug !== 'middleware') {
            Tenant::detect($tenantSlug);
            
            if ($module === 'dashboard') {
                include $baseDir . '/tenant/dashboard.php';
                exit;
            }
            if ($module === 'logout') {
                include $baseDir . '/public/logout.php';
                exit;
            }
            // Add more module routing here if needed
        }
    }

    // 3. Root / Default Page
    if ($path === '/' || $path === '') {
        include $baseDir . '/public/index.php';
        exit;
    }

    // 4. Fallback Diagnostics if path not routed
    echo "<h1>Path Not Routed</h1>";
    echo "Current Path: " . htmlspecialchars($path) . "<br>";
    echo "Is Production: " . ($isProduction ? 'Yes' : 'No') . "<br>";

} catch (Exception $e) {
    echo "<div style='padding:20px; background:#fff1f2; color:#be123c; border:1px solid #fda4af; border-radius:8px; font-family:sans-serif; margin:20px;'>";
    echo "<h2 style='margin-top:0'>System Error (Captured)</h2>";
    echo "<strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line: " . $e->getLine() . ")<br>";
    echo "</div>";
}
?>