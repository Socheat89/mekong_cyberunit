<?php
$heldOrders = $heldOrders ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Held Orders</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .holds-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }
        .holds-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 14px;
            border: 1px solid var(--pos-border);
            background: rgba(255,255,255,0.92);
            color: var(--pos-text);
            text-decoration: none;
            cursor: pointer;
            font-weight: 900;
            box-shadow: 0 10px 18px rgba(22,24,35,0.06);
        }
        .btn:hover { background: rgba(255,255,255,0.98); transform: translateY(-1px); }
        .btn.primary { background: linear-gradient(135deg, var(--pos-brand-a) 0%, var(--pos-brand-b) 65%, var(--pos-brand-c) 130%); color: #fff; border-color: transparent; }

        .hold-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }
        .hold-meta { min-width: 0; }
        .hold-meta b { font-weight: 950; }
        .hold-meta .sub { margin-top: 4px; color: var(--pos-muted); font-weight: 650; font-size: 12px; }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 900;
            font-size: 12px;
            border: 1px solid rgba(32,34,50,0.12);
            background: rgba(106, 92, 255, 0.08);
            color: rgba(106, 92, 255, 0.95);
        }

        .empty {
            padding: 16px;
            color: var(--pos-muted);
            font-weight: 700;
        }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'holds'; include __DIR__ . '/partials/navbar.php'; ?>

    <div class="pos-card pad">
        <div class="holds-head">
            <div class="pos-title">
                <h1><i class="fas fa-pause"></i> Held Orders</h1>
                <p>Pending sales saved from New Sale (Hold)</p>
            </div>
            <div class="holds-actions">
                <a class="btn primary" href="/Mekong_CyberUnit/<?php echo htmlspecialchars(Tenant::getCurrent()['subdomain']); ?>/pos/pos"><i class="fas fa-plus"></i> New Sale</a>
                <a class="btn" href="/Mekong_CyberUnit/<?php echo htmlspecialchars(Tenant::getCurrent()['subdomain']); ?>/pos/orders"><i class="fas fa-receipt"></i> Orders</a>
            </div>
        </div>
    </div>

    <div style="height: 14px;"></div>

    <div class="pos-card pad">
        <?php if (!count($heldOrders)): ?>
            <div class="empty">No held orders yet.</div>
        <?php else: ?>
            <ul class="pos-list">
                <?php foreach ($heldOrders as $o): ?>
                    <?php
                        $id = (int)($o['id'] ?? 0);
                        $total = (float)($o['total'] ?? 0);
                        $customerName = $o['customer_name'] ?? null;
                        $createdAt = $o['created_at'] ?? '';
                        $updatedAt = $o['updated_at'] ?? '';
                        $lineCount = (int)($o['item_lines'] ?? 0);
                        $qtyCount = (int)($o['total_qty'] ?? 0);
                    ?>
                    <li class="pos-list-item">
                        <span class="pos-dot" aria-hidden="true"></span>
                        <div class="hold-row" style="flex:1; min-width:0;">
                            <div class="hold-meta">
                                <div>
                                    <b>#<?php echo $id; ?></b>
                                    <span class="pill"><i class="fas fa-clock"></i> Pending</span>
                                </div>
                                <div class="sub">
                                    Customer: <?php echo htmlspecialchars($customerName ?: 'Walk-in'); ?>
                                    • Items: <?php echo $lineCount; ?> lines / <?php echo $qtyCount; ?> qty
                                    • Total: $<?php echo number_format($total, 2); ?>
                                </div>
                                <div class="sub">
                                    Created: <?php echo htmlspecialchars($createdAt); ?>
                                    <?php if ($updatedAt): ?> • Updated: <?php echo htmlspecialchars($updatedAt); ?><?php endif; ?>
                                </div>
                            </div>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                <a class="btn primary" href="/Mekong_CyberUnit/<?php echo htmlspecialchars(Tenant::getCurrent()['subdomain']); ?>/pos/pos?resume=<?php echo $id; ?>"><i class="fas fa-play"></i> Resume</a>
                                <a class="btn" href="/Mekong_CyberUnit/<?php echo htmlspecialchars(Tenant::getCurrent()['subdomain']); ?>/pos/orders/<?php echo $id; ?>"><i class="fas fa-eye"></i> View</a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
