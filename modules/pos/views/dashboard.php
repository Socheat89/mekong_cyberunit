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
    <link href="/Mekong_CyberUnit/public/css/pos_template.css?v=<?php echo time(); ?>" rel="stylesheet">
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

    <div class="fade-in">
        <div class="pos-row" style="margin-bottom: 24px; background: var(--pos-gradient-indigo); padding: 40px; border-radius: var(--pos-radius); color: white; box-shadow: var(--pos-shadow-lg);">
            <div class="pos-title">
                <h1 style="font-size: 32px; font-weight: 900; margin: 0;">Hello <?php echo htmlspecialchars($tenantName); ?> 👋</h1>
                <p style="color: rgba(255,255,255,0.85); font-size: 16px; margin-top: 8px;">Your business is performing great today. Here's what's happening.</p>
            </div>
            <div style="display:flex; gap:12px;">
                <a class="pos-pill glass" href="/Mekong_CyberUnit/<?php echo htmlspecialchars($tenant['subdomain'] ?? 'pos'); ?>/pos/pos" style="padding: 14px 24px; border: 1px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.2); color: white;">
                    <i class="fas fa-plus"></i> New Sale
                </a>
            </div>
        </div>

        <div class="pos-grid cols-4" style="margin-bottom: 24px;">
            <div class="pos-stat pos-shadow-sm" style="border: none; padding: 24px;">
                <div class="chip" style="background: rgba(99, 102, 241, 0.1); color: #6366f1; margin: 0 0 16px;"><i class="fas fa-receipt"></i></div>
                <div class="k">Total Orders</div>
                <div class="v" style="font-size: 28px;"><?php echo (int)($stats['total_orders'] ?? 0); ?></div>
                <div class="pos-small" style="margin-top: 8px;"><i class="fas fa-arrow-up" style="color: #22c55e;"></i> <span style="color: #22c55e; font-weight: 800;">12%</span> since last week</div>
            </div>
            <div class="pos-stat pos-shadow-sm" style="border: none; padding: 24px;">
                <div class="chip" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; margin: 0 0 16px;"><i class="fas fa-chart-line"></i></div>
                <div class="k">Total Sales</div>
                <div class="v" style="font-size: 28px;"><?php echo htmlspecialchars($fmtMoney($stats['total_sales'] ?? 0)); ?></div>
                <div class="pos-small" style="margin-top: 8px;"><i class="fas fa-arrow-up" style="color: #22c55e;"></i> <span style="color: #22c55e; font-weight: 800;">8%</span> since last week</div>
            </div>
            <div class="pos-stat pos-shadow-sm" style="border: none; padding: 24px;">
                <div class="chip" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9; margin: 0 0 16px;"><i class="fas fa-box"></i></div>
                <div class="k">Products</div>
                <div class="v" style="font-size: 28px;"><?php echo (int)($stats['total_products'] ?? 0); ?></div>
                <div class="pos-small" style="margin-top: 8px;"><span style="font-weight: 800; color: var(--pos-text);">Active inventory</span></div>
            </div>
            <div class="pos-stat pos-shadow-sm" style="border: none; padding: 24px;">
                <div class="chip" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; margin: 0 0 16px;"><i class="fas fa-triangle-exclamation"></i></div>
                <div class="k">Low Stock</div>
                <div class="v" style="font-size: 28px;"><?php echo (int)($stats['low_stock_count'] ?? 0); ?></div>
                <div class="pos-small" style="margin-top: 8px;"><span style="font-weight: 800; color: #ef4444;">Requires attention</span></div>
            </div>
        </div>

        <div class="pos-grid cols-2">
            <div class="pos-card pos-shadow-sm" style="border: none; overflow: hidden;">
                <div class="pos-chart-wrap" style="padding: 24px;">
                    <div class="pos-row" style="margin-bottom: 20px;">
                        <div>
                            <h3 class="pos-card-title" style="font-size: 18px;">Earning History</h3>
                            <p class="pos-card-sub">Revenue growth over the last 12 months</p>
                        </div>
                    </div>
                    <canvas id="salesChart" style="max-height: 300px;"></canvas>
                </div>

                <div class="pos-recent" style="padding: 24px; border-top: 1px solid var(--pos-border);">
                    <div class="pos-row" style="margin-bottom: 16px;">
                        <h3 class="pos-card-title" style="font-size: 18px;">Recent Orders</h3>
                        <a href="#" style="font-size: 13px; font-weight: 700; color: var(--pos-brand-a); text-decoration: none;">View All</a>
                    </div>
                    <div style="overflow:auto;">
                        <table style="width: 100%; border-collapse: separate; border-spacing: 0 8px;">
                            <thead>
                                <tr style="background: transparent;">
                                    <th style="padding: 12px; font-size: 11px; text-transform: uppercase; color: var(--pos-muted); border: none;">Order #</th>
                                    <th style="padding: 12px; font-size: 11px; text-transform: uppercase; color: var(--pos-muted); border: none;">Customer</th>
                                    <th style="padding: 12px; font-size: 11px; text-transform: uppercase; color: var(--pos-muted); border: none;">Amount</th>
                                    <th style="padding: 12px; font-size: 11px; text-transform: uppercase; color: var(--pos-muted); border: none;">Status</th>
                                    <th style="padding: 12px; font-size: 11px; text-transform: uppercase; color: var(--pos-muted); border: none;">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($recentOrders)): ?>
                                <tr><td colspan="5" style="text-align: center; padding: 40px; color: var(--pos-muted);">No orders found yet.</td></tr>
                            <?php else: ?>
                                <?php foreach ($recentOrders as $o):
                                    $status = (string)($o['status'] ?? '');
                                    $statusClass = 'warn';
                                    if ($status === 'completed') $statusClass = 'ok';
                                    elseif ($status === 'cancelled') $statusClass = 'bad';
                                ?>
                                <tr style="background: #f8fafc; transition: all 0.2s;">
                                    <td style="padding: 14px 12px; border-radius: 12px 0 0 12px; font-weight: 800;">#<?php echo (int)($o['id'] ?? 0); ?></td>
                                    <td style="padding: 14px 12px; font-weight: 700;"><?php echo htmlspecialchars($o['customer_name'] ?? 'Walk-in'); ?></td>
                                    <td style="padding: 14px 12px; font-weight: 800; color: var(--pos-text);"><?php echo htmlspecialchars($fmtMoney($o['total'] ?? 0)); ?></td>
                                    <td style="padding: 14px 12px;"><span class="pos-badge <?php echo $statusClass; ?>" style="padding: 6px 12px;"><?php echo htmlspecialchars(ucfirst($status ?: 'pending')); ?></span></td>
                                    <td style="padding: 14px 12px; border-radius: 0 12px 12px 0; font-size: 12px; color: var(--pos-muted);"><?php echo date('M d, H:i', strtotime($o['created_at'] ?? 'now')); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="pos-grid" style="gap: 20px;">
                <div class="pos-card pos-shadow-sm" style="border: none; padding: 24px;">
                    <h3 class="pos-card-title" style="font-size: 18px; margin-bottom: 20px;">Smart Notifications</h3>
                    <ul class="pos-list">
                        <?php if (empty($lowStockItems)): ?>
                            <li class="pos-list-item" style="border: none; background: #f0fdf4; color: #166534; padding: 16px;">
                                <i class="fas fa-check-circle" style="font-size: 20px; opacity: 0.8;"></i>
                                <div>
                                    <div style="font-weight:900;">Inventory Healthy</div>
                                    <div style="font-size: 12px; opacity: 0.8;">All items are well-stocked.</div>
                                </div>
                            </li>
                        <?php else: ?>
                            <?php foreach (array_slice($lowStockItems, 0, 3) as $p): ?>
                                <li class="pos-list-item" style="border: none; background: #fef2f2; color: #991b1b; padding: 16px;">
                                    <i class="fas fa-triangle-exclamation" style="font-size: 20px; opacity: 0.8;"></i>
                                    <div>
                                        <div style="font-weight:900;"><?php echo htmlspecialchars($p['name']); ?></div>
                                        <div style="font-size: 12px; opacity: 0.8;">Only <?php echo (int)$p['stock_quantity']; ?> left in stock.</div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="pos-card pos-shadow-sm" style="border: none; padding: 24px;">
                    <h3 class="pos-card-title" style="font-size: 18px; margin-bottom: 20px;">Top Selling Products</h3>
                    <div class="pos-list">
                        <?php if (empty($topProducts)): ?>
                            <p style="color: var(--pos-muted); font-size: 14px; text-align: center; padding: 20px;">No top products yet.</p>
                        <?php else: ?>
                            <?php foreach ($topProducts as $idx => $tp): ?>
                                <div style="display: flex; align-items: center; gap: 14px; padding: 12px 0; <?php echo $idx < count($topProducts)-1 ? 'border-bottom: 1px solid var(--pos-border);' : ''; ?>">
                                    <div style="width: 36px; height: 36px; border-radius: 10px; background: #f1f5f9; display: grid; place-items: center; font-weight: 800; font-size: 13px; color: var(--pos-muted);">
                                        <?php echo $idx + 1; ?>
                                    </div>
                                    <div style="flex:1;">
                                        <div style="font-weight:800; color: var(--pos-text); font-size: 14px;"><?php echo htmlspecialchars($tp['name']); ?></div>
                                        <div style="font-size: 12px; color: var(--pos-muted);"><?php echo (int)$tp['qty']; ?> items sold</div>
                                    </div>
                                    <div style="font-weight: 900; color: var(--pos-brand-a);">$<?php echo number_format($tp['qty'] * 25.5, 2); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
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