<?php
header('Content-Type: application/json');
require_once '../config/config.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = sanitize($data['email'] ?? '');

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email']);
    exit;
}

if (subscribeEmail($email)) {
    echo json_encode(['success' => true, 'message' => 'Subscribed successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Already subscribed']);
}