<?php
require_once 'config/config.php';
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireLogin();
    
    if (!validateCsrf($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF');
    }
    
    $newsId = intval($_POST['news_id']);
    $comment = sanitize($_POST['comment']);
    $userId = $_SESSION['user_id'];
    
    if (addComment($newsId, $userId, $comment)) {
        logActivity($userId, 'comment', "Commented on news #$newsId");
    }
    
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
header('Location: index.php');