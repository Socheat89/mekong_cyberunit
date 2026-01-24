<?php
$host = $_SERVER['HTTP_HOST'] ?? '';
$isProduction = (strpos($host, 'mekongcyberunit.app') !== false || strpos($host, 'mekongcy') !== false);
$urlPrefix = $isProduction ? '' : '/Mekong_CyberUnit';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - <?php echo htmlspecialchars($tenantName ?? 'POS'); ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --pos-primary: #6366f1;
            --pos-bg: #f8fafc;
            --pos-text: #1e293b;
            --pos-text-muted: #64748b;
            --pos-border: #e2e8f0;
            --pos-radius: 20px;
        }

        body.pos-app { background-color: var(--pos-bg); font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Outfit', sans-serif; }

        .prod-table { width: 100%; border-collapse: separate; border-spacing: 0 12px; }
        .prod-row { background: white; border-radius: 20px; transition: all 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        .prod-row:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
        .prod-td { padding: 16px 24px; border: none; }
        .prod-th { padding: 12px 24px; font-size: 11px; text-transform: uppercase; color: var(--pos-text-muted); font-weight: 800; letter-spacing: 1px; }
        
        .stock-badge { padding: 6px 14px; border-radius: 12px; font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 8px; }
        .stock-ok { background: #f0fdf4; color: #16a34a; }
        .stock-low { background: #fff7ed; color: #ea580c; }
        .stock-none { background: #fef2f2; color: #dc2626; }

        .search-container { position: relative; margin-bottom: 30px; }
        .search-container i { position: absolute; left: 24px; top: 18px; color: var(--pos-primary); font-size: 18px; }
        .search-container input { width: 100%; padding: 16px 16px 16px 60px; border-radius: 20px; border: 1px solid var(--pos-border); background: white; font-size: 16px; font-weight: 600; outline: none; transition: all 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
        .search-container input:focus { border-color: var(--pos-primary); box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.1); }

        .btn-add { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 14px 28px; border-radius: 18px; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 12px; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2); transition: all 0.3s; }
        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 15px 25px rgba(99, 102, 241, 0.3); }

        .stat-banner { background: white; border-radius: 24px; padding: 30px; border: 1px solid var(--pos-border); display: flex; gap: 40px; margin-bottom: 30px; }
        .stat-item { display: flex; align-items: center; gap: 16px; }
        .stat-icon { width: 48px; height: 48px; border-radius: 14px; background: #f1f5f9; display: grid; place-items: center; color: var(--pos-primary); font-size: 20px; }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'products'; include __DIR__ . '/partials/navbar.php'; ?>
    <div class="fade-in" style="padding: 30px; max-width: 1400px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 900; margin: 0; color: var(--pos-text);">Inventory Management</h1>
                <p style="color: var(--pos-text-muted); margin-top: 6px; font-weight: 500;">Control your products, tracking stock and pricing.</p>
            </div>
            <a href="<?php echo $urlPrefix; ?>/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/products/create" class="btn-add">
                <i class="fas fa-plus"></i> Add New Product
            </a>
        </div>

        <div class="stat-banner">
            <div class="stat-item">
                <div class="stat-icon" style="background: #eef2ff;"><i class="fas fa-boxes-stacked"></i></div>
                <div>
                    <div style="font-size: 12px; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase;">Total SKUs</div>
                    <div style="font-size: 24px; font-weight: 900; color: var(--pos-text);"><?php echo count($products); ?></div>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon" style="background: #fdf2f8; color: #db2777;"><i class="fas fa-tags"></i></div>
                <div>
                    <div style="font-size: 12px; font-weight: 800; color: var(--pos-text-muted); text-transform: uppercase;">Categories</div>
                    <div style="font-size: 24px; font-weight: 900; color: var(--pos-text);">12</div>
                </div>
            </div>
        </div>

        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search by name, SKU or categories..." onkeyup="searchProducts()">
        </div>

        <div style="overflow:auto;">
            <table class="prod-table" id="productsTable">
                <thead>
                    <tr>
                        <th class="prod-th">Product Details</th>
                        <th class="prod-th">Inventory Status</th>
                        <th class="prod-th">Unit Price</th>
                        <th class="prod-th" style="text-align: right;">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="4" style="padding: 80px; text-align: center;">
                                <div style="width: 100px; height: 100px; background: #f1f5f9; border-radius: 50%; display: grid; place-items: center; margin: 0 auto 20px;">
                                    <i class="fas fa-box-open" style="font-size: 40px; color: #cbd5e1;"></i>
                                </div>
                                <h3 style="color: var(--pos-text); font-weight: 800;">Inventory is empty</h3>
                                <p style="color: var(--pos-text-muted);">Start by adding your first product above.</p>
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
                                <td class="prod-td" style="border-radius: 20px 0 0 20px;">
                                    <div style="display: flex; align-items: center; gap: 16px;">
                                        <div style="width: 56px; height: 56px; border-radius: 16px; background: #f8fafc; display: grid; place-items: center; overflow: hidden; border: 1px solid var(--pos-border);">
                                            <?php if (!empty($product['image'])): ?>
                                                <img src="/Mekong_CyberUnit/uploads/products/<?php echo htmlspecialchars($product['image']); ?>" style="width:100%; height:100%; object-fit:cover;">
                                            <?php else: ?>
                                                <i class="fas fa-image" style="color: #cbd5e1; font-size: 24px;"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div style="font-weight: 800; font-size: 15px; color: var(--pos-text);"><?php echo htmlspecialchars($product['name']); ?></div>
                                            <div style="font-size: 12px; font-weight: 600; color: var(--pos-text-muted);">SKU: <?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="prod-td">
                                    <span class="stock-badge <?php echo $stockClass; ?>">
                                        <i class="fas <?php echo $stock <= 10 ? 'fa-circle-exclamation' : 'fa-circle-check'; ?>"></i>
                                        <?php echo $stock; ?> in stock
                                    </span>
                                </td>
                                <td class="prod-td">
                                    <div style="font-weight: 900; color: var(--pos-primary); font-size: 18px;">$<?php echo number_format($product['price'], 2); ?></div>
                                </td>
                                <td class="prod-td" style="text-align: right; border-radius: 0 20px 20px 0;">
                                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                        <a href="<?php echo $urlPrefix; ?>/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/products/<?php echo $product['id']; ?>/edit" class="pos-icon-btn" style="width: 44px; height: 44px; border-radius: 14px; border-color: #e2e8f0; color: #4f46e5; background: white;" title="Edit Product">
                                            <i class="fas fa-pencil" style="font-size: 14px;"></i>
                                        </a>
                                        <a href="<?php echo $urlPrefix; ?>/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/products/<?php echo $product['id']; ?>/delete" class="pos-icon-btn" style="width: 44px; height: 44px; border-radius: 14px; border-color: #fee2e2; color: #ef4444; background: white;" data-pos-confirm="Are you sure you want to delete this product?" title="Delete Product">
                                            <i class="fas fa-trash-can" style="font-size: 14px;"></i>
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