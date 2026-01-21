<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Order History</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    <style>
        .ord-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .ord-row { background: white; border-radius: 12px; transition: all 0.2s; box-shadow: var(--pos-shadow-sm); }
        .ord-row:hover { transform: translateY(-2px); box-shadow: var(--pos-shadow-md); }
        .ord-td { padding: 20px; border: none; }
        .ord-th { padding: 12px 20px; font-size: 11px; text-transform: uppercase; color: var(--pos-muted); font-weight: 800; letter-spacing: 0.5px; }
        .status-pill { padding: 6px 12px; border-radius: 999px; font-weight: 800; font-size: 11px; text-transform: uppercase; }
        .status-completed { background: #f0fdf4; color: #16a34a; }
        .status-pending { background: #fff7ed; color: #ea580c; }
        .status-cancelled { background: #fef2f2; color: #dc2626; }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'orders'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="fade-in">
        <div class="pos-row" style="margin-bottom: 24px;">
            <div class="pos-title">
                <h1 class="text-gradient">Order History</h1>
                <p>Track all sales transactions and order statuses.</p>
            </div>
            <div style="display:flex; gap:12px;">
                <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos" class="pos-pill" style="padding: 12px 24px;">
                    <i class="fas fa-plus"></i> POS
                </a>
            </div>
        </div>

        <div class="pos-grid cols-4" style="margin-bottom: 24px;">
            <div class="pos-stat pos-shadow-sm" style="border: none;">
                <div class="k">Total Orders</div>
                <div class="v"><?php echo count($orders); ?></div>
                <div class="chip" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i class="fas fa-receipt"></i></div>
            </div>
        </div>

        <div class="pos-card pos-shadow-sm" style="padding: 30px; border: none;">
            <div class="pos-topbar__search" style="max-width: 100%; margin-bottom: 24px; background: #f8fafc; border: 1px solid var(--pos-border);">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search by Order ID, Customer Name or Status..." onkeyup="searchOrders()" style="font-weight: 700;">
            </div>

            <div style="overflow:auto;">
                <table class="ord-table" id="ordersTable">
                    <thead>
                        <tr>
                            <th class="ord-th">Order ID</th>
                            <th class="ord-th">Customer</th>
                            <th class="ord-th">Date & Time</th>
                            <th class="ord-th">Total Amount</th>
                            <th class="ord-th">Status</th>
                            <th class="ord-th" style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="6" style="padding: 60px; text-align: center; color: var(--pos-muted);">
                                    <i class="fas fa-receipt" style="font-size: 40px; opacity: 0.2; margin-bottom: 16px; display: block;"></i>
                                    <p style="font-weight: 700;">No orders found yet.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr class="ord-row">
                                    <td class="ord-td" style="border-radius: 12px 0 0 12px;">
                                        <div style="font-weight: 900; font-size: 15px; color: var(--pos-brand-a);">#<?php echo $order['id']; ?></div>
                                    </td>
                                    <td class="ord-td">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="width: 32px; height: 32px; border-radius: 8px; background: #f1f5f9; display: grid; place-items: center; font-size: 12px; font-weight: 800; color: var(--pos-muted);">
                                                <?php echo strtoupper(substr($order['customer_name'] ?? 'W', 0, 1)); ?>
                                            </div>
                                            <div style="font-weight: 700; color: var(--pos-text);"><?php echo htmlspecialchars($order['customer_name'] ?? 'Walk-in Customer'); ?></div>
                                        </div>
                                    </td>
                                    <td class="ord-td">
                                        <div style="font-size: 13px; font-weight: 600; color: var(--pos-text);"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></div>
                                        <div style="font-size: 11px; color: var(--pos-muted); font-weight: 700;"><?php echo date('H:i A', strtotime($order['created_at'])); ?></div>
                                    </td>
                                    <td class="ord-td">
                                        <div style="font-weight: 900; color: var(--pos-text); font-size: 16px;">$<?php echo number_format($order['total'], 2); ?></div>
                                    </td>
                                    <td class="ord-td">
                                        <span class="status-pill status-<?php echo $order['status']; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td class="ord-td" style="text-align: right; border-radius: 0 12px 12px 0;">
                                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders/<?php echo $order['id']; ?>" class="pos-icon-btn" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders/<?php echo $order['id']; ?>/receipt" class="pos-icon-btn" target="_blank" title="Print Receipt">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function searchOrders() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('ordersTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const tdId = tr[i].getElementsByTagName('td')[0];
                const tdCust = tr[i].getElementsByTagName('td')[1];
                if (tdId || tdCust) {
                    const txtValueId = tdId ? (tdId.textContent || tdId.innerText) : "";
                    const txtValueCust = tdCust ? (tdCust.textContent || tdCust.innerText) : "";
                    
                    if (txtValueId.toUpperCase().indexOf(filter) > -1 || txtValueCust.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }
    </script>
    
    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
