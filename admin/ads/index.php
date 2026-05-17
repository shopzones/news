<?php
session_start();
require_once '../config/config.php';
require_once '../includes/auth.php';
requireAdmin();

$ads = $db->query("SELECT * FROM advertisements ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Advertisements - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between mb-4">
            <h4>Advertisement Management</h4>
            <a href="#" class="btn btn-danger">Add New Ad</a>
        </div>
        
        <div class="row">
            <?php foreach ($ads as $ad): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h6><?= sanitize($ad['title']) ?></h6>
                        <p class="text-muted small mb-1">Position: <strong><?= $ad['position'] ?></strong></p>
                        <p class="text-muted small">Clicks: <?= $ad['clicks'] ?> | Status: <?= $ad['status'] ? 'Active' : 'Inactive' ?></p>
                        <div class="btn-group btn-group-sm">
                            <a href="#" class="btn btn-outline-primary">Edit</a>
                            <a href="#" class="btn btn-outline-danger">Delete</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>