<?php
// tenant/settings.php
session_start();
require_once __DIR__ . '/../core/classes/Database.php';
require_once __DIR__ . '/../core/classes/Tenant.php';
require_once __DIR__ . '/../core/classes/Auth.php';
require_once __DIR__ . '/../core/classes/Settings.php';
require_once __DIR__ . '/../middleware/TenantMiddleware.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

TenantMiddleware::handle();
AuthMiddleware::handle();

// Check if user has permission to manage settings
if (!Auth::isTenantAdmin()) {
    header('Location: /Mekong_CyberUnit/tenant/dashboard.php?error=' . urlencode('Access denied'));
    exit;
}

$tenantId = Tenant::getId();
$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_receipt_settings'])) {
        Settings::set('receipt_show_logo', isset($_POST['receipt_show_logo']) ? '1' : '0', $tenantId);
        Settings::set('receipt_header_text', trim($_POST['receipt_header_text']), $tenantId);
        Settings::set('receipt_footer_text', trim($_POST['receipt_footer_text']), $tenantId);
        Settings::set('receipt_font_size', (int) $_POST['receipt_font_size'], $tenantId);
        Settings::set('receipt_paper_width', (int) $_POST['receipt_paper_width'], $tenantId);
        $message = 'Receipt settings updated successfully!';
    } elseif (isset($_POST['update_company_info'])) {
        Settings::set('company_address', trim($_POST['company_address']), $tenantId);
        Settings::set('company_phone', trim($_POST['company_phone']), $tenantId);
        Settings::set('company_email', trim($_POST['company_email']), $tenantId);
        Settings::set('company_tax_id', trim($_POST['company_tax_id']), $tenantId);
        Settings::set('company_website', trim($_POST['company_website']), $tenantId);
        $message = 'Company information updated successfully!';
    }
}

// Get current settings
$settings = Settings::getAll($tenantId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - <?php echo htmlspecialchars(Tenant::getCurrent()['name']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 900px; margin: 0 auto; }
        .header { background: #007bff; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }

        /* Tab Styles */
        .tabs { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }
        .tab-buttons { display: flex; background: #f8f9fa; border-bottom: 1px solid #dee2e6; }
        .tab-button { flex: 1; padding: 15px 20px; border: none; background: none; cursor: pointer; font-size: 16px; font-weight: 500; color: #6c757d; border-bottom: 3px solid transparent; transition: all 0.3s ease; }
        .tab-button:hover { background: #e9ecef; color: #495057; }
        .tab-button.active { color: #007bff; border-bottom-color: #007bff; background: white; }

        .tab-content { padding: 30px; display: none; }
        .tab-content.active { display: block; }

        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        input, select, textarea { width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; transition: border-color 0.3s ease; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #007bff; box-shadow: 0 0 0 3px rgba(0,123,255,0.1); }
        textarea { resize: vertical; min-height: 80px; }

        .checkbox-group { display: flex; align-items: center; margin-bottom: 20px; }
        .checkbox-group input { width: auto; margin-right: 12px; transform: scale(1.2); }
        .checkbox-group label { margin-bottom: 0; font-weight: 500; }

        .btn { background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 500; transition: background 0.3s ease; }
        .btn:hover { background: #218838; }

        .message { padding: 15px; margin-bottom: 25px; border-radius: 6px; font-weight: 500; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .preview { border: 2px solid #e9ecef; padding: 20px; background: #f9f9f9; font-family: 'Courier New', monospace; font-size: 12px; max-width: 400px; margin: 20px auto; border-radius: 6px; }

        /* Navigation Bar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .nav-links li a:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .tab-buttons { flex-direction: column; }
            .tab-button { text-align: left; }
            .container { padding: 10px; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand"><?php echo htmlspecialchars(Tenant::getCurrent()['name']); ?> Admin</div>
            <ul class="nav-links">
                <li><a href="/Mekong_CyberUnit/tenant/dashboard.php">Dashboard</a></li>
                <li><a href="/Mekong_CyberUnit/tenant/users.php">Users</a></li>
                <li><a href="/Mekong_CyberUnit/tenant/settings.php">Settings</a></li>
                <li><a href="/Mekong_CyberUnit/<?php echo Tenant::getCurrent()['subdomain']; ?>/logout">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="header">
            <div>
                <h1>System Settings</h1>
                <p><?php echo htmlspecialchars(Tenant::getCurrent()['name']); ?></p>
            </div>
            <a href="/Mekong_CyberUnit/tenant/dashboard.php" style="color: white; text-decoration: none;">← Back to Dashboard</a>
        </div>

        <?php if ($message): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="tabs">
            <div class="tab-buttons">
                <button class="tab-button active" onclick="openTab('company')">🏢 Company Information</button>
                <button class="tab-button" onclick="openTab('receipt')">🧾 Receipt Design</button>
            </div>

            <div id="company" class="tab-content active">
                <h3>Company Information</h3>
                <form method="POST">
                    <div class="form-group">
                        <label>Company Address:</label>
                        <textarea name="company_address" placeholder="Enter company address"><?php echo htmlspecialchars($settings['company_address'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Phone Number:</label>
                        <input type="text" name="company_phone" value="<?php echo htmlspecialchars($settings['company_phone'] ?? ''); ?>" placeholder="Enter phone number">
                    </div>

                    <div class="form-group">
                        <label>Email Address:</label>
                        <input type="email" name="company_email" value="<?php echo htmlspecialchars($settings['company_email'] ?? ''); ?>" placeholder="Enter email address">
                    </div>

                    <div class="form-group">
                        <label>Tax ID / VAT Number:</label>
                        <input type="text" name="company_tax_id" value="<?php echo htmlspecialchars($settings['company_tax_id'] ?? ''); ?>" placeholder="Enter tax ID or VAT number">
                    </div>

                    <div class="form-group">
                        <label>Website:</label>
                        <input type="url" name="company_website" value="<?php echo htmlspecialchars($settings['company_website'] ?? ''); ?>" placeholder="Enter website URL">
                    </div>

                    <button type="submit" name="update_company_info" class="btn">💾 Save Company Information</button>
                </form>
            </div>

            <div id="receipt" class="tab-content">
                <h3>Receipt Design Settings</h3>
                <form method="POST">
                    <div class="checkbox-group">
                        <input type="checkbox" name="receipt_show_logo" id="receipt_show_logo" value="1" <?php echo ($settings['receipt_show_logo'] ?? '1') === '1' ? 'checked' : ''; ?>>
                        <label for="receipt_show_logo">Show company logo on receipt</label>
                    </div>

                    <div class="form-group">
                        <label>Receipt Header Text:</label>
                        <input type="text" name="receipt_header_text" value="<?php echo htmlspecialchars($settings['receipt_header_text'] ?? 'Point of Sale Receipt'); ?>" placeholder="Point of Sale Receipt">
                    </div>

                    <div class="form-group">
                        <label>Receipt Footer Text:</label>
                        <textarea name="receipt_footer_text" placeholder="Thank you for your business!"><?php echo htmlspecialchars($settings['receipt_footer_text'] ?? 'Thank you for your business!'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Font Size (px):</label>
                        <input type="number" name="receipt_font_size" value="<?php echo htmlspecialchars($settings['receipt_font_size'] ?? '12'); ?>" min="8" max="16">
                    </div>

                    <div class="form-group">
                        <label>Paper Width (px):</label>
                        <input type="number" name="receipt_paper_width" value="<?php echo htmlspecialchars($settings['receipt_paper_width'] ?? '400'); ?>" min="300" max="600">
                    </div>

                    <button type="submit" name="update_receipt_settings" class="btn">💾 Save Receipt Settings</button>
                </form>

                <h4 style="margin-top: 30px; color: #007bff;">📋 Receipt Preview</h4>
                <div class="preview" style="max-width: <?php echo ($settings['receipt_paper_width'] ?? '400'); ?>px; font-size: <?php echo ($settings['receipt_font_size'] ?? '12'); ?>px;">
                    <div style="text-align: center; margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 5px;">
                        <?php if (($settings['receipt_show_logo'] ?? '1') === '1'): ?>
                            <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">[LOGO]</div>
                        <?php endif; ?>
                        <div style="font-weight: bold;"><?php echo htmlspecialchars(Tenant::getCurrent()['name']); ?></div>
                        <div><?php echo htmlspecialchars($settings['receipt_header_text'] ?? 'Point of Sale Receipt'); ?></div>
                        <div>Order #12345</div>
                    </div>

                    <?php if (!empty($settings['company_address']) || !empty($settings['company_phone']) || !empty($settings['company_email']) || !empty($settings['company_tax_id']) || !empty($settings['company_website'])): ?>
                    <div style="text-align: center; margin-bottom: 10px; font-size: 10px;">
                        <?php if (!empty($settings['company_address'])): ?>
                            <div><?php echo htmlspecialchars($settings['company_address']); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($settings['company_phone'])): ?>
                            <div>Phone: <?php echo htmlspecialchars($settings['company_phone']); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($settings['company_email'])): ?>
                            <div>Email: <?php echo htmlspecialchars($settings['company_email']); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($settings['company_tax_id'])): ?>
                            <div>Tax ID: <?php echo htmlspecialchars($settings['company_tax_id']); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($settings['company_website'])): ?>
                            <div><?php echo htmlspecialchars($settings['company_website']); ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div style="margin-bottom: 10px;">
                        <div><strong>Date:</strong> <?php echo date('M j, Y H:i'); ?></div>
                        <div><strong>Customer:</strong> John Doe</div>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px; padding-bottom: 2px; border-bottom: 1px dotted #ccc;">
                            <span style="flex: 2;">Product 1</span>
                            <span style="flex: 1; text-align: center;">2</span>
                            <span style="flex: 1; text-align: right;">$10.00</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 5px; padding-bottom: 2px; border-bottom: 1px dotted #ccc;">
                            <span style="flex: 2;">Product 2</span>
                            <span style="flex: 1; text-align: center;">1</span>
                            <span style="flex: 1; text-align: right;">$15.00</span>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #000; padding-top: 5px; font-weight: bold;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>Total:</span>
                            <span>$25.00</span>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 10px; border-top: 1px dashed #000; padding-top: 5px;">
                        <div><?php echo htmlspecialchars($settings['receipt_footer_text'] ?? 'Thank you for your business!'); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openTab(tabName) {
                // Hide all tab contents
                const tabContents = document.querySelectorAll('.tab-content');
                tabContents.forEach(content => content.classList.remove('active'));

                // Remove active class from all tab buttons
                const tabButtons = document.querySelectorAll('.tab-button');
                tabButtons.forEach(button => button.classList.remove('active'));

                // Show the selected tab content
                document.getElementById(tabName).classList.add('active');

                // Add active class to clicked button
                event.target.classList.add('active');
            }
        </script>
    </div>
</body>
</html>