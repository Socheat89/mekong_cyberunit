<?php
$heldOrders = $heldOrders ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Held Orders</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    <style>
        .hold-card { background: white; border-radius: 12px; transition: all 0.2s; box-shadow: var(--pos-shadow-sm); border: 1px solid var(--pos-border); padding: 20px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .hold-card:hover { transform: translateY(-2px); box-shadow: var(--pos-shadow-md); border-color: var(--pos-brand-a); }
        .hold-info { display: flex; align-items: center; gap: 15px; }
        .hold-icon { width: 44px; height: 44px; border-radius: 10px; background: #fff7ed; color: #ea580c; display: grid; place-items: center; font-size: 18px; }
        .hold-details { display: flex; flex-direction: column; gap: 2px; }
        .hold-id { font-weight: 900; font-size: 15px; color: var(--pos-brand-a); }
        .hold-cust { font-weight: 700; color: var(--pos-text); font-size: 14px; }
        .hold-meta { font-size: 12px; color: var(--pos-muted); font-weight: 600; }
        .hold-total { font-weight: 900; color: var(--pos-text); font-size: 18px; text-align: right; }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'holds'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="fade-in">
        <div class="pos-row" style="margin-bottom: 32px;">
            <div class="pos-title">
                <h1 class="text-gradient">Held Orders</h1>
                <p>Manage sales that were put on hold for later completion.</p>
            </div>
            <div style="display:flex; gap:12px;">
                <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos" class="pos-pill" style="padding: 12px 24px;">
                    <i class="fas fa-plus"></i> New Sale
                </a>
            </div>
        </div>

        <?php if (!count($heldOrders)): ?>
            <div class="pos-card" style="padding: 80px; text-align: center; border: none;">
                <i class="fas fa-pause-circle" style="font-size: 48px; opacity: 0.1; margin-bottom: 16px; display: block;"></i>
                <h2 style="font-weight: 800; color: var(--pos-text);">No Held Orders</h2>
                <p style="color: var(--pos-muted); font-weight: 600; margin-top: 8px;">Order that you put on hold will appear here.</p>
            </div>
        <?php else: ?>
            <div style="max-width: 900px; margin: 0 auto;">
                <?php foreach ($heldOrders as $o): ?>
                    <?php
                        $id = (int)($o['id'] ?? 0);
                        $total = (float)($o['total'] ?? 0);
                        $customerName = $o['customer_name'] ?? 'Walk-in Customer';
                        $createdAt = $o['created_at'] ?? '';
                        $lineCount = (int)($o['item_lines'] ?? 0);
                    ?>
                    <div class="hold-card">
                        <div class="hold-info">
                            <div class="hold-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="hold-details">
                                <div class="hold-id">Order #<?php echo $id; ?></div>
                                <div class="hold-cust"><?php echo htmlspecialchars($customerName); ?></div>
                                <div class="hold-meta">
                                    <i class="fas fa-calendar-alt"></i> <?php echo date('M j, Y H:i', strtotime($createdAt)); ?>
                                    <span style="margin: 0 8px;">•</span>
                                    <i class="fas fa-layer-group"></i> <?php echo $lineCount; ?> Items
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 40px;">
                            <div class="hold-total">
                                <div style="font-size: 11px; text-transform: uppercase; color: var(--pos-muted); letter-spacing: 0.5px; margin-bottom: 2px;">Payable</div>
                                $<?php echo number_format($total, 2); ?>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <a href="/Mekong_CyberUnit/<?php echo htmlspecialchars(Tenant::getCurrent()['subdomain']); ?>/pos/pos?resume=<?php echo $id; ?>" class="pos-pill" style="background: var(--pos-gradient-indigo); padding: 10px 20px; font-size: 12px; box-shadow: 0 8px 15px rgba(99, 102, 241, 0.2);">
                                    <i class="fas fa-play"></i> Resume
                                </a>
                                <a href="/Mekong_CyberUnit/<?php echo htmlspecialchars(Tenant::getCurrent()['subdomain']); ?>/pos/orders/<?php echo $id; ?>" class="pos-icon-btn" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
