<?php
// middleware/SuperAdminMiddleware.php
require_once __DIR__ . '/../core/classes/Auth.php';

class SuperAdminMiddleware {
    public static function handle() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $urlPrefix = '';
        if ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
            $urlPrefix = '/Mekong_CyberUnit';
        }


        if (!Auth::check() || !Auth::isSuperAdmin()) {
            // Store the attempted URL to redirect back after login
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header("Location: $urlPrefix/admin/login.php?error=" . urlencode('Master authentication required.'));
            exit;
        }
    }
}
?>
