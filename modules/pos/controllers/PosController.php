<?php
// modules/pos/controllers/PosController.php
require_once __DIR__ . '/../../../core/classes/Database.php';
require_once __DIR__ . '/../../../core/classes/Tenant.php';
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../middleware/TenantMiddleware.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php';

class PosController {
    public function index() {
        TenantMiddleware::handle();
        AuthMiddleware::handle();

        if (!Tenant::hasSystem('POS System')) {
            die('POS system not subscribed');
        }

        // Viewing the POS terminal implies ability to create sales
        if (!Auth::hasPermission('pos', 'write')) {
            die('No permission');
        }

        $products = Product::getAll();
        $customers = $this->getCustomers();

        $resumeOrder = null;
        if (isset($_GET['resume'])) {
            $resumeId = (int)$_GET['resume'];
            if ($resumeId > 0) {
                $resumeOrder = Order::getPendingById($resumeId);
                if (!$resumeOrder) {
                    die('Held order not found');
                }
            }
        }

        include __DIR__ . '/../views/pos.php';
    }

    private function getCustomers() {
        $db = Database::getInstance();
        $tenantId = Tenant::getId();
        return $db->fetchAll("SELECT * FROM customers WHERE tenant_id = ? ORDER BY name", [$tenantId]);
    }
}
?>
