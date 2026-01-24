<?php
// index.php - Front Controller (PHP 8.2 Optimized)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    date_default_timezone_set('Asia/Phnom_Penh');

    $baseDir = dirname(__FILE__);
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    $host = str_replace('www.', '', $host);
    $isProduction = (strpos($host, 'mekongcyberunit.app') !== false || strpos($host, 'mekongcy') !== false);
    
    // Auto-load Core Components
    require_once $baseDir . '/core/classes/Database.php';
    require_once $baseDir . '/core/classes/Tenant.php';
    require_once $baseDir . '/core/classes/Auth.php';
    require_once $baseDir . '/middleware/AuthMiddleware.php';
    require_once $baseDir . '/middleware/TenantMiddleware.php';

    $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $path = parse_url($requestUri, PHP_URL_PATH);

    // Normalize path: Always strip the project folder name if it's present at the start
    $projectFolder = '/Mekong_CyberUnit';
    if (strpos($path, $projectFolder) === 0) {
        $path = substr($path, strlen($projectFolder));
    }
    if (empty($path)) $path = '/';

    // 1. Static/Public Routing
    if (strpos($path, '/public/') === 0) {
        $file = $baseDir . $path;
        if (file_exists($file) && !is_dir($file)) {
            include $file;
            exit;
        }
    }

    // 2. Tenant Routing (e.g. /socheatcofe/dashboard)
    $segments = explode('/', trim($path, '/'));
    
    // If running in a subdirectory on production, the first segment might be the folder name
    if (isset($segments[0]) && $segments[0] === 'Mekong_CyberUnit') {
        array_shift($segments);
    }

    if (count($segments) >= 2) {
        $tenantSlug = $segments[0];
        $module = $segments[1];

        // List of reserved words to skip tenant detection
        $reserved = ['admin', 'public', 'core', 'middleware', 'config', 'modules', 'api'];
        if (!in_array($tenantSlug, $reserved)) {
            Tenant::detect($tenantSlug);
            
            if ($module === 'dashboard') {
                include $baseDir . '/tenant/dashboard.php';
                exit;
            }
            if ($module === 'logout') {
                include $baseDir . '/public/logout.php';
                exit;
            }
            // POS Module Routing
            if ($module === 'pos' && isset($segments[2])) {
                $sub = $segments[2];
                $controller = null;
                $action = 'index';

                if ($sub === 'dashboard') {
                    require_once $baseDir . '/modules/pos/controllers/DashboardController.php';
                    $controller = new DashboardController();
                } elseif ($sub === 'pos') {
                    require_once $baseDir . '/modules/pos/controllers/PosController.php';
                    $controller = new PosController();
                } elseif ($sub === 'products') {
                    require_once $baseDir . '/modules/pos/controllers/ProductController.php';
                    $controller = new ProductController();
                } elseif ($sub === 'orders') {
                    require_once $baseDir . '/modules/pos/controllers/OrderController.php';
                    $controller = new OrderController();
                } elseif ($sub === 'customers') {
                    require_once $baseDir . '/modules/pos/controllers/CustomerController.php';
                    $controller = new CustomerController();
                } elseif ($sub === 'reports') {
                    require_once $baseDir . '/modules/pos/controllers/ReportsController.php';
                    $controller = new ReportsController();
                } elseif ($sub === 'settings') {
                    require_once $baseDir . '/modules/pos/controllers/SettingsController.php';
                    $controller = new SettingsController();
                }

                if ($controller) {
                    $method = 'index';
                    $params = [];
                    
                    // Route to specific action if provided (e.g., /pos/products/create or /pos/products/edit/5)
                    if (isset($segments[3])) {
                        if (method_exists($controller, $segments[3])) {
                            $method = $segments[3];
                            // Remaining segments are params
                            $params = array_slice($segments, 4);
                        } else {
                            // If index takes an ID as first param (e.g., /pos/orders/5 -> index(5) or show(5)?)
                            // Standardize: If 3rd segment exists and it's numeric, maybe it's show?
                            if (is_numeric($segments[3])) {
                                if (method_exists($controller, 'show')) {
                                    $method = 'show';
                                    $params = [$segments[3]];
                                } elseif (method_exists($controller, 'view')) {
                                    $method = 'view';
                                    $params = [$segments[3]];
                                }
                            }
                        }
                    }

                    call_user_func_array([$controller, $method], $params);
                    exit;
                }
            }
        }
    }

    // 3. Root/Home Page
    if ($path === '/' || $path === '') {
        include $baseDir . '/public/index.php';
        exit;
    }

    // 4. Default 404
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
    echo "Path: " . htmlspecialchars($path);

} catch (Throwable $e) {
    echo "<div style='padding:20px; background:#fff1f2; color:#be123c; border:1px solid #fda4af; border-radius:8px; font-family:sans-serif; margin:20px; line-height:1.5;'>";
    echo "<h2 style='margin-top:0; border-bottom:1px solid #fda4af; padding-bottom:10px;'>System Error (Captured)</h2>";
    echo "<strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line: " . $e->getLine() . ")<br>";
    echo "<details style='margin-top:10px;'><summary>Click for Stack Trace</summary><pre style='font-size:12px; overflow:auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre></details>";
    echo "</div>";
}
?>