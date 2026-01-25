<?php
// middleware/AuthMiddleware.php
class AuthMiddleware {
    public static function handle($requiredLevel = 1) {
        $urlPrefix = '/Mekong_CyberUnit';

        if (!Auth::check()) {
            header("Location: $urlPrefix/public/login.php");
            exit;
        }

        if ($_SESSION['role_level'] < $requiredLevel) {
            header("Location: $urlPrefix/public/unauthorized.php");
            exit;
        }
    }
}
?>