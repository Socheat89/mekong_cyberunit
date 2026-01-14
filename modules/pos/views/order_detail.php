<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - Order #<?php echo $order['id']; ?></title>
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
            max-width: 1000px;
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
        .order-info {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 40px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 20px;
        }
        .info-item {
            padding: 25px;
            background: #f9f9f9;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .info-item:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
        }
        .info-label {
            font-weight: 600;
            color: #666;
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-value {
            font-size: 20px;
            color: #333;
            font-weight: 700;
        }
        .status-completed {
            color: #28a745 !important;
        }
        .status-pending {
            color: #ffc107 !important;
        }
        .status-cancelled {
            color: #dc3545 !important;
        }
        .items-table {
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
            padding: 20px 25px;
            text-align: left;
            border-bottom: 1px solid #eee;
            color: #333;
        }
        th {
            background: #f8f8f8;
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
        }
        .total-row {
            background: #e9ecef;
            font-weight: 800;
            border-top: 2px solid #007bff;
        }
        .total-row td {
            color: #333;
            font-size: 18px;
        }
        .btn {
            background: #28a745;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            transition: all 0.2s ease;
            border: 1px solid #28a745;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .btn:hover {
            background: #218838;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        .btn-primary {
            background: #007bff;
            border: 1px solid #007bff;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-success {
            background: #28a745;
            border: 1px solid #28a745;
        }
        .btn-success:hover {
            background: #1e7e34;
        }
        .actions {
            margin-top: 40px;
            text-align: center;
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
            .container { padding: 15px; }
            .header { padding: 35px 25px; }
            .header h1 { font-size: 2.8em; }
            .order-info { padding: 30px; }
            .info-grid { grid-template-columns: 1fr; gap: 20px; }
            .info-item { padding: 20px; }
            .items-table { overflow-x: auto; }
            th, td { padding: 15px; font-size: 14px; }
            .actions { display: flex; flex-direction: column; align-items: center; }
            .btn { width: 100%; max-width: 300px; margin: 5px 0; }
        }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'orders'; include __DIR__ . '/partials/navbar.php'; ?>
    <div class="container">
        <div class="header">
            <h1>Order #<?php echo $order['id']; ?></h1>
        </div>

        <div class="order-info">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Customer</div>
                    <div class="info-value"><?php echo htmlspecialchars($order['customer_name'] ?? 'Walk-in Customer'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo htmlspecialchars($order['email'] ?? 'N/A'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone</div>
                    <div class="info-value"><?php echo htmlspecialchars($order['phone'] ?? 'N/A'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Order Date</div>
                    <div class="info-value"><?php echo date('M j, Y H:i', strtotime($order['created_at'])); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Amount</div>
                    <div class="info-value">$<?php echo number_format($order['total'], 2); ?></div>
                </div>
            </div>
        </div>

        <div class="items-table">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>$<?php echo number_format($item['unit_price'], 2); ?></td>
                            <td>$<?php echo number_format($item['total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">Grand Total:</td>
                        <td>$<?php echo number_format($order['total'], 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="actions">
            <?php if ($order['status'] === 'pending'): ?>
                <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders/<?php echo $order['id']; ?>/complete" class="btn btn-success" data-pos-confirm="Are you sure you want to complete this order?">Complete Order</a>
            <?php endif; ?>
            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders/<?php echo $order['id']; ?>/receipt" class="btn btn-success">Print Receipt</a>
            <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/orders" class="btn btn-primary">Back to Orders</a>
        </div>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>