<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Order #<?php echo $order['id']; ?></title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    <style>
        .ord-detail-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; }
        .info-card { background: white; border-radius: 20px; padding: 30px; border: 1px solid var(--pos-border); }
        .info-label { font-size: 11px; font-weight: 800; color: var(--pos-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .info-value { font-size: 15px; font-weight: 700; color: var(--pos-text); }
        
        .item-table { width: 100%; border-collapse: collapse; }
        .item-th { padding: 15px 20px; font-size: 11px; text-transform: uppercase; color: var(--pos-muted); font-weight: 800; border-bottom: 2px solid #f1f5f9; }
        .item-td { padding: 20px; border-bottom: 1px solid #f1f5f9; font-weight: 600; }
        
        .total-section { padding-top: 30px; display: flex; flex-direction: column; align-items: flex-end; gap: 12px; }
        .total-row { display: flex; width: 300px; justify-content: space-between; align-items: center; }
        .total-row.grand { margin-top: 12px; padding-top: 12px; border-top: 2px solid var(--pos-brand-a); }
        
        .status-badge { padding: 8px 16px; border-radius: 999px; font-weight: 900; font-size: 12px; text-transform: uppercase; }
        .status-completed { background: #f0fdf4; color: #16a34a; }
        .status-pending { background: #fff7ed; color: #ea580c; }
        .status-cancelled { background: #fef2f2; color: #dc2626; }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'orders'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="fade-in">
        <div class="pos-row" style="margin-bottom: 32px;">
            <div class="pos-title">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                    <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders" class="pos-icon-btn" style="width: 36px; height: 36px;"><i class="fas fa-arrow-left"></i></a>
                    <span class="pos-pill" style="font-size: 12px; background: #eef2ff; color: #4338ca;">Order Details</span>
                </div>
                <h1 class="text-gradient">Order #<?php echo $order['id']; ?></h1>
            </div>
            <div style="display: flex; gap: 12px;">
                <span class="status-badge status-<?php echo $order['status']; ?>">
                    <i class="fas <?php echo $order['status'] === 'completed' ? 'fa-check-circle' : ($order['status'] === 'pending' ? 'fa-clock' : 'fa-times-circle'); ?>"></i>
                    <?php echo ucfirst($order['status']); ?>
                </span>
            </div>
        </div>

        <div class="pos-grid cols-3" style="margin-bottom: 32px; gap: 24px;">
            <div class="info-card">
                <div class="info-label">Customer Information</div>
                <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                    <div style="width: 50px; height: 50px; border-radius: 12px; background: var(--pos-gradient-indigo); display: grid; place-items: center; color: white; font-size: 20px; font-weight: 800;">
                        <?php echo strtoupper(substr($order['customer_name'] ?? 'W', 0, 1)); ?>
                    </div>
                    <div>
                        <div class="info-value" style="font-size: 18px;"><?php echo htmlspecialchars($order['customer_name'] ?? 'Walk-in Customer'); ?></div>
                        <div style="font-size: 13px; color: var(--pos-muted); font-weight: 600;"><?php echo htmlspecialchars($order['phone'] ?? 'No Phone Number'); ?></div>
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="info-label">Transaction Details</div>
                <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="font-size: 13px; color: var(--pos-muted); font-weight: 700;">Date</span>
                        <span style="font-weight: 700; color: var(--pos-text);"><?php echo date('M j, Y', strtotime($order['created_at'])); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="font-size: 13px; color: var(--pos-muted); font-weight: 700;">Time</span>
                        <span style="font-weight: 700; color: var(--pos-text);"><?php echo date('H:i A', strtotime($order['created_at'])); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="font-size: 13px; color: var(--pos-muted); font-weight: 700;">Payment</span>
                        <span class="pos-pill" style="font-size: 10px; background: #f8fafc; color: var(--pos-text); border: 1px solid var(--pos-border);"><?php echo strtoupper($order['payment_method'] ?? 'CASH'); ?></span>
                    </div>
                </div>
            </div>

            <div class="info-card" style="background: var(--pos-gradient-indigo); border: none; color: white;">
                <div class="info-label" style="color: rgba(255,255,255,0.7);">Order Total</div>
                <div style="margin-top: 10px;">
                    <div style="font-size: 32px; font-weight: 900;">$<?php echo number_format($order['total'], 2); ?></div>
                    <div style="font-size: 13px; font-weight: 500; opacity: 0.8; margin-top: 4px;">Thank you for your business.</div>
                </div>
            </div>
        </div>

        <div class="pos-card pos-shadow-sm" style="padding: 0; border: none; overflow: hidden; margin-bottom: 32px;">
            <div style="padding: 24px; border-bottom: 1px solid var(--pos-border);">
                <h3 style="font-weight: 900; color: var(--pos-text); font-size: 16px;">Item Summary</h3>
            </div>
            <table class="item-table">
                <thead>
                    <tr>
                        <th class="item-th">Product</th>
                        <th class="item-th" style="text-align: center;">Qty</th>
                        <th class="item-th" style="text-align: right;">Price</th>
                        <th class="item-th" style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td class="item-td">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #f8fafc; display: grid; place-items: center;">
                                        <i class="fas fa-box" style="color: #cbd5e1;"></i>
                                    </div>
                                    <div>
                                        <div style="color: var(--pos-text); font-weight: 800;"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                        <div style="font-size: 11px; color: var(--pos-muted);">ID: <?php echo $item['product_id']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="item-td" style="text-align: center; color: var(--pos-text);">x<?php echo $item['quantity']; ?></td>
                            <td class="item-td" style="text-align: right; color: var(--pos-text);">$<?php echo number_format($item['unit_price'], 2); ?></td>
                            <td class="item-td" style="text-align: right; color: var(--pos-brand-a); font-weight: 800;">$<?php echo number_format($item['total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="padding: 40px; background: #f8fafc; display: flex; justify-content: flex-end;">
                <div class="total-section">
                    <div class="total-row">
                        <span style="font-weight: 700; color: var(--pos-muted);">Subtotal</span>
                        <span style="font-weight: 800; color: var(--pos-text);">$<?php echo number_format($order['total'], 2); ?></span>
                    </div>
                    <div class="total-row">
                        <span style="font-weight: 700; color: var(--pos-muted);">Discount (0%)</span>
                        <span style="font-weight: 800; color: var(--pos-text);">$0.00</span>
                    </div>
                    <div class="total-row grand">
                        <span style="font-weight: 900; color: var(--pos-text); font-size: 18px;">Grand Total</span>
                        <span style="font-weight: 900; color: var(--pos-brand-a); font-size: 24px;">$<?php echo number_format($order['total'], 2); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: center; gap: 16px;">
            <?php if ($order['status'] === 'pending'): ?>
                <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders/<?php echo $order['id']; ?>/complete" class="pos-pill" style="padding: 16px 32px; background: #16a34a; box-shadow: 0 10px 20px rgba(22, 163, 74, 0.2);" data-pos-confirm="Complete this transaction?">
                    <i class="fas fa-check-circle"></i> Complete Sale
                </a>
            <?php endif; ?>
            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders/<?php echo $order['id']; ?>/receipt" class="pos-pill" style="padding: 16px 32px; background: white; color: var(--pos-text); border: 1px solid var(--pos-border);" target="_blank">
                <i class="fas fa-print"></i> Print Receipt
            </a>
            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders" class="pos-pill" style="padding: 16px 32px; background: transparent; color: var(--pos-muted); border: 1px solid var(--pos-border);">
                <i class="fas fa-list"></i> Back to History
            </a>
        </div>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>