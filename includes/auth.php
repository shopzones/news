<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireAdmin() {
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['super_admin','admin','editor','moderator'])) {
        header('Location: ' . ADMIN_URL . '/login.php');
        exit;
    }
}

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}