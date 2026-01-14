<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Your Business - Mekong CyberUnit SaaS</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; }
        .register-form { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 100%; max-width: 500px; }
        .register-form h2 { text-align: center; margin-bottom: 20px; color: #007bff; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        .form-group input:focus, .form-group select:focus { border-color: #007bff; outline: none; }
        .systems { margin: 20px 0; }
        .systems label { display: block; margin-bottom: 10px; }
        .btn { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .btn:hover { background: #218838; }
        .login-link { text-align: center; margin-top: 20px; }
        .login-link a { color: #007bff; text-decoration: none; }
        .error { color: red; font-size: 14px; margin-top: 5px; }
        .success { color: green; font-size: 14px; margin-top: 5px; }
    </style>
</head>
<body>
    <form class="register-form" method="POST" action="register_process.php" id="registerForm">
        <h2>Register Your Business</h2>

        <?php if (isset($_GET['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>

        <div class="form-group">
            <label for="business_name">Business Name</label>
            <input type="text" id="business_name" name="business_name" required>
        </div>

        <div class="form-group">
            <label for="subdomain">Subdomain (yourname.mysaas.com)</label>
            <input type="text" id="subdomain" name="subdomain" required pattern="[a-zA-Z0-9]+" title="Only letters and numbers allowed">
            <small style="color: #666;">This will be your unique URL: subdomain.mysaas.com</small>
        </div>

        <div class="form-group">
            <label for="admin_email">Admin Email</label>
            <input type="email" id="admin_email" name="admin_email" required>
        </div>

        <div class="form-group">
            <label for="admin_username">Admin Username</label>
            <input type="text" id="admin_username" name="admin_username" required>
        </div>

        <div class="form-group">
            <label for="admin_password">Admin Password</label>
            <input type="password" id="admin_password" name="admin_password" required minlength="8">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <div class="systems">
            <label><strong>Select Systems to Subscribe:</strong></label>
            <label><input type="checkbox" name="systems[]" value="1" checked> POS System ($50/month)</label>
            <label><input type="checkbox" name="systems[]" value="2"> Inventory System ($30/month)</label>
            <label><input type="checkbox" name="systems[]" value="3"> HR System ($40/month)</label>
        </div>

        <button type="submit" class="btn">Create Business Account</button>

        <div class="login-link">
            <a href="/Mekong_CyberUnit/public/login.php">Already have an account? Login here</a>
        </div>
    </form>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('admin_password').value;
            const confirm = document.getElementById('confirm_password').value;

            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }

            // Basic subdomain validation
            const subdomain = document.getElementById('subdomain').value;
            if (!/^[a-zA-Z0-9]+$/.test(subdomain)) {
                e.preventDefault();
                alert('Subdomain can only contain letters and numbers!');
                return;
            }
        });
    </script>
</body>
</html>