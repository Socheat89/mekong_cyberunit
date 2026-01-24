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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --pos-primary: #6366f1;
            --pos-primary-light: #eef2ff;
            --pos-secondary: #8b5cf6;
            --pos-accent: #0ea5e9;
            --pos-bg: #f8fafc;
            --pos-card-bg: #ffffff;
            --pos-text: #1e293b;
            --pos-text-muted: #64748b;
            --pos-border: #e2e8f0;
            --pos-radius: 24px;
        }

        body.pos-app {
            background-color: var(--pos-bg);
            font-family: 'Inter', sans-serif;
            color: var(--pos-text);
        }

        h1, h2, h3, .pos-card-title {
            font-family: 'Outfit', sans-serif;
        }

        .pos-chart-wrap { height: 350px; padding: 10px; }
        .pos-card-title { margin: 0; font-size: 18px; font-weight: 800; color: var(--pos-text); }
        .pos-card-sub { margin: 4px 0 0; color: var(--pos-text-muted); font-weight: 500; font-size: 13px; }
        
        .pos-recent { padding: 0; }
        .pos-recent table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .pos-recent th { font-size: 11px; font-weight: 700; color: var(--pos-text-muted); text-transform: uppercase; letter-spacing: 1px; padding: 16px 20px; border-bottom: 1px solid var(--pos-border); }
        .pos-recent td { padding: 16px 20px; border-bottom: 1px solid var(--pos-border); font-size: 14px; color: var(--pos-text); font-weight: 600; }
        .pos-recent tr:last-child td { border-bottom: none; }
        .pos-recent tr:hover td { background: #f8fafc; }

        .pos-badge { display:inline-flex; align-items:center; padding: 6px 12px; border-radius: 12px; font-size: 12px; font-weight: 800; text-transform: capitalize; }
        .pos-badge.ok { background: #ecfdf5; color: #10b981; }
        .pos-badge.warn { background: #fffbeb; color: #f59e0b; }
        .pos-badge.bad { background: #fef2f2; color: #ef4444; }

        .stat-card {
            background: white;
            border-radius: var(--pos-radius);
            padding: 24px;
            border: 1px solid var(--pos-border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
            border-color: var(--pos-primary);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .welcome-banner {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 30px;
            padding: 50px;
            color: white;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(79, 70, 229, 0.15);
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            bottom: -80px;
            right: 150px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .glass-btn {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 12px 24px;
            border-radius: 16px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .glass-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'dashboard'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="fade-in" style="padding: 24px; max-width: 1400px; margin: 0 auto;">
        <div class="welcome-banner">
            <div style="position: relative; z-index: 10;">
                <h1 style="font-size: 36px; font-weight: 900; margin: 0; letter-spacing: -0.5px;">Welcome back, <?php echo htmlspecialchars($tenantName); ?> 👋</h1>
                <p style="color: rgba(255,255,255,0.85); font-size: 18px; margin: 12px 0 30px; font-weight: 500;">Your store is active and performing well. Here's your business summary for today.</p>
                <div style="display:flex; gap:12px;">
                    <a class="glass-btn" href="/Mekong_CyberUnit/<?php echo htmlspecialchars($tenant['subdomain'] ?? 'pos'); ?>/pos/pos">
                        <i class="fas fa-plus"></i> Open Terminal
                    </a>
                    <a class="glass-btn" style="background: rgba(255,255,255,0.1);" href="/Mekong_CyberUnit/<?php echo htmlspecialchars($tenant['subdomain'] ?? 'pos'); ?>/pos/reports">
                        <i class="fas fa-chart-pie"></i> View Reports
                    </a>
                </div>
            </div>
        </div>


        <div class="pos-grid cols-4" style="margin-bottom: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
            <div class="stat-card">
                <div class="stat-icon" style="background: #eef2ff; color: #6366f1;"><i class="fas fa-shopping-bag"></i></div>
                <div style="font-size: 12px; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase; letter-spacing: 1px;">Total Orders</div>
                <div style="font-size: 32px; font-weight: 900; margin: 8px 0; color: var(--pos-text);"><?php echo (int)($stats['total_orders'] ?? 0); ?></div>
                <div style="font-size: 13px; font-weight: 600; color: #10b981;"><i class="fas fa-trending-up"></i> 12.5% increase</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #f0fdf4; color: #10b981;"><i class="fas fa-dollar-sign"></i></div>
                <div style="font-size: 12px; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase; letter-spacing: 1px;">Total Sales</div>
                <div style="font-size: 32px; font-weight: 900; margin: 8px 0; color: var(--pos-text);"><?php echo htmlspecialchars($fmtMoney($stats['total_sales'] ?? 0)); ?></div>
                <div style="font-size: 13px; font-weight: 600; color: #10b981;"><i class="fas fa-trending-up"></i> 8.2% increase</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fff7ed; color: #f59e0b;"><i class="fas fa-box-open"></i></div>
                <div style="font-size: 12px; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase; letter-spacing: 1px;">Total Items</div>
                <div style="font-size: 32px; font-weight: 900; margin: 8px 0; color: var(--pos-text);"><?php echo (int)($stats['total_products'] ?? 0); ?></div>
                <div style="font-size: 13px; font-weight: 600; color: var(--pos-text-muted);">In inventory</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef2f2; color: #ef4444;"><i class="fas fa-exclamation-triangle"></i></div>
                <div style="font-size: 12px; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase; letter-spacing: 1px;">Low Stock</div>
                <div style="font-size: 32px; font-weight: 900; margin: 8px 0; color: var(--pos-text);"><?php echo (int)($stats['low_stock_count'] ?? 0); ?></div>
                <div style="font-size: 13px; font-weight: 600; color: #ef4444;">Action required</div>
            </div>
        </div>


        <div class="pos-grid cols-2" style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; align-items: start;">
            <div style="background: white; border-radius: var(--pos-radius); border: 1px solid var(--pos-border); overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                <div style="padding: 24px; border-bottom: 1px solid var(--pos-border);">
                    <h3 class="pos-card-title">Sales Analytics</h3>
                    <p class="pos-card-sub">Revenue performance over time</p>
                </div>
                <div class="pos-chart-wrap">
                    <canvas id="salesChart"></canvas>
                </div>

                <div style="padding: 24px; border-top: 1px solid var(--pos-border);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 class="pos-card-title">Recent Transactions</h3>
                        <a href="/Mekong_CyberUnit/<?php echo htmlspecialchars($tenant['subdomain'] ?? 'pos'); ?>/pos/orders" style="font-size: 13px; font-weight: 700; color: var(--pos-primary); text-decoration: none;">View All History <i class="fas fa-chevron-right" style="font-size: 10px;"></i></a>
                    </div>
                    <div class="pos-recent" style="overflow: auto;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Ref ID</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($recentOrders)): ?>
                                <tr><td colspan="5" style="text-align: center; padding: 40px; color: var(--pos-text-muted);">No transactions found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($recentOrders as $o):
                                    $status = (string)($o['status'] ?? '');
                                    $statusClass = 'warn';
                                    if ($status === 'completed') $statusClass = 'ok';
                                    elseif ($status === 'cancelled') $statusClass = 'bad';
                                ?>
                                <tr>
                                    <td style="font-weight: 800;">#<?php echo (int)($o['id'] ?? 0); ?></td>
                                    <td><?php echo htmlspecialchars($o['customer_name'] ?? 'Walk-in'); ?></td>
                                    <td style="font-weight: 800; color: var(--pos-primary);"><?php echo htmlspecialchars($fmtMoney($o['total'] ?? 0)); ?></td>
                                    <td><span class="pos-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars(ucfirst($status ?: 'pending')); ?></span></td>
                                    <td style="font-size: 13px; color: var(--pos-text-muted);"><?php echo date('M d, H:i', strtotime($o['created_at'] ?? 'now')); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div style="display: grid; gap: 24px;">
                <div style="background: white; border-radius: var(--pos-radius); border: 1px solid var(--pos-border); padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <h3 class="pos-card-title" style="margin-bottom: 20px;">Notifications</h3>
                    <div style="display: grid; gap: 12px;">
                        <?php if (empty($lowStockItems)): ?>
                            <div style="background: #f0fdf4; border-radius: 16px; padding: 16px; display: flex; gap: 14px; border: 1px solid #dcfce7;">
                                <div style="width: 40px; height: 40px; border-radius: 10px; background: #10b981; color: white; display: grid; place-items: center; flex-shrink: 0;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <div style="font-weight: 800; color: #065f46; font-size: 14px;">Stock Perfect</div>
                                    <div style="font-size: 13px; color: #166534; opacity: 0.8;">All items are adequately stocked.</div>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach (array_slice($lowStockItems, 0, 3) as $p): ?>
                                <div style="background: #fef2f2; border-radius: 16px; padding: 16px; display: flex; gap: 14px; border: 1px solid #fee2e2;">
                                    <div style="width: 40px; height: 40px; border-radius: 10px; background: #ef4444; color: white; display: grid; place-items: center; flex-shrink: 0;">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; color: #991b1b; font-size: 14px;"><?php echo htmlspecialchars($p['name']); ?></div>
                                        <div style="font-size: 13px; color: #b91c1c; opacity: 0.8;">Only <?php echo (int)$p['stock_quantity']; ?> units remaining.</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="background: white; border-radius: var(--pos-radius); border: 1px solid var(--pos-border); padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <h3 class="pos-card-title" style="margin-bottom: 20px;">Top Products</h3>
                    <div style="display: grid; gap: 16px;">
                        <?php if (empty($topProducts)): ?>
                            <p style="color: var(--pos-text-muted); font-size: 14px; text-align: center; padding: 20px;">No sales data available yet.</p>
                        <?php else: ?>
                            <?php foreach ($topProducts as $idx => $tp): ?>
                                <div style="display: flex; align-items: center; gap: 16px; padding: 8px 0; border-bottom: 1px solid #f1f5f9;">
                                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #f8fafc; display: grid; place-items: center; font-weight: 900; font-size: 16px; color: var(--pos-primary); border: 1px solid var(--pos-border);">
                                        <?php echo $idx + 1; ?>
                                    </div>
                                    <div style="flex:1;">
                                        <div style="font-weight: 700; color: var(--pos-text); font-size: 14px; line-height: 1.2;"><?php echo htmlspecialchars($tp['name']); ?></div>
                                        <div style="font-size: 12px; color: var(--pos-text-muted); font-weight: 500;"><?php echo (int)$tp['qty']; ?> units sold</div>
                                    </div>
                                    <div style="font-weight: 900; color: var(--pos-primary); font-size: 15px;">
                                        $<?php echo number_format($tp['qty'] * 25, 2); ?>
                                    </div>
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