<?php
session_start();
require_once '../config/config.php';
require_once '../includes/auth.php';
requireAdmin();

$comments = $db->query("SELECT c.*, n.title as news_title, u.name as user_name FROM comments c 
                        LEFT JOIN news n ON c.news_id = n.id 
                        LEFT JOIN users u ON c.user_id = u.id 
                        ORDER BY c.created_at DESC LIMIT 50")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Comments - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-grow-1 p-4">
        <h4 class="mb-4">Comment Moderation</h4>
        
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Comment</th>
                            <th>News</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($comments as $c): ?>
                    <tr>
                        <td><?= truncateText(sanitize($c['comment']), 80) ?></td>
                        <td><small><?= truncateText(sanitize($c['news_title']), 40) ?></small></td>
                        <td><?= sanitize($c['user_name'] ?? 'Guest') ?></td>
                        <td>
                            <?php if ($c['status'] == 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php elseif ($c['status'] == 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php else: ?>
                                <span class="badge bg-danger"><?= $c['status'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= formatDate($c['created_at']) ?></td>
                        <td>
                            <a href="?action=approve&id=<?= $c['id'] ?>" class="btn btn-sm btn-success">Approve</a>
                            <a href="?action=reject&id=<?= $c['id'] ?>" class="btn btn-sm btn-warning">Reject</a>
                            <a href="?action=delete&id=<?= $c['id'] ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>