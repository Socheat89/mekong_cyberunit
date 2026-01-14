<?php
$stats = $stats ?? [];
$recentOrders = $recentOrders ?? [];
$salesByMonth = $salesByMonth ?? [];
$topProducts = $topProducts ?? [];
$lowStockItems = $lowStockItems ?? [];

$tenant = class_exists('Tenant') ? (Tenant::getCurrent() ?? []) : [];
$tenantName = is_array($tenant) && !empty($tenant['name']) ? $tenant['name'] : 'Tenant';

$fmtMoney = function($value): string {
    return '$' . number_format((float)$value, 2);
};

$labels = array_keys($salesByMonth);
$values = array_values($salesByMonth);

// Friendly labels like Jan
$friendlyLabels = [];
foreach ($labels as $ym) {
    $dt = DateTime::createFromFormat('Y-m', $ym);
    $friendlyLabels[] = $dt ? $dt->format('M') : $ym;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Dashboard</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* page-only bits */
        .pos-chart-wrap { height: 320px; padding: 18px; }
        .pos-card-title { margin: 0; font-size: 14px; font-weight: 900; letter-spacing: 0.2px; }
        .pos-card-sub { margin: 6px 0 0; color: var(--pos-muted); font-weight: 650; font-size: 12px; }
        .pos-recent { padding: 14px; }
        .pos-recent table { width: 100%; border-collapse: collapse; }
        .pos-recent th, .pos-recent td { text-align: left; padding: 10px 8px; border-bottom: 1px solid var(--pos-border); font-weight: 650; }
        .pos-recent th { font-size: 12px; color: var(--pos-muted); text-transform: uppercase; letter-spacing: 0.6px; }
        .pos-badge { display:inline-flex; align-items:center; padding: 5px 10px; border-radius: 999px; font-size: 12px; font-weight: 900; }
        .pos-badge.ok { background: rgba(34, 197, 94, 0.12); color: rgba(20, 110, 55, 0.95); }
        .pos-badge.warn { background: rgba(245, 158, 11, 0.14); color: rgba(146, 87, 12, 0.95); }
        .pos-badge.bad { background: rgba(239, 68, 68, 0.14); color: rgba(160, 20, 20, 0.95); }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'dashboard'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="pos-row" style="margin-bottom: 14px;">
        <div class="pos-title">
            <h1>Hello <?php echo htmlspecialchars($tenantName); ?> 👋</h1>
            <p>Welcome to your point-of-sale dashboard</p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a class="pos-pill" href="/Mekong_CyberUnit/<?php echo htmlspecialchars($tenant['subdomain'] ?? 'pos'); ?>/pos/pos"><i class="fas fa-plus"></i> New Sale</a>
        </div>
    </div>

    <div class="pos-grid cols-4" style="margin-bottom: 14px;">
        <div class="pos-stat">
            <div class="k">Total Orders</div>
            <div class="v"><?php echo (int)($stats['total_orders'] ?? 0); ?></div>
            <div class="chip"><i class="fas fa-receipt"></i> Completed</div>
        </div>
        <div class="pos-stat">
            <div class="k">Total Sales</div>
            <div class="v"><?php echo htmlspecialchars($fmtMoney($stats['total_sales'] ?? 0)); ?></div>
            <div class="chip"><i class="fas fa-chart-line"></i> Revenue</div>
        </div>
        <div class="pos-stat">
            <div class="k">Products</div>
            <div class="v"><?php echo (int)($stats['total_products'] ?? 0); ?></div>
            <div class="chip"><i class="fas fa-box"></i> Active</div>
        </div>
        <div class="pos-stat">
            <div class="k">Low Stock</div>
            <div class="v"><?php echo (int)($stats['low_stock_count'] ?? 0); ?></div>
            <div class="chip"><i class="fas fa-triangle-exclamation"></i> Under 10</div>
        </div>
    </div>

    <div class="pos-grid cols-2">
        <div class="pos-card">
            <div class="pos-chart-wrap">
                <div class="pos-row" style="margin-bottom: 10px;">
                    <div>
                        <p class="pos-card-title">Earning History</p>
                        <p class="pos-card-sub">Last 12 months (completed orders)</p>
                    </div>
                </div>
                <canvas id="salesChart" aria-label="Sales chart" role="img"></canvas>
            </div>

            <div class="pos-recent">
                <p class="pos-card-title" style="margin-bottom:10px;">Recent Orders</p>
                <div style="overflow:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($recentOrders)): ?>
                            <tr><td colspan="5" class="pos-small">No recent orders.</td></tr>
                        <?php else: ?>
                            <?php foreach ($recentOrders as $o):
                                $status = (string)($o['status'] ?? '');
                                $badge = 'warn';
                                if ($status === 'completed') $badge = 'ok';
                                elseif ($status === 'cancelled') $badge = 'bad';
                            ?>
                            <tr>
                                <td>#<?php echo (int)($o['id'] ?? 0); ?></td>
                                <td><?php echo htmlspecialchars($o['customer_name'] ?? 'Walk-in'); ?></td>
                                <td><?php echo htmlspecialchars($fmtMoney($o['total'] ?? 0)); ?></td>
                                <td><span class="pos-badge <?php echo $badge; ?>"><?php echo htmlspecialchars(ucfirst($status ?: 'pending')); ?></span></td>
                                <td class="pos-small"><?php echo htmlspecialchars($o['created_at'] ?? ''); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="pos-grid" style="gap: 14px;">
            <div class="pos-card pad">
                <p class="pos-card-title">Notifications</p>
                <p class="pos-card-sub">Stock & operational updates</p>
                <ul class="pos-list" style="margin-top: 12px;">
                    <?php if (empty($lowStockItems)): ?>
                        <li class="pos-list-item"><span class="pos-dot"></span><div><div style="font-weight:900;">All good</div><div class="pos-small">No low-stock products right now.</div></div></li>
                    <?php else: ?>
                        <?php foreach ($lowStockItems as $p): ?>
                            <li class="pos-list-item">
                                <span class="pos-dot"></span>
                                <div>
                                    <div style="font-weight:900;">Low stock: <?php echo htmlspecialchars($p['name'] ?? 'Product'); ?></div>
                                    <div class="pos-small">Remaining: <?php echo (int)($p['stock_quantity'] ?? 0); ?></div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="pos-card pad">
                <p class="pos-card-title">Top Products</p>
                <p class="pos-card-sub">By quantity sold</p>
                <ul class="pos-list" style="margin-top: 12px;">
                    <?php if (empty($topProducts)): ?>
                        <li class="pos-list-item"><span class="pos-dot"></span><div><div style="font-weight:900;">No sales yet</div><div class="pos-small">Complete a sale to see rankings.</div></div></li>
                    <?php else: ?>
                        <?php foreach ($topProducts as $tp): ?>
                            <li class="pos-list-item">
                                <span class="pos-dot"></span>
                                <div style="flex:1;">
                                    <div style="display:flex; justify-content:space-between; gap:10px;">
                                        <div style="font-weight:900; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                            <?php echo htmlspecialchars($tp['name'] ?? 'Product'); ?>
                                        </div>
                                        <div class="pos-small" style="white-space:nowrap;">Qty: <?php echo (int)($tp['qty'] ?? 0); ?></div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        (function() {
            var el = document.getElementById('salesChart');
            if (!el) return;

            var labels = <?php echo json_encode($friendlyLabels, JSON_UNESCAPED_SLASHES); ?>;
            var data = <?php echo json_encode($values, JSON_UNESCAPED_SLASHES); ?>;

            // If no data, show an empty chart with 12 months placeholders.
            if (!labels || labels.length === 0) {
                labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                data = new Array(labels.length).fill(0);
            }

            new Chart(el, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales',
                        data: data,
                        borderColor: 'rgba(106, 92, 255, 0.95)',
                        backgroundColor: 'rgba(106, 92, 255, 0.10)',
                        fill: true,
                        tension: 0.38,
                        pointRadius: 2,
                        pointHoverRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: 'rgba(32, 34, 50, 0.65)', font: { weight: 700 } }
                        },
                        y: {
                            grid: { color: 'rgba(32, 34, 50, 0.08)' },
                            ticks: { color: 'rgba(32, 34, 50, 0.55)' }
                        }
                    }
                }
            });
        })();
    </script>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>