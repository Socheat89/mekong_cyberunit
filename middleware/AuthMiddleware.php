<?php
// middleware/AuthMiddleware.php
class AuthMiddleware {
    public static function handle($requiredLevel = 1) {
        if (!Auth::check()) {
            header('Location: /Mekong_CyberUnit/public/login.php');
            exit;
        }

        if ($_SESSION['role_level'] < $requiredLevel) {
            header('Location: /Mekong_CyberUnit/public/unauthorized.php');
            exit;
        }
    }
}
?>