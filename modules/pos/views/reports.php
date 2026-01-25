<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - <?php echo htmlspecialchars($tenantName ?? 'POS'); ?></title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .report-card { background: white; border-radius: 24px; padding: 32px; border: 1.5px solid var(--pos-border); }
        .ranking-item { display: flex; align-items: center; gap: 16px; padding: 14px; border-radius: 18px; transition: all 0.2s; border: 1px solid transparent; }
        .ranking-item:hover { background: #f8fafc; border-color: var(--pos-border); transform: translateX(8px); }
        .ranking-badge { width: 32px; height: 32px; border-radius: 10px; background: var(--pos-primary-light); color: var(--pos-primary); display: grid; place-items: center; font-size: 14px; font-weight: 900; flex-shrink: 0; }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'reports'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="fade-in">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px;">
            <div class="pos-title">
                <h1>Business Analytics</h1>
                <p>Monitor your performance, sales trends, and inventory health in real-time.</p>
            </div>
            <button class="btn btn-outline" onclick="window.print()">
                <i class="fas fa-file-pdf"></i> Export Overview
            </button>
        </div>

        <!-- Quick Summary -->
        <div class="pos-grid cols-4" style="margin-bottom: 32px;">
            <div class="pos-stat">
                <span class="k">Total Revenue</span>
                <p class="v">$<?php echo number_format($salesSummary['total_sales'] ?? 0, 2); ?></p>
                <div class="chip" style="background: rgba(99, 102, 241, 0.1); color: var(--pos-primary);"><i class="fas fa-dollar-sign"></i></div>
            </div>
            <div class="pos-stat">
                <span class="k">Orders Volume</span>
                <p class="v"><?php echo number_format($salesSummary['total_orders'] ?? 0); ?></p>
                <div class="chip" style="background: rgba(16, 185, 129, 0.1); color: var(--pos-success);"><i class="fas fa-shopping-bag"></i></div>
            </div>
            <div class="pos-stat">
                <span class="k">Avg Ticket Size</span>
                <p class="v">$<?php echo number_format($salesSummary['avg_order_value'] ?? 0, 2); ?></p>
                <div class="chip" style="background: rgba(139, 92, 246, 0.1); color: var(--pos-secondary);"><i class="fas fa-chart-line"></i></div>
            </div>
            <div class="pos-stat">
                <span class="k">Active Customers</span>
                <p class="v"><?php echo number_format($salesSummary['unique_customers'] ?? 0); ?></p>
                <div class="chip" style="background: rgba(245, 158, 11, 0.1); color: var(--pos-warning);"><i class="fas fa-users"></i></div>
            </div>
        </div>

        <div class="pos-grid cols-3" style="margin-bottom: 32px; align-items: stretch;">
            <!-- Sales Chart -->
            <div class="report-card" style="grid-column: span 2;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                    <h3 class="pos-card-title">Daily Sales Performance</h3>
                    <div class="badge badge-primary">Past 7 Days</div>
                </div>
                <div style="height: 380px;">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>

            <!-- Top Products -->
            <div class="report-card">
                <h3 class="pos-card-title" style="margin-bottom: 24px;">Growth Leaderboard</h3>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <?php if(empty($topProducts)): ?>
                        <div style="text-align: center; padding: 48px; color: var(--pos-text-muted);">
                            <i class="fas fa-layer-group" style="font-size: 32px; opacity: 0.2; margin-bottom: 12px; display: block;"></i>
                            <p style="font-weight: 700;">No sales data found</p>
                        </div>
                    <?php else: ?>
                        <?php $rank = 1; foreach($topProducts as $p): ?>
                            <div class="ranking-item">
                                <div class="ranking-badge"><?php echo $rank++; ?></div>
                                <div style="flex: 1;">
                                    <p style="font-weight: 800; color: var(--pos-text); font-size: 14px; margin: 0;"><?php echo htmlspecialchars($p['name']); ?></p>
                                    <p style="font-size: 12px; font-weight: 600; color: var(--pos-text-muted); margin-top: 2px; text-transform: uppercase;"><?php echo number_format($p['total_quantity']); ?> sold</p>
                                </div>
                                <div style="font-weight: 900; color: var(--pos-primary); font-size: 15px;">$<?php echo number_format($p['total_revenue'], 2); ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Monthly Overview -->
        <div class="report-card" style="margin-bottom: 32px;">
             <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
                <h3 class="pos-card-title">Monthly Revenue Trends</h3>
                <div class="badge" style="background: #f1f5f9; color: #64748b;">Past 6 Months</div>
            </div>
            <div style="height: 320px;">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Common chart styling
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#94a3b8';

        // 1. Daily Evolution
        const dailyCtx = document.getElementById('dailySalesChart').getContext('2d');
        const dailyData = <?php echo json_encode(array_reverse($dailySales)); ?>;
        
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyData.map(d => {
                    const date = new Date(d.date);
                    return date.toLocaleDateString('en-US', { weekday: 'short', day: 'numeric' });
                }),
                datasets: [{
                    data: dailyData.map(d => parseFloat(d.daily_total)),
                    borderColor: '#6366f1',
                    borderWidth: 5,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#6366f1',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 4,
                    backgroundColor: (context) => {
                        const ctx = context.chart.ctx;
                        const g = ctx.createLinearGradient(0, 0, 0, 400);
                        g.addColorStop(0, 'rgba(99, 102, 241, 0.15)');
                        g.addColorStop(1, 'rgba(99, 102, 241, 0)');
                        return g;
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { padding: 12, cornerRadius: 12, bodyFont: { size: 14, weight: 'bold' } } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#f1f5f9' }, ticks: { callback: v => '$' + v } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Monthly Stats
        const monthCtx = document.getElementById('monthlySalesChart').getContext('2d');
        const monthData = <?php echo json_encode(array_reverse($monthlySales)); ?>;
        
        new Chart(monthCtx, {
            type: 'bar',
            data: {
                labels: monthData.map(d => {
                    const [y, m] = d.month.split('-');
                    return new Date(y, m - 1).toLocaleDateString('en-US', { month: 'long', year: '2-digit' });
                }),
                datasets: [{
                    data: monthData.map(d => parseFloat(d.monthly_total)),
                    backgroundColor: '#8b5cf6',
                    borderRadius: 12,
                    maxBarThickness: 50
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#f1f5f9' }, ticks: { callback: v => '$' + v } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
