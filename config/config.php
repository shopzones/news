<?php
session_start();
ob_start();

define('BASE_URL', 'http://localhost/news-portal');
define('ADMIN_URL', BASE_URL . '/admin');
define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/news-portal/assets/uploads/');
define('UPLOAD_URL', BASE_URL . '/assets/uploads/');
define('SITE_NAME', 'News Portal');
define('TIMEZONE', 'UTC');
define('POSTS_PER_PAGE', 12);

date_default_timezone_set(TIMEZONE);

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';

$settings = getSettings();
if (!empty($settings['timezone'])) {
    date_default_timezone_set($settings['timezone']);
}

if (isset($settings['maintenance_mode']) && $settings['maintenance_mode'] == '1') {
    $exclude_pages = ['login.php', 'admin/login.php'];
    $current_page = basename($_SERVER['PHP_SELF']);
    if (!in_array($current_page, $exclude_pages) && !isset($_SESSION['user_id'])) {
        include_once __DIR__ . '/../maintenance.php';
        exit;
    }
}