<?php
header('Content-Type: application/json');
require_once '../config/config.php';

$q = sanitize($_GET['q'] ?? '');
$limit = intval($_GET['limit'] ?? 10);

if (!$q) {
    echo json_encode(['success' => false, 'message' => 'Query required']);
    exit;
}

$results = searchNews($q, $limit);

echo json_encode([
    'success' => true,
    'news' => $results
]);