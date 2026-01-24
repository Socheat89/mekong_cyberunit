<?php
// index.php - Front Controller
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

try {
    session_start();
    
    // Ensure consistent timezone (UTC+7)
    date_default_timezone_set('Asia/Phnom_Penh');

    if (!file_exists('core/classes/Database.php')) die('Critical Error: Database.php missing');
    if (!file_exists('config/database.php')) die('Critical Error: config/database.php missing');

    require_once 'core/classes/Database.php';
    require_once 'core/classes/Tenant.php';
    require_once 'core/classes/Auth.php';
    require_once 'middleware/AuthMiddleware.php';
    require_once 'middleware/TenantMiddleware.php';

    // Define path logic
    $basePath = '/Mekong_CyberUnit';
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $host = str_replace('www.', '', $host);
    $isProduction = (strpos($host, 'mekongcyberunit.app') !== false || strpos($host, 'mekongcy') !== false);
    $urlPrefix = $isProduction ? '' : $basePath;

    $requestUri = $_SERVER['REQUEST_URI'];
    $path = parse_url($requestUri, PHP_URL_PATH);

    if (!$isProduction && strpos($path, $basePath) === 0) {
        $path = substr($path, strlen($basePath));
    }
    if (empty($path)) $path = '/';

    // Function to route POS actions
    function routePOSAction($action) {
        if ($action === 'dashboard') {
            require_once 'modules/pos/controllers/DashboardController.php';
            (new DashboardController())->index();
        } elseif ($action === 'pos') {
            require_once 'modules/pos/controllers/PosController.php';
            (new PosController())->index();
        } elseif ($action === 'order' || $action === 'orders/create') {
            require_once 'modules/pos/controllers/OrderController.php';
            (new OrderController())->create();
        } elseif ($action === 'products') {
            require_once 'modules/pos/controllers/ProductController.php';
            (new ProductController())->index();
        } elseif ($action === 'customers') {
            require_once 'modules/pos/controllers/CustomerController.php';
            (new CustomerController())->index();
        } elseif ($action === 'reports') {
            require_once 'modules/pos/controllers/ReportsController.php';
            (new ReportsController())->index();
        } elseif ($action === 'settings') {
            require_once 'modules/pos/controllers/SettingsController.php';
            (new SettingsController())->index();
        } else {
            echo 'Module action not found: ' . htmlspecialchars($action);
        }
    }

    // Public routes (/public/...)
    if (strpos($path, '/public/') === 0) {
        $file = __DIR__ . $path;
        if (file_exists($file)) { include $file; } 
        else { include 'public/404.php'; }
        exit;
    }

    // Tenant Routing (slug/module)
    if (preg_match('/^\/([^\/]+)\/(.+)/', $path, $matches)) {
        $tenantSlug = $matches[1];
        $modulePath = $matches[2];

        if ($tenantSlug !== 'admin' && $tenantSlug !== 'public') {
            Tenant::detect($tenantSlug);
            
            if ($modulePath === 'dashboard') {
                include 'tenant/dashboard.php';
                exit;
            } elseif ($modulePath === 'logout') {
                include 'public/logout.php';
                exit;
            } elseif (preg_match('/^pos\/(.+)/', $modulePath, $pMatches)) {
                routePOSAction($pMatches[1]);
                exit;
            }
        }
    }

    // Admin Panel
    if (strpos($path, '/admin/') === 0) {
        echo 'Admin panel - UNDER CONSTRUCTION';
        exit;
    }

    // Default: Home Page
    include 'public/index.php';

} catch (Throwable $e) {
    echo "<div style='padding:20px; background:#fff1f2; color:#be123c; border:1px solid #fda4af; border-radius:8px; font-family:sans-serif;'>";
    echo "<h2 style='margin-top:0'>Backend Error Detected</h2>";
    echo "<strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line: " . $e->getLine() . ")<br>";
    echo "<strong>Stack Trace:</strong><pre style='font-size:12px; margin-top:10px;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>";
}
?>