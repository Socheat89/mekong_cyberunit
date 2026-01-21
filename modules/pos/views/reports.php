<?php
$salesSummary = $salesSummary ?? [];
$dailySales = $dailySales ?? [];
$monthlySales = $monthlySales ?? [];
$topProducts = $topProducts ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Reports</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .report-section { background: white; border-radius: 20px; padding: 30px; border: 1px solid var(--pos-border); }
        .top-prod-card { display: flex; align-items: center; justify-content: space-between; padding: 15px; border-radius: 12px; background: #f8fafc; margin-bottom: 12px; transition: transform 0.2s; }
        .top-prod-card:hover { transform: translateX(5px); background: #f1f5f9; }
        .rank-badge { width: 28px; height: 28px; border-radius: 8px; background: var(--pos-brand-a); color: white; display: grid; place-items: center; font-size: 12px; font-weight: 800; }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'reports'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="fade-in">
        <div class="pos-row" style="margin-bottom: 32px;">
            <div class="pos-title">
                <h1 class="text-gradient">Analytics & Reports</h1>
                <p>Monitor your performance, sales trends, and inventory health.</p>
            </div>
            <div style="display:flex; gap:12px;">
                <button class="pos-pill" style="padding: 12px 24px; background: white; color: var(--pos-text); border: 1px solid var(--pos-border);" onclick="window.print()">
                    <i class="fas fa-download"></i> Export PDF
                </button>
            </div>
        </div>

        <div class="pos-grid cols-4" style="margin-bottom: 32px; gap: 20px;">
            <div class="pos-stat pos-shadow-sm" style="border: none;">
                <div class="k">Total Revenue</div>
                <div class="v">$<?php echo number_format($salesSummary['total_sales'] ?? 0, 2); ?></div>
                <div class="chip" style="background: rgba(99, 102, 241, 0.1); color: #6366f1;"><i class="fas fa-dollar-sign"></i></div>
            </div>
            <div class="pos-stat pos-shadow-sm" style="border: none;">
                <div class="k">Total Orders</div>
                <div class="v"><?php echo number_format($salesSummary['total_orders'] ?? 0); ?></div>
                <div class="chip" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i class="fas fa-shopping-bag"></i></div>
            </div>
            <div class="pos-stat pos-shadow-sm" style="border: none;">
                <div class="k">Distinct Customers</div>
                <div class="v"><?php echo number_format($salesSummary['unique_customers'] ?? 0); ?></div>
                <div class="chip" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;"><i class="fas fa-users"></i></div>
            </div>
            <div class="pos-stat pos-shadow-sm" style="border: none;">
                <div class="k">Avg Order Value</div>
                <div class="v">$<?php echo number_format($salesSummary['avg_order_value'] ?? 0, 2); ?></div>
                <div class="chip" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;"><i class="fas fa-chart-line"></i></div>
            </div>
        </div>

        <div class="pos-grid cols-3" style="margin-bottom: 32px; gap: 24px;">
            <div class="report-section" style="grid-column: span 2;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <h2 style="font-size: 18px; font-weight: 800; color: var(--pos-text);">Sales Revenue Trend</h2>
                    <div class="pos-pill" style="font-size: 11px; background: #f8fafc; color: var(--pos-muted);">Last 7 Days</div>
                </div>
                <div style="height: 350px;">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            </div>

            <div class="report-section">
                <h2 style="font-size: 18px; font-weight: 800; color: var(--pos-text); margin-bottom: 24px;">Top Performing Products</h2>
                <?php if(empty($topProducts)): ?>
                    <div style="text-align: center; padding: 40px; color: var(--pos-muted);">
                        <i class="fas fa-box-open" style="font-size: 32px; opacity: 0.2; margin-bottom: 12px;"></i>
                        <p style="font-weight: 700;">No sales recorded yet.</p>
                    </div>
                <?php else: ?>
                    <?php $rank = 1; foreach($topProducts as $prod): ?>
                        <div class="top-prod-card">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div class="rank-badge"><?php echo $rank++; ?></div>
                                <div>
                                    <div style="font-weight: 800; font-size: 14px; color: var(--pos-text);"><?php echo htmlspecialchars($prod['name']); ?></div>
                                    <div style="font-size: 12px; color: var(--pos-muted); font-weight: 600;"><?php echo number_format($prod['total_quantity']); ?> sold</div>
                                </div>
                            </div>
                            <div style="font-weight: 900; color: var(--pos-brand-a);">$<?php echo number_format($prod['total_revenue'], 2); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="report-section" style="margin-bottom: 32px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 style="font-size: 18px; font-weight: 800; color: var(--pos-text);">Monthly Performance Overview</h2>
                <div class="pos-pill" style="font-size: 11px; background: #f8fafc; color: var(--pos-muted);">Past 6 Months</div>
            </div>
             <div style="height: 300px;">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script>
        // Config for common chart style
        Chart.defaults.font.family = "'Segoe UI', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
        Chart.defaults.color = '#64748b';
        
        // 1. Daily Sales
        const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
        const dailySalesData = <?php echo json_encode(array_reverse($dailySales)); ?>;
        
        new Chart(dailySalesCtx, {
            type: 'line',
            data: {
                labels: dailySalesData.map(item => {
                    const d = new Date(item.date);
                    return d.toLocaleDateString('en-US', { weekday: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Sales ($)',
                    data: dailySalesData.map(item => parseFloat(item.daily_total)),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6366f1',
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: { size: 13 },
                        bodyFont: { size: 14, weight: 'bold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f1f5f9' },
                        ticks: {
                            callback: function(value) { return '$' + value; }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // 2. Monthly Sales
        const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
        const monthlySalesData = <?php echo json_encode(array_reverse($monthlySales)); ?>;

        new Chart(monthlySalesCtx, {
            type: 'bar',
            data: {
                labels: monthlySalesData.map(item => {
                    const [y, m] = item.month.split('-');
                    const date = new Date(y, m - 1); // Month is 0-indexed
                    return date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                }),
                datasets: [{
                    label: 'Monthly Revenue',
                    data: monthlySalesData.map(item => parseFloat(item.monthly_total)),
                    backgroundColor: '#8b5cf6',
                    borderRadius: 8,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f1f5f9' },
                        ticks: {
                            callback: function(value) { return '$' + value; }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>
