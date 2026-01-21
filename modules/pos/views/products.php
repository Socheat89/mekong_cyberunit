<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Products</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .prod-table { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
        .prod-row { background: white; border-radius: 12px; transition: all 0.2s; box-shadow: var(--pos-shadow-sm); }
        .prod-row:hover { transform: translateY(-2px); box-shadow: var(--pos-shadow-md); }
        .prod-td { padding: 20px; border: none; }
        .prod-th { padding: 12px 20px; font-size: 11px; text-transform: uppercase; color: var(--pos-muted); font-weight: 800; letter-spacing: 0.5px; }
        .stock-badge { padding: 6px 12px; border-radius: 999px; font-weight: 800; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; }
        .stock-ok { background: #f0fdf4; color: #16a34a; }
        .stock-low { background: #fff7ed; color: #ea580c; }
        .stock-none { background: #fef2f2; color: #dc2626; }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'products'; include __DIR__ . '/partials/navbar.php'; ?>
    <div class="fade-in">
        <div class="pos-row" style="margin-bottom: 24px;">
            <div class="pos-title">
                <h1 class="text-gradient">Products</h1>
                <p>Manage your inventory, pricing, and stock levels.</p>
            </div>
            <div style="display:flex; gap:12px;">
                <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/products/create" class="pos-pill" style="padding: 12px 24px;">
                    <i class="fas fa-plus"></i> Add Product
                </a>
            </div>
        </div>

        <div class="pos-grid cols-4" style="margin-bottom: 24px;">
            <div class="pos-stat pos-shadow-sm" style="border: none;">
                <div class="k">Total Items</div>
                <div class="v"><?php echo count($products); ?></div>
                <div class="chip" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9;"><i class="fas fa-box"></i></div>
            </div>
        </div>

        <div class="pos-card pos-shadow-sm" style="padding: 30px; border: none;">
            <div class="pos-topbar__search" style="max-width: 100%; margin-bottom: 24px; background: #f8fafc; border: 1px solid var(--pos-border);">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search by name, SKU or category..." onkeyup="searchProducts()" style="font-weight: 700;">
            </div>

            <div style="overflow:auto;">
                <table class="prod-table" id="productsTable">
                    <thead>
                        <tr>
                            <th class="prod-th">Product Information</th>
                            <th class="prod-th">Stock Level</th>
                            <th class="prod-th">Price</th>
                            <th class="prod-th" style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="4" style="padding: 60px; text-align: center; color: var(--pos-muted);">
                                    <i class="fas fa-box-open" style="font-size: 40px; opacity: 0.2; margin-bottom: 16px; display: block;"></i>
                                    <p style="font-weight: 700;">No products in inventory.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): 
                                $stock = (int)$product['stock_quantity'];
                                $stockClass = 'stock-ok';
                                if ($stock <= 0) $stockClass = 'stock-none';
                                elseif ($stock <= 10) $stockClass = 'stock-low';
                            ?>
                                <tr class="prod-row">
                                    <td class="prod-td" style="border-radius: 12px 0 0 12px;">
                                        <div style="display: flex; align-items: center; gap: 14px;">
                                            <div style="width: 48px; height: 48px; border-radius: 12px; background: #f1f5f9; display: grid; place-items: center; overflow: hidden;">
                                                <?php if (!empty($product['image'])): ?>
                                                    <img src="/Mekong_CyberUnit/uploads/products/<?php echo htmlspecialchars($product['image']); ?>" style="width:100%; height:100%; object-fit:cover;">
                                                <?php else: ?>
                                                    <i class="fas fa-image" style="color: #cbd5e1;"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div style="font-weight: 800; font-size: 15px; color: var(--pos-text);"><?php echo htmlspecialchars($product['name']); ?></div>
                                                <div style="font-size: 11px; font-weight: 700; color: var(--pos-muted); text-transform: uppercase; margin-top: 2px;">SKU: <?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="prod-td">
                                        <span class="stock-badge <?php echo $stockClass; ?>">
                                            <i class="fas <?php echo $stock <= 10 ? 'fa-triangle-exclamation' : 'fa-check-circle'; ?>"></i>
                                            <?php echo $stock; ?> in stock
                                        </span>
                                    </td>
                                    <td class="prod-td">
                                        <div style="font-weight: 900; color: var(--pos-text); font-size: 16px;">$<?php echo number_format($product['price'], 2); ?></div>
                                    </td>
                                    <td class="prod-td" style="text-align: right; border-radius: 0 12px 12px 0;">
                                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/products/<?php echo $product['id']; ?>/edit" class="pos-icon-btn" style="width: 36px; height: 36px; color: var(--pos-brand-a); border-color: rgba(99, 102, 241, 0.1);">
                                                <i class="fas fa-pen" style="font-size: 14px;"></i>
                                            </a>
                                            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/products/<?php echo $product['id']; ?>/delete" class="pos-icon-btn" style="width: 36px; height: 36px; color: #ef4444; border-color: rgba(239, 68, 68, 0.1);" data-pos-confirm="Are you sure you want to delete this product?">
                                                <i class="fas fa-trash" style="font-size: 14px;"></i>
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
    </div>

    <script>
        function searchProducts() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('productsTable');
            const trs = table.getElementsByClassName('prod-row');

            for (let i = 0; i < trs.length; i++) {
                const nameText = trs[i].innerText.toUpperCase();
                trs[i].style.display = nameText.includes(filter) ? '' : 'none';
            }
        }
    </script>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>