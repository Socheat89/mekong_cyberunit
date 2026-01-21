<?php
// index.php - Front Controller
session_start();

// Ensure consistent timezone for all date/time formatting
// Cambodia timezone (UTC+7)
date_default_timezone_set('Asia/Phnom_Penh');

require_once 'core/classes/Database.php';
require_once 'core/classes/Tenant.php';
require_once 'core/classes/Auth.php';

// Define base path for the project
$basePath = '/Mekong_CyberUnit';
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Strip base path if present
if (strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}
if (empty($path)) $path = '/';

// Function to route POS actions
function routePOSAction($action) {
    if ($action === 'dashboard') {
        require_once 'modules/pos/controllers/DashboardController.php';
        $controller = new DashboardController();
        $controller->index();
    } elseif ($action === 'pos') {
        require_once 'modules/pos/controllers/PosController.php';
        $controller = new PosController();
        $controller->index();
    } elseif ($action === 'holds') {
        require_once 'modules/pos/controllers/OrderController.php';
        $controller = new OrderController();
        $controller->holds();
    } elseif ($action === 'order' || $action === 'orders/create') {
        require_once 'modules/pos/controllers/OrderController.php';
        $controller = new OrderController();
        $controller->create();
    } elseif (preg_match('/^orders$/', $action)) {
        require_once 'modules/pos/controllers/OrderController.php';
        $controller = new OrderController();
        $controller->index();
    } elseif (preg_match('/^orders\/(\d+)$/', $action, $matches)) {
        require_once 'modules/pos/controllers/OrderController.php';
        $controller = new OrderController();
        $controller->show($matches[1]);
    } elseif (preg_match('/^orders\/(\d+)\/receipt$/', $action, $matches)) {
        require_once 'modules/pos/controllers/OrderController.php';
        $controller = new OrderController();
        $controller->receipt($matches[1]);
    } elseif (preg_match('/^orders\/(\d+)\/complete$/', $action, $matches)) {
        require_once 'modules/pos/controllers/OrderController.php';
        $controller = new OrderController();
        $controller->complete($matches[1]);
    } elseif ($action === 'products') {
        require_once 'modules/pos/controllers/ProductController.php';
        $controller = new ProductController();
        $controller->index();
    } elseif ($action === 'products/create') {
        require_once 'modules/pos/controllers/ProductController.php';
        $controller = new ProductController();
        $controller->create();
    } elseif (preg_match('/^products\/(\d+)\/edit$/', $action, $matches)) {
        require_once 'modules/pos/controllers/ProductController.php';
        $controller = new ProductController();
        $controller->edit($matches[1]);
    } elseif (preg_match('/^products\/(\d+)\/delete$/', $action, $matches)) {
        require_once 'modules/pos/controllers/ProductController.php';
        $controller = new ProductController();
        $controller->delete($matches[1]);
    } elseif ($action === 'customers') {
        require_once 'modules/pos/controllers/CustomerController.php';
        $controller = new CustomerController();
        $controller->index();
    } elseif ($action === 'customers/create') {
        require_once 'modules/pos/controllers/CustomerController.php';
        $controller = new CustomerController();
        $controller->create();
    } elseif (preg_match('/^customers\/(\d+)\/edit$/', $action, $matches)) {
        require_once 'modules/pos/controllers/CustomerController.php';
        $controller = new CustomerController();
        $controller->edit($matches[1]);
    } elseif (preg_match('/^customers\/(\d+)\/delete$/', $action, $matches)) {
        require_once 'modules/pos/controllers/CustomerController.php';
        $controller = new CustomerController();
        $controller->delete($matches[1]);
    } elseif ($action === 'reports') {
        require_once 'modules/pos/controllers/ReportsController.php';
        $controller = new ReportsController();
        $controller->index();
    } elseif ($action === 'settings') {
        require_once 'modules/pos/controllers/SettingsController.php';
        $controller = new SettingsController();
        $controller->index();
    } elseif ($action === 'settings/update') {
        require_once 'modules/pos/controllers/SettingsController.php';
        $controller = new SettingsController();
        $controller->update();
    } else {
        echo 'POS action not found';
    }
}

// Basic routing
// Public routes
if (strpos($path, '/public/') === 0) {
    $file = __DIR__ . $path;
    if (file_exists($file)) {
        include $file;
    } else {
        include 'public/404.php';
    }
    exit;
}

// Direct POS access for development (localhost) - MOVED UP
if (preg_match('/^\/pos\/(.+)/', $path, $matches)) {
    $action = $matches[1];
    
    // For development, use a default tenant and auto-login first user
    try {
        // Try to find a default tenant or the first active tenant
        $db = Database::getInstance();
        $defaultTenant = $db->fetchOne("SELECT * FROM tenants WHERE status = 'active' LIMIT 1");
        if ($defaultTenant) {
            Tenant::detect($defaultTenant['subdomain']);
            
            // Auto-login the first user of this tenant for development
            $firstUser = $db->fetchOne("SELECT * FROM users WHERE tenant_id = ? AND status = 'active' LIMIT 1", [$defaultTenant['id']]);
            if ($firstUser) {
                $_SESSION['user_id'] = $firstUser['id'];
                $_SESSION['tenant_id'] = $firstUser['tenant_id'];
                $_SESSION['tenant_subdomain'] = $defaultTenant['subdomain'];
                $_SESSION['role_level'] = $firstUser['role_level'] ?? 1;
            }
            
            routePOSAction($action);
        } else {
            echo 'No active tenant found. Please create a tenant first.';
        }
    } catch (Exception $e) {
        echo 'Error accessing POS: ' . $e->getMessage();
    }
    exit;
}

// Handle direct POS routes without /pos/ prefix (for development convenience)
if (preg_match('/^\/(products|orders|customers|reports)\/?.*/', $path)) {
    // Redirect to /pos/{path} for proper routing
    $redirectPath = '/pos' . $path;
    header("Location: $redirectPath");
    exit;
}

// Tenant admin routes (using session tenant)
if (preg_match('/^\/tenant\/(.+)/', $path, $matches)) {
    $modulePath = $matches[1];
    // Strip .php if present
    $modulePath = preg_replace('/\.php$/', '', $modulePath);

    if (!isset($_SESSION['tenant_id'])) {
        header('Location: /Mekong_CyberUnit/public/login.php');
        exit;
    }

    $db = Database::getInstance();
    $tenant = $db->fetchOne("SELECT * FROM tenants WHERE id = ? AND status = 'active'", [$_SESSION['tenant_id']]);
    if (!$tenant) {
        header('Location: /Mekong_CyberUnit/public/login.php');
        exit;
    }

    Tenant::setCurrent($tenant);

    if ($modulePath === 'dashboard') {
        include 'tenant/dashboard.php';
    } elseif ($modulePath === 'users') {
        include 'tenant/users.php';
    } elseif ($modulePath === 'settings') {
        include 'tenant/settings.php';
    } elseif ($modulePath === 'logout') {
        include 'public/logout.php';
    } else {
        echo 'Page not found';
    }
    exit;
}

// Tenant routes
if (preg_match('/^\/([^\/]+)\/(.+)/', $path, $matches)) {
    $tenantSlug = $matches[1];
    $modulePath = $matches[2];

    try {
        Tenant::detect($tenantSlug); // Pass the tenant slug

        // Check if tenant matches session
        if (isset($_SESSION['tenant_subdomain']) && $_SESSION['tenant_subdomain'] !== $tenantSlug) {
            header('Location: /Mekong_CyberUnit/tenant/dashboard');
            exit;
        }

        // Dashboard
        if ($modulePath === 'dashboard') {
            include 'tenant/dashboard.php';
            exit;
        }

        // Logout
        if ($modulePath === 'logout') {
            include 'public/logout.php';
            exit;
        }

        // Route to modules
        if (preg_match('/^pos\/(.+)/', $modulePath, $matches)) {
            $action = $matches[1];
            routePOSAction($action);
        } elseif (preg_match('/^(products|orders|customers|reports)\/?.*/', $modulePath, $matches)) {
            // Handle direct module access without /pos/ prefix
            $action = $matches[0];
            routePOSAction($action);
        } else {
            echo 'Module not found';
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage() . '<br>';
        echo 'Requested tenant: ' . htmlspecialchars($tenantSlug) . '<br>';
        echo 'Available tenants: ';
        $db = Database::getInstance();
        $tenants = $db->fetchAll("SELECT subdomain FROM tenants WHERE status = 'active'");
        foreach ($tenants as $tenant) {
            echo htmlspecialchars($tenant['subdomain']) . ' ';
        }
    }
    exit;
}

// Direct POS access for development (localhost) - MOVED UP
if (preg_match('/^\/pos\/(.+)/', $path, $matches)) {
    $action = $matches[1];

    // For development, use a default tenant and auto-login first user
    try {
        // Try to find a default tenant or the first active tenant
        $db = Database::getInstance();
        $defaultTenant = $db->fetchOne("SELECT * FROM tenants WHERE status = 'active' LIMIT 1");
        if ($defaultTenant) {
            Tenant::detect($defaultTenant['subdomain']);
            
            // Auto-login the first user of this tenant for development
            $firstUser = $db->fetchOne("SELECT * FROM users WHERE tenant_id = ? AND status = 'active' LIMIT 1", [$defaultTenant['id']]);
            if ($firstUser) {
                $_SESSION['user_id'] = $firstUser['id'];
                $_SESSION['tenant_id'] = $firstUser['tenant_id'];
                $_SESSION['tenant_subdomain'] = $defaultTenant['subdomain'];
                $_SESSION['role_level'] = $firstUser['role_level'] ?? 1;
            }
            
            routePOSAction($action);
        } else {
            echo 'No active tenant found. Please create a tenant first.';
        }
    } catch (Exception $e) {
        echo 'Error accessing POS: ' . $e->getMessage();
    }
    exit;
}

// Admin routes (for super admin)
if (strpos($path, '/admin/') === 0) {
    // Handle admin routes
    echo 'Admin panel - TODO';
    exit;
}

// Default to public
include 'public/index.php';
?>