<?php
// tenant/users.php
session_start();
require_once __DIR__ . '/../core/classes/Database.php';
require_once __DIR__ . '/../core/classes/Tenant.php';
require_once __DIR__ . '/../core/classes/Auth.php';
require_once __DIR__ . '/../core/classes/User.php';
require_once __DIR__ . '/../core/classes/Settings.php';
require_once __DIR__ . '/../middleware/TenantMiddleware.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

TenantMiddleware::handle();
AuthMiddleware::handle();

// Check if user has permission to manage users
if (!Auth::isTenantAdmin()) {
    header('Location: /Mekong_CyberUnit/tenant/dashboard.php?error=' . urlencode('Access denied'));
    exit;
}

$db = Database::getInstance();
$tenantId = Tenant::getId();

$message = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_user'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $roleId = (int) $_POST['role_id'];

        try {
            User::create([
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'role_id' => $roleId
            ], $tenantId);
            $message = 'User created successfully!';
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Get users
$users = User::getAll($tenantId);

// Get roles for dropdown
$roles = $db->fetchAll("SELECT * FROM roles WHERE level <= 2 ORDER BY level DESC");

// Get current settings
$maxFreeUsers = Settings::get('max_free_users', $tenantId, 5);
$currentUserCount = User::countUsers($tenantId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - <?php echo htmlspecialchars(Tenant::getCurrent()['name']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: #007bff; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { background: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #218838; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .message { padding: 10px; margin-bottom: 20px; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .limit-info { background: #fff3cd; color: #856404; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
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
                <h1>User Management</h1>
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

        <div class="limit-info">
            <strong>Free Plan:</strong> <?php echo $currentUserCount; ?>/<?php echo $maxFreeUsers; ?> users
            <?php if ($currentUserCount >= $maxFreeUsers): ?>
                <span style="color: red;">(Limit reached - upgrade to add more users)</span>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3>Create New User</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Role:</label>
                    <select name="role_id" required>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo $role['id']; ?>"><?php echo htmlspecialchars($role['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="create_user" class="btn" <?php echo (!User::canCreateUser($tenantId) ? 'disabled' : ''); ?>>
                    Create User
                </button>
            </form>
        </div>

        <div class="card">
            <h3>Current Users</h3>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role_name']); ?></td>
                            <td><?php echo ucfirst($user['status']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>