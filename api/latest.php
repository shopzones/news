<?php
header('Content-Type: application/json');
require_once '../config/config.php';

$limit = intval($_GET['limit'] ?? 10);
$news = getNews('published', $limit);

echo json_encode([
    'success' => true,
    'data' => $news
]);