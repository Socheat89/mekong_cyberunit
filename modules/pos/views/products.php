<?php
$host = $_SERVER['HTTP_HOST'] ?? '';
$isProduction = (strpos($host, 'mekongcyberunit.app') !== false || strpos($host, 'mekongcy') !== false);
$urlPrefix = '/Mekong_CyberUnit';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - <?php echo htmlspecialchars($tenantName ?? 'POS'); ?></title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        .search-container { position: relative; margin-bottom: 24px; }
        .search-container i { position: absolute; left: 20px; top: 16px; color: var(--pos-primary); font-size: 18px; }
        .search-container input { width: 100%; padding: 14px 20px 14px 54px; border-radius: 16px; border: 1.5px solid var(--pos-border); background: white; font-size: 15px; font-weight: 600; outline: none; transition: all 0.3s; }
        .search-container input:focus { border-color: var(--pos-primary); box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }
        
        .product-img { width: 44px; height: 44px; border-radius: 12px; background: #f1f5f9; display: grid; place-items: center; overflow: hidden; border: 1px solid var(--pos-border); }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'products'; include __DIR__ . '/partials/navbar.php'; ?>
    
    <div class="fade-in">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px;">
            <div class="pos-title">
                <h1>Inventory Management</h1>
                <p>Track your stock levels, prices and product catalog.</p>
            </div>
            <a href="<?php echo htmlspecialchars($posUrl('products/create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Product
            </a>
        </div>

        <div class="pos-grid cols-4" style="margin-bottom: 32px;">
            <div class="pos-stat">
                <span class="k">Total SKUs</span>
                <p class="v"><?php echo count($products); ?></p>
                <div class="chip" style="background: rgba(99, 102, 241, 0.1); color: var(--pos-primary);"><i class="fas fa-box"></i></div>
            </div>
            <div class="pos-stat">
                <span class="k">Active Categories</span>
                <p class="v">12</p>
                <div class="chip" style="background: rgba(139, 92, 246, 0.1); color: var(--pos-secondary);"><i class="fas fa-tags"></i></div>
            </div>
        </div>

        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search by name, SKU or barcode..." onkeyup="searchProducts()">
        </div>

        <div class="pos-table-container">
            <table class="pos-table" id="productsTable">
                <thead>
                    <tr>
                        <th style="width: 60px;">Pic</th>
                        <th>Product Details</th>
                        <th>Status</th>
                        <th>Unit Price</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="5" style="padding: 100px; text-align: center;">
                                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: grid; place-items: center; margin: 0 auto 20px;">
                                    <i class="fas fa-box-open" style="font-size: 32px; color: #cbd5e1;"></i>
                                </div>
                                <h3 style="color: var(--pos-text); font-weight: 800; margin: 0;">No products found</h3>
                                <p style="color: var(--pos-text-muted); margin-top: 8px;">Start by adding your first product items.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $p): 
                            $stock = (int)$p['stock_quantity'];
                            $badge = 'badge-success';
                            if ($stock <= 0) $badge = 'badge-danger';
                            elseif ($stock <= 10) $badge = 'badge-warning';
                        ?>
                            <tr class="product-row">
                                <td>
                                    <div class="product-img">
                                        <?php if (!empty($p['image'])): ?>
                                            <img src="/Mekong_CyberUnit/uploads/products/<?php echo htmlspecialchars($p['image']); ?>" style="width:100%; height:100%; object-fit:cover;">
                                        <?php else: ?>
                                            <i class="fas fa-image" style="color: #cbd5e1; font-size: 18px;"></i>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 800; font-size: 15px; color: var(--pos-text);"><?php echo htmlspecialchars($p['name']); ?></div>
                                    <div style="font-size: 12px; font-weight: 600; color: var(--pos-text-muted); margin-top: 2px;">SKU: <?php echo htmlspecialchars($p['sku'] ?: 'N/A'); ?></div>
                                </td>
                                <td>
                                    <span class="badge <?php echo $badge; ?>">
                                        <?php echo $stock; ?> in stock
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight: 900; color: var(--pos-primary); font-size: 16px;">$<?php echo number_format($p['price'], 2); ?></div>
                                </td>
                                <td>
                                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                        <a href="<?php echo htmlspecialchars($posUrl('products/' . $p['id'] . '/edit')); ?>" class="pos-icon-btn" title="Edit">
                                            <i class="fas fa-pencil-alt" style="font-size: 14px;"></i>
                                        </a>
                                        <a href="<?php echo htmlspecialchars($posUrl('products/' . $p['id'] . '/delete')); ?>" class="pos-icon-btn" style="color: var(--pos-danger);" data-pos-confirm="Are you sure you want to delete this product?" title="Delete">
                                            <i class="fas fa-trash-alt" style="font-size: 14px;"></i>
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
            const filter = document.getElementById('searchInput').value.toUpperCase();
            const rows = document.querySelectorAll('.product-row');
            rows.forEach(row => {
                const text = row.innerText.toUpperCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        }
    </script>
    
    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>