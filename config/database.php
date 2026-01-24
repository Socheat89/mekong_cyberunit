<?php
// config/database.php

// Determine environment
$isProduction = false;
if (isset($_SERVER['HTTP_HOST']) && (
    strpos($_SERVER['HTTP_HOST'], 'mekongcyberunit.app') !== false || 
    strpos($_SERVER['HTTP_HOST'], 'mekongcy') !== false
)) {
    $isProduction = true;
}

if ($isProduction) {
    // Production Credentials
    return [
        'host' => 'localhost',
        'database' => 'mekongcy_mekong_saas',
        'username' => 'mekongcy',
        'password' => 'Socheat!@#$2026',
        'charset' => 'utf8mb4'
    ];
} else {
    // Local Development Credentials
    return [
        'host' => 'localhost',
        'database' => 'mekong_saas',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ];
}
?>