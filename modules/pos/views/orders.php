    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --pos-primary: #6366f1;
            --pos-secondary: #8b5cf6;
            --pos-bg: #f8fafc;
            --pos-text: #1e293b;
            --pos-text-muted: #64748b;
            --pos-border: #e2e8f0;
            --pos-radius: 20px;
        }

        body.pos-app { background-color: var(--pos-bg); font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Outfit', sans-serif; }

        .ord-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        .ord-row { background: white; border-radius: 20px; transition: all 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        .ord-row:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
        .ord-td { padding: 16px 24px; border: none; }
        .ord-th { padding: 12px 24px; font-size: 11px; text-transform: uppercase; color: var(--pos-text-muted); font-weight: 800; letter-spacing: 1px; }
        
        .status-pill { padding: 6px 14px; border-radius: 12px; font-weight: 700; font-size: 12px; text-transform: capitalize; display: inline-flex; align-items: center; gap: 8px; }
        .status-completed { background: #ecfdf5; color: #10b981; }
        .status-pending { background: #fffbeb; color: #f59e0b; }
        .status-cancelled { background: #fef2f2; color: #ef4444; }

        .search-container { position: relative; margin-bottom: 30px; }
        .search-container i { position: absolute; left: 24px; top: 18px; color: var(--pos-primary); font-size: 18px; }
        .search-container input { width: 100%; padding: 16px 16px 16px 60px; border-radius: 20px; border: 1px solid var(--pos-border); background: white; font-size: 16px; font-weight: 600; outline: none; transition: all 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        .search-container input:focus { border-color: var(--pos-primary); box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.1); }

        .btn-pos { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 14px 28px; border-radius: 18px; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 12px; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2); transition: all 0.3s; }
        .btn-pos:hover { transform: translateY(-2px); box-shadow: 0 15px 25px rgba(99, 102, 241, 0.3); }

        .stat-banner { background: white; border-radius: 24px; padding: 30px; border: 1px solid var(--pos-border); display: flex; gap: 40px; margin-bottom: 30px; }
        .stat-item { display: flex; align-items: center; gap: 16px; }
        .stat-icon { width: 48px; height: 48px; border-radius: 14px; background: #f1f5f9; display: grid; place-items: center; color: var(--pos-primary); font-size: 20px; }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'orders'; include __DIR__ . '/partials/navbar.php'; ?>
    <div class="fade-in" style="padding: 30px; max-width: 1400px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 900; margin: 0; color: var(--pos-text);">Transaction History</h1>
                <p style="color: var(--pos-text-muted); margin-top: 6px; font-weight: 500;">Review all orders, payments, and sales records.</p>
            </div>
            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/pos" class="btn-pos">
                <i class="fas fa-desktop"></i> Open Point of Sale
            </a>
        </div>

        <div class="stat-banner">
            <div class="stat-item">
                <div class="stat-icon" style="background: #eef2ff;"><i class="fas fa-file-invoice-dollar"></i></div>
                <div>
                    <div style="font-size: 12px; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase;">Total Transactions</div>
                    <div style="font-size: 24px; font-weight: 900; color: var(--pos-text);"><?php echo count($orders); ?></div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon" style="background: #ecfdf5; color: #10b981;"><i class="fas fa-circle-check"></i></div>
                <div>
                    <div style="font-size: 12px; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase;">Completed</div>
                    <div style="font-size: 24px; font-weight: 900; color: var(--pos-text);"><?php echo count(array_filter($orders, fn($o) => $o['status'] === 'completed')); ?></div>
                </div>
            </div>
        </div>

        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search by Order ID, Customer Name or Status..." onkeyup="searchOrders()">
        </div>

        <div style="overflow:auto;">
            <table class="ord-table" id="ordersTable">
                <thead>
                    <tr>
                        <th class="ord-th">Order ID</th>
                        <th class="ord-th">Customer</th>
                        <th class="ord-th">Date & Time</th>
                        <th class="ord-th">Payable Total</th>
                        <th class="ord-th">Status</th>
                        <th class="ord-th" style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="6" style="padding: 80px; text-align: center;">
                                <div style="width: 100px; height: 100px; background: #f1f5f9; border-radius: 50%; display: grid; place-items: center; margin: 0 auto 20px;">
                                    <i class="fas fa-receipt" style="font-size: 40px; color: #cbd5e1;"></i>
                                </div>
                                <h3 style="color: var(--pos-text); font-weight: 800;">No transactions yet</h3>
                                <p style="color: var(--pos-text-muted);">Process your first sale to see it here.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr class="ord-row">
                                <td class="ord-td" style="border-radius: 20px 0 0 20px;">
                                    <div style="font-weight: 900; font-size: 16px; color: var(--pos-primary);">#<?php echo $order['id']; ?></div>
                                </td>
                                <td class="ord-td">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 36px; height: 36px; border-radius: 10px; background: #f8fafc; display: grid; place-items: center; font-size: 14px; font-weight: 900; color: var(--pos-primary); border: 1px solid var(--pos-border);">
                                            <?php echo strtoupper(substr($order['customer_name'] ?? 'W', 0, 1)); ?>
                                        </div>
                                        <div style="font-weight: 700; color: var(--pos-text);"><?php echo htmlspecialchars($order['customer_name'] ?? 'Walk-in Customer'); ?></div>
                                    </div>
                                </td>
                                <td class="ord-td">
                                    <div style="font-size: 14px; font-weight: 600; color: var(--pos-text);"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></div>
                                    <div style="font-size: 12px; color: var(--pos-text-muted); font-weight: 600;"><?php echo date('h:i A', strtotime($order['created_at'])); ?></div>
                                </td>
                                <td class="ord-td">
                                    <div style="font-weight: 900; color: var(--pos-text); font-size: 18px;">$<?php echo number_format($order['total'], 2); ?></div>
                                </td>
                                <td class="ord-td">
                                    <span class="status-pill status-<?php echo $order['status']; ?>">
                                        <i class="fas <?php echo $order['status'] === 'completed' ? 'fa-check-circle' : ($order['status'] === 'pending' ? 'fa-clock' : 'fa-times-circle'); ?>"></i>
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td class="ord-td" style="text-align: right; border-radius: 0 20px 20px 0;">
                                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                        <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders/<?php echo $order['id']; ?>" class="pos-icon-btn" style="width: 44px; height: 44px; border-radius: 14px; border-color: #e2e8f0; color: #4f46e5; background: white;" title="Order Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders/<?php echo $order['id']; ?>/receipt" class="pos-icon-btn" target="_blank" style="width: 44px; height: 44px; border-radius: 14px; border-color: #e2e8f0; color: var(--pos-text); background: white;" title="Print Invoice">
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
