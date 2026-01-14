<?php
$orders = $orders ?? [];

$totalOrders = count($orders);
$completedOrders = 0;
$pendingOrders = 0;
$cancelledOrders = 0;
$totalSales = 0.0;

foreach ($orders as $o) {
    $status = $o['status'] ?? '';
    if ($status === 'completed') {
        $completedOrders++;
        $totalSales += (float)($o['total'] ?? 0);
    } elseif ($status === 'pending') {
        $pendingOrders++;
    } elseif ($status === 'cancelled') {
        $cancelledOrders++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Order Management</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #0f1220;
            --panel: rgba(255, 255, 255, 0.06);
            --panel2: rgba(255, 255, 255, 0.08);
            --border: rgba(255, 255, 255, 0.12);
            --text: rgba(255, 255, 255, 0.92);
            --muted: rgba(255, 255, 255, 0.70);
            --brandA: #667eea;
            --brandB: #764ba2;
            --ok: #28a745;
            --warn: #ffc107;
            --danger: #dc3545;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: radial-gradient(1200px 600px at 20% -10%, rgba(102, 126, 234, 0.35), transparent 55%),
                        radial-gradient(900px 600px at 100% 0%, rgba(118, 75, 162, 0.35), transparent 55%),
                        var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 18px 20px 26px;
        }

        .page-header {
            display: grid;
            gap: 10px;
            margin: 18px 0 16px;
        }

        .page-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .page-title h1 {
            font-size: 1.6rem;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .page-title h1 i { color: #66e08a; }
        .page-title p { color: var(--muted); margin-top: 4px; }

        .toolbar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.10);
            color: var(--text);
            text-decoration: none;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .btn:hover { transform: translateY(-1px); background: rgba(255, 255, 255, 0.14); }
        .btn.primary { background: #007bff; border-color: #007bff; }
        .btn.primary:hover { background: #0056b3; }
        .btn.success { background: #28a745; border-color: #28a745; }
        .btn.success:hover { background: #1e7e34; }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(180px, 1fr));
            gap: 12px;
            margin-top: 4px;
        }

        .stat {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.35);
        }
        .stat .label { color: var(--muted); font-weight: 700; font-size: 12px; display:flex; gap:8px; align-items:center; }
        .stat .value { font-size: 1.4rem; font-weight: 900; margin-top: 6px; }

        .panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.35);
            margin-top: 14px;
        }

        .filters {
            display: grid;
            grid-template-columns: 1fr 220px 220px;
            gap: 10px;
            align-items: center;
        }

        .input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.16);
            background: rgba(255,255,255,0.08);
            color: var(--text);
            outline: none;
        }
        .input:focus { border-color: rgba(124,140,255,0.8); box-shadow: 0 0 0 3px rgba(124,140,255,0.15); }

        .table-wrap {
            margin-top: 12px;
            overflow: auto;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: rgba(0,0,0,0.20);
        }

        table { width: 100%; border-collapse: collapse; min-width: 860px; }
        thead th {
            position: sticky;
            top: 0;
            background: rgba(18, 18, 18, 0.95);
            color: rgba(255,255,255,0.85);
            text-align: left;
            padding: 14px;
            font-size: 12px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
        }
        tbody td {
            padding: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.90);
        }
        tbody tr:hover { background: rgba(255,255,255,0.06); }
        .muted { color: var(--muted); }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 12px;
            border: 1px solid;
        }
        .badge.ok { background: rgba(40,167,69,0.18); color: #66e08a; border-color: rgba(40,167,69,0.35); }
        .badge.warn { background: rgba(255,193,7,0.18); color: #ffd26a; border-color: rgba(255,193,7,0.35); }
        .badge.danger { background: rgba(220,53,69,0.18); color: #ff7d8c; border-color: rgba(220,53,69,0.35); }

        .actions-cell { display: flex; gap: 8px; flex-wrap: wrap; }
        .btn.small { padding: 8px 10px; border-radius: 10px; font-size: 12px; }
        .btn.ghost { background: rgba(255,255,255,0.08); }

        /* Navigation */
        .navbar {
            background: #2a2a2a;
            border-bottom: 1px solid #404040;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            max-width: none;
            margin: 0;
        }

        .nav-brand {
            font-size: 1.5em;
            font-weight: 700;
            color: #667eea;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-brand i {
            color: #28a745;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 5px;
        }

        .nav-menu li a {
            color: #ffffff;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: block;
            font-weight: 500;
        }

        .nav-menu li a:hover,
        .nav-menu li a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .nav-menu .dropdown {
            position: relative;
        }

        .nav-menu .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: #333;
            border: 1px solid #555;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            min-width: 180px;
            z-index: 1001;
        }

        .nav-menu .dropdown:hover .dropdown-content {
            display: block;
        }

        .nav-menu .dropdown-content a {
            padding: 12px 16px;
            border-radius: 0;
            margin: 0;
            border-bottom: 1px solid #444;
            color: #ccc;
        }

        .nav-menu .dropdown-content a:last-child {
            border-bottom: none;
        }

        .nav-menu .dropdown-content a:hover {
            background: #444;
            color: white;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 5px;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: #fff;
            margin: 3px 0;
            transition: 0.3s;
            border-radius: 2px;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
        }
      .nav-menu.active {
            display: flex;
            flex-direction: column;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #2a2a2a;
            border: 1px solid #404040;
            border-top: none;
            padding: 20px;
            gap: 10px;
        }

        .nav-menu.active .dropdown-content {
            position: static;
            display: block;
            box-shadow: none;
            border: none;
            background: transparent;
            margin-top: 10px;
        }

        .nav-menu.active .dropdown-content a {
            padding: 10px 20px;
        }
        @media (max-width: 1100px) {
            .filters { grid-template-columns: 1fr; }
            table { min-width: 0; }
        }

        @media (max-width: 768px) {
            .container { padding: 14px 14px 22px; }
            .stats { grid-template-columns: repeat(2, minmax(160px, 1fr)); }
            .nav-menu { display: none; }
            .hamburger { display: flex; }

            table, thead, tbody, th, td, tr { display: block; }
            table { min-width: 0; }
            thead { display: none; }
            tbody tr {
                border-bottom: 1px solid rgba(255,255,255,0.10);
                padding: 10px;
            }
            tbody td {
                display: flex;
                justify-content: space-between;
                gap: 12px;
                padding: 10px 0;
                border-bottom: 1px dashed rgba(255,255,255,0.10);
            }
            tbody td:last-child { border-bottom: none; }
            tbody td::before {
                content: attr(data-label);
                color: rgba(255,255,255,0.65);
                font-weight: 800;
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.06em;
            }
            .actions-cell { justify-content: flex-end; }
        }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'orders'; include __DIR__ . '/partials/navbar.php'; ?>
    <div class="container">
        <div class="page-header">
            <div class="page-title">
                <div>
                    <h1><i class="fas fa-receipt"></i> Order Management</h1>
                    <p><?php echo htmlspecialchars(Tenant::getCurrent()['name']); ?> • Track sales and manage orders</p>
                </div>
                <div class="toolbar">
                    <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/pos" class="btn primary"><i class="fas fa-plus"></i> New Sale</a>
                    <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/dashboard" class="btn"><i class="fas fa-arrow-left"></i> Back to POS</a>
                </div>
            </div>

            <div class="stats">
                <div class="stat">
                    <div class="label"><i class="fas fa-list"></i> Total Orders</div>
                    <div class="value"><?php echo number_format($totalOrders); ?></div>
                </div>
                <div class="stat">
                    <div class="label"><i class="fas fa-circle-check" style="color:#66e08a;"></i> Completed</div>
                    <div class="value"><?php echo number_format($completedOrders); ?></div>
                </div>
                <div class="stat">
                    <div class="label"><i class="fas fa-clock" style="color:#ffd26a;"></i> Pending</div>
                    <div class="value"><?php echo number_format($pendingOrders); ?></div>
                </div>
                <div class="stat">
                    <div class="label"><i class="fas fa-dollar-sign"></i> Sales (Completed)</div>
                    <div class="value">$<?php echo number_format($totalSales, 2); ?></div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="filters">
                <input type="text" id="searchInput" class="input" placeholder="Search by order #, customer, status, date…" onkeyup="searchOrders()">
                <select id="statusFilter" class="input" onchange="searchOrders()">
                    <option value="">All statuses</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button class="btn" type="button" onclick="clearFilters()"><i class="fas fa-eraser"></i> Clear Filters</button>
            </div>

            <div class="table-wrap">
                <table id="ordersTable">
                    <thead>
                        <tr>
                            <th style="width: 110px;">Order</th>
                            <th>Customer</th>
                            <th style="width: 140px;">Total</th>
                            <th style="width: 160px;">Status</th>
                            <th style="width: 190px;">Created</th>
                            <th style="width: 320px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="6" class="muted" style="padding: 18px;">
                                    No orders found. Create your first sale with <a class="btn small primary" href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/pos"><i class="fas fa-plus"></i> New Sale</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <?php
                                    $status = $order['status'] ?? 'pending';
                                    $badgeClass = 'warn';
                                    $statusIcon = '<i class="fas fa-clock"></i>';
                                    if ($status === 'completed') {
                                        $badgeClass = 'ok';
                                        $statusIcon = '<i class="fas fa-circle-check"></i>';
                                    } elseif ($status === 'cancelled') {
                                        $badgeClass = 'danger';
                                        $statusIcon = '<i class="fas fa-circle-xmark"></i>';
                                    }
                                ?>
                                <tr>
                                    <td data-label="Order"><strong>#<?php echo (int)$order['id']; ?></strong></td>
                                    <td data-label="Customer"><?php echo htmlspecialchars($order['customer_name'] ?? 'Walk-in Customer'); ?></td>
                                    <td data-label="Total"><strong>$<?php echo number_format((float)$order['total'], 2); ?></strong></td>
                                    <td data-label="Status"><span class="badge <?php echo $badgeClass; ?>"><?php echo $statusIcon; ?> <?php echo htmlspecialchars(ucfirst($status)); ?></span></td>
                                    <td data-label="Created" class="muted"><?php echo date('M j, Y H:i', strtotime($order['created_at'])); ?></td>
                                    <td data-label="Actions">
                                        <div class="actions-cell">
                                            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders/<?php echo (int)$order['id']; ?>" class="btn small ghost"><i class="fas fa-eye"></i> View</a>
                                            <?php if (($order['status'] ?? '') === 'pending'): ?>
                                                <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders/<?php echo (int)$order['id']; ?>/complete" class="btn small success" data-pos-confirm="Are you sure you want to complete this order?"><i class="fas fa-circle-check"></i> Complete</a>
                                            <?php endif; ?>
                                            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders/<?php echo (int)$order['id']; ?>/receipt" class="btn small"><i class="fas fa-print"></i> Receipt</a>
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
            const query = (document.getElementById('searchInput').value || '').trim().toLowerCase();
            const status = (document.getElementById('statusFilter').value || '').trim().toLowerCase();
            const table = document.getElementById('ordersTable');
            const rows = table.tBodies[0].rows;

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.cells;
                if (!cells || cells.length < 6) continue;

                const text = row.textContent.toLowerCase();
                const statusCell = (cells[3]?.textContent || '').trim().toLowerCase();
                const matchesQuery = !query || text.indexOf(query) > -1;
                const matchesStatus = !status || statusCell.indexOf(status) > -1;

                row.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
            }
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            searchOrders();
        }

        function toggleMenu() {
            const hamburger = document.querySelector('.hamburger');
            const navMenu = document.querySelector('.nav-menu');
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        }
    </script>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>