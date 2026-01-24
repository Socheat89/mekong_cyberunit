<?php
// index.php - Front Controller
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

try {
    session_start();
    date_default_timezone_set('Asia/Phnom_Penh');

    // Define Paths
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $host = str_replace('www.', '', $host);
    $isProduction = (strpos($host, 'mekongcyberunit.app') !== false || strpos($host, 'mekongcy') !== false);
    $urlPrefix = $isProduction ? '' : '/Mekong_CyberUnit';

    // Auto-load Core
    require_once __DIR__ . '/core/classes/Database.php';
    require_once __DIR__ . '/core/classes/Tenant.php';
    require_once __DIR__ . '/core/classes/Auth.php';
    require_once __DIR__ . '/middleware/AuthMiddleware.php';
    require_once __DIR__ . '/middleware/TenantMiddleware.php';

    $requestUri = $_SERVER['REQUEST_URI'];
    $path = parse_url($requestUri, PHP_URL_PATH);

    // Normalize path for routing
    if (!$isProduction && strpos($path, '/Mekong_CyberUnit') === 0) {
        $path = substr($path, strlen('/Mekong_CyberUnit'));
    }
    if (empty($path)) $path = '/';

    // Route: Public Files (CSS/JS/API)
    if (strpos($path, '/public/') === 0) {
        $file = __DIR__ . $path;
        if (file_exists($file)) {
            include $file;
            exit;
        }
    }

    // Route: Tenant Modules (e.g. /socheatcofe/dashboard)
    $segments = explode('/', trim($path, '/'));
    if (count($segments) >= 2) {
        $tenantSlug = $segments[0];
        $module = $segments[1];

        if ($tenantSlug !== 'admin' && $tenantSlug !== 'public') {
            Tenant::detect($tenantSlug);
            
            if ($module === 'dashboard') {
                include __DIR__ . '/tenant/dashboard.php';
                exit;
            }
            if ($module === 'logout') {
                include __DIR__ . '/public/logout.php';
                exit;
            }
            // Handle POS prefix
            if ($module === 'pos' && isset($segments[2])) {
                $action = $segments[2];
                // You can add more POS routing here
                echo "POS Action: " . htmlspecialchars($action);
                exit;
            }
        }
    }

    // Route: Root / Default
    if ($path === '/' || $path === '') {
        include __DIR__ . '/public/index.php';
        exit;
    }

    // 404 Fallback
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
    echo "Path: " . htmlspecialchars($path);

} catch (Throwable $e) {
    echo "<div style='padding:20px; background:#fff1f2; color:#be123c; border:1px solid #fda4af; border-radius:8px; font-family:sans-serif; margin:20px;'>";
    echo "<h2 style='margin-top:0'>System Error</h2>";
    echo "<strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line: " . $e->getLine() . ")<br>";
    echo "</div>";
}
?>