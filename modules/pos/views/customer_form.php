<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - <?php echo isset($customer) ? 'Edit' : 'Add'; ?> Customer</title>
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
            overflow-x: hidden;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #fff;
            color: #333;
            padding: 30px 40px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 600;
        }
        .navbar {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }
        .btn {
            background: #fff;
            color: #333;
            border: 1px solid #ddd;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s, color 0.3s;
            font-size: 14px;
        }
        .btn:hover {
            background: #f0f0f0;
        }
        .btn-primary {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .btn-secondary {
            background: #6c757d;
            color: #fff;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background: #fff;
        }
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .required::after {
            content: ' *';
            color: #dc3545;
        }
        .btn-container {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            justify-content: center;
        }
        .form-section {
            margin-bottom: 30px;
        }
        .form-section:last-child {
            margin-bottom: 0;
        }
        .section-title {
            font-size: 1.4em;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title::before {
            content: '';
            width: 4px;
            height: 20px;
            background: #007bff;
            border-radius: 2px;
        }
        .navbar {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
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
            font-size: 1.5em;
            font-weight: 600;
            color: #333;
        }
        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 20px;
        }
        .nav-links li a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .nav-links li a:hover {
            background: #f8f9fa;
        }
        @media (max-width: 768px) {
            .container { padding: 15px; }
            .header { padding: 25px 20px; }
            .header h1 { font-size: 2em; }
            .form-container { padding: 20px; }
            .form-row { grid-template-columns: 1fr; gap: 15px; }
            .btn-container { flex-direction: column; align-items: center; }
            .btn { width: 100%; max-width: 300px; }
        }
    </style>
</head>
<body class="pos-app">
    <?php $activeNav = 'customers'; include __DIR__ . '/partials/navbar.php'; ?>
    <div class="container">
        <div class="header">
            <h1><?php echo isset($customer) ? 'Edit Customer' : 'Add New Customer'; ?></h1>
        </div>

        <div class="form-container">
            <form method="POST">
                <div class="form-section">
                    <h2 class="section-title">👤 Basic Information</h2>
                    <div class="form-group">
                        <label for="name" class="required">Customer Name</label>
                        <div class="input-group name">
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($customer['name'] ?? ''); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="section-title">📞 Contact Details</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-group email">
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customer['email'] ?? ''); ?>" placeholder="customer@example.com">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <div class="input-group phone">
                                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($customer['phone'] ?? ''); ?>" placeholder="+1 (555) 123-4567">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="section-title">🏠 Address Information</h2>
                    <div class="form-group">
                        <label for="address">Full Address</label>
                        <textarea id="address" name="address" placeholder="Enter customer's full address..."><?php echo htmlspecialchars($customer['address'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn"><?php echo isset($customer) ? '✏️ Update Customer' : '➕ Add Customer'; ?></button>
                    <a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/pos/customers" class="btn btn-secondary">❌ Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>