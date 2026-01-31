<?php
// public/set_lang.php
require_once __DIR__ . '/../core/classes/Language.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    Language::setLanguage($lang);
}

// Redirect back to the previous page or home
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/Mekong_CyberUnit/';
header("Location: " . $referer);
exit;
