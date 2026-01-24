<?php
// modules/pos/controllers/DashboardController.php
require_once __DIR__ . '/../../../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../../../middleware/TenantMiddleware.php';

class DashboardController {
    public function index() {
        TenantMiddleware::handle();
        AuthMiddleware::handle();

        if (!Tenant::hasModule('pos')) {
            die('POS system not subscribed for your plan');
        }

        if (!Auth::hasPermission('pos', 'read')) {
            die('No permission to access POS Dashboard');
        }

        $db = Database::getInstance();
        $tenantId = Tenant::getId();

        // Get stats
        $stats = [];

        // Total products
        $stats['total_products'] = $db->fetchOne("SELECT COUNT(*) as count FROM products WHERE tenant_id = ?", [$tenantId])['count'];

        // Total orders (completed only, for consistency with reports)
        $stats['total_orders'] = $db->fetchOne("SELECT COUNT(*) as count FROM orders WHERE tenant_id = ? AND status = 'completed'", [$tenantId])['count'];

        // Total sales
        $salesData = $db->fetchOne("SELECT SUM(total) as total_sales FROM orders WHERE tenant_id = ? AND status = 'completed'", [$tenantId]);
        $stats['total_sales'] = $salesData['total_sales'] ?? 0;

        // Low stock items (less than 10)
        $stats['low_stock_count'] = $db->fetchOne("SELECT COUNT(*) as count FROM products WHERE tenant_id = ? AND stock_quantity < 10", [$tenantId])['count'];

        // Recent orders
        $recentOrders = $db->fetchAll(
            "SELECT o.id, o.total, o.status, o.created_at, c.name as customer_name 
             FROM orders o 
             LEFT JOIN customers c ON o.customer_id = c.id 
             WHERE o.tenant_id = ? 
             ORDER BY o.created_at DESC LIMIT 10",
            [$tenantId]
        );

        // Monthly sales (last 12 months, completed orders only)
        $salesRows = $db->fetchAll(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') as ym, SUM(total) as total_sales
             FROM orders
             WHERE tenant_id = ? AND status = 'completed' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
             GROUP BY ym
             ORDER BY ym ASC",
            [$tenantId]
        );

        $salesByMonth = [];
        if (is_array($salesRows)) {
            foreach ($salesRows as $r) {
                $key = (string)($r['ym'] ?? '');
                if ($key === '') continue;
                $salesByMonth[$key] = (float)($r['total_sales'] ?? 0);
            }
        }

        // Top products (by quantity, completed orders only)
        $topProducts = $db->fetchAll(
            "SELECT p.id, p.name, SUM(oi.quantity) as qty
             FROM order_items oi
             INNER JOIN orders o ON o.id = oi.order_id
             INNER JOIN products p ON p.id = oi.product_id
             WHERE o.tenant_id = ? AND o.status = 'completed'
             GROUP BY p.id, p.name
             ORDER BY qty DESC
             LIMIT 5",
            [$tenantId]
        );

        // Notifications (low stock)
        $lowStockItems = $db->fetchAll(
            "SELECT id, name, stock_quantity
             FROM products
             WHERE tenant_id = ? AND stock_quantity < 10
             ORDER BY stock_quantity ASC
             LIMIT 5",
            [$tenantId]
        );

        include __DIR__ . '/../views/dashboard.php';
    }
}
?>