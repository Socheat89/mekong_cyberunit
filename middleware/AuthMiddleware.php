<?php
// middleware/AuthMiddleware.php
class AuthMiddleware {
    public static function handle($requiredLevel = 1) {
        $isCleanDomain = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'mekongcyberunit.app');
        $urlPrefix = $isCleanDomain ? '' : '/Mekong_CyberUnit';

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