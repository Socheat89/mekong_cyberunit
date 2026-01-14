<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Products</title>
    <link href="/Mekong_CyberUnit/public/css/pos_template.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
            min-height: 100vh;
            color: #333;
        }
        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #fff;
            color: #333;
            padding: 50px 40px;
            border-radius: 8px;
            margin-bottom: 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 3.5em;
            color: #333;
            font-weight: 800;
        }
        .header p {
            margin: 15px 0 0;
            color: #666;
        }
        .actions {
            margin-bottom: 40px;
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .btn {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            color: #333;
            padding: 18px 35px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .btn:hover {
            background: #f8f8f8;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        .btn-primary {
            background: #007bff;
            color: white;
            border: 1px solid #007bff;
        }
        .btn-primary:hover {
            background: #0056b3;
            box-shadow: 0 2px 6px rgba(0,123,255,0.3);
        }
        .btn-danger {
            background: #dc3545;
            color: white;
            border: 1px solid #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
            box-shadow: 0 2px 6px rgba(220,53,69,0.3);
        }
        .search-box {
            margin-bottom: 40px;
            text-align: center;
        }
        .search-box input {
            padding: 20px 30px;
            width: 100%;
            max-width: 600px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            background: #fff;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        .search-box input::placeholder {
            color: #999;
        }
        .search-box input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.2);
        }
        .table-container {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 25px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f8f8f8;
            font-weight: 700;
            color: #333;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .status-active {
            color: #28a745;
            font-weight: bold;
            background: #d4edda;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85em;
        }
        .status-inactive {
            color: #721c24;
            font-weight: bold;
            background: #f8d7da;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85em;
        }
        .action-btns {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .action-btns .btn {
            padding: 10px 18px;
            font-size: 14px;
            text-transform: none;
            min-width: auto;
            border-radius: 6px;
        }
        .empty-state {
            text-align: center;
            color: #666;
            padding: 100px 20px;
        }
        .empty-state p {
            margin: 0;
            font-size: 1.5em;
            margin-bottom: 20px;
        }
        /* Navigation Bar */
        .navbar {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 0;
        }
        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            max-width: none;
            margin: 0;
        }
        .nav-brand {
            font-size: 1.8em;
            font-weight: 800;
            color: #333;
        }
        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 25px;
        }
        .nav-links li a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .nav-links li a:hover {
            background: #f8f8f8;
            color: #007bff;
        }
        @media (max-width: 768px) {
            .container { padding: 20px; }
            .header { padding: 35px 25px; }
            .header h1 { font-size: 2.8em; }
            .actions { flex-direction: column; align-items: center; }
            .search-box input { max-width: 100%; }
            .action-btns { flex-direction: column; }
            .action-btns .btn { width: 100%; }
            th, td { padding: 15px 10px; font-size: 14px; }
            .nav-links { gap: 15px; }
            .nav-links li a { padding: 10px 15px; font-size: 14px; }
        }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'products'; include __DIR__ . '/partials/navbar.php'; ?>
    <div class="container">
        <div class="header">
            <h1>Product Management</h1>
            <p>Manage your POS products and inventory</p>
        </div>

        <div class="actions">
            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/products/create" class="btn btn-primary">➕ Add New Product</a>
            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/dashboard" class="btn">🏠 Back to POS</a>
        </div>

        <div class="search-box">
            <input type="text" id="searchInput" placeholder="🔍 Search products by name..." onkeyup="searchProducts()">
        </div>

        <div class="table-container">
            <table id="productsTable">
                <thead>
                    <tr>
                        <th>�️ Image</th>
                        <th>�📦 Product Name</th>
                        <th>🏷️ Category</th>
                        <th>💰 Price</th>
                        <th>📊 Stock</th>
                        <th>🔢 SKU</th>
                        <th>📍 Status</th>
                        <th>⚡ Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <p>No products found. <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/products/create">Create your first product</a></p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><img src="<?php echo $product['image'] ? '/Mekong_CyberUnit/uploads/products/' . htmlspecialchars($product['image']) : '/Mekong_CyberUnit/public/images/no-image.svg'; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo htmlspecialchars($product['category_name'] ?? 'No Category'); ?></td>
                                <td>$<?php echo number_format($product['price'], 2); ?></td>
                                <td><?php echo $product['stock_quantity']; ?></td>
                                <td><?php echo htmlspecialchars($product['sku']); ?></td>
                                <td><span class="status-<?php echo $product['status']; ?>"><?php echo ucfirst($product['status']); ?></span></td>
                                <td>
                                    <div class="action-btns">
                                        <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/products/<?php echo $product['id']; ?>/edit" class="btn">✏️ Edit</a>
                                        <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/products/<?php echo $product['id']; ?>/delete" class="btn btn-danger" data-pos-confirm="Are you sure you want to delete this product?">🗑️ Delete</a>
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
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td')[0];
                if (td) {
                    const txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
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