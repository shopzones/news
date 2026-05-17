<?php
session_start();
require_once '../config/config.php';
require_once '../includes/auth.php';

if (!isAdmin()) {
    header('Location: login.php');
    exit;
}

$totalNews = getNewsCount('published');
$totalDrafts = getNewsCount('draft');
$totalCategories = count(getCategories(1));
$totalUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalComments = $db->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'")->fetchColumn();
$recentActivity = getRecentActivity(8);
$analytics = getAnalytics(7);
$visitors = getTotalVisitors(30);
$topNews = getTrendingNews(5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= ADMIN_URL ?>/assets/css/admin.css">
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Dashboard</h2>
            <div>
                <span class="text-muted">Welcome, <?= $_SESSION['name'] ?? 'Admin' ?></span>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-2 col-md-4">
                <div class="card stats-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0"><?= $totalNews ?></h3>
                                <p class="text-muted mb-0">Published News</p>
                            </div>
                            <i class="fas fa-newspaper fa-2x text-danger opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4">
                <div class="card stats-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0"><?= $totalDrafts ?></h3>
                                <p class="text-muted mb-0">Drafts</p>
                            </div>
                            <i class="fas fa-edit fa-2x text-warning opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4">
                <div class="card stats-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0"><?= $totalCategories ?></h3>
                                <p class="text-muted mb-0">Categories</p>
                            </div>
                            <i class="fas fa-folder fa-2x text-primary opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4">
                <div class="card stats-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0"><?= $totalUsers ?></h3>
                                <p class="text-muted mb-0">Users</p>
                            </div>
                            <i class="fas fa-users fa-2x text-success opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4">
                <div class="card stats-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0"><?= $totalComments ?></h3>
                                <p class="text-muted mb-0">Pending Comments</p>
                            </div>
                            <i class="fas fa-comments fa-2x text-info opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-2 col-md-4">
                <div class="card stats-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0"><?= $visitors ?></h3>
                                <p class="text-muted mb-0">Monthly Visitors</p>
                            </div>
                            <i class="fas fa-chart-line fa-2x text-purple opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Activity -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">Recent Activity</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <tbody>
                            <?php foreach ($recentActivity as $act): ?>
                            <tr>
                                <td>
                                    <strong><?= sanitize($act['user_name'] ?? 'System') ?></strong><br>
                                    <small class="text-muted"><?= sanitize($act['action']) ?></small>
                                </td>
                                <td class="text-muted small"><?= timeAgo($act['created_at']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Top News -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">Most Viewed This Week</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach ($topNews as $n): ?>
                        <a href="../news/<?= $n['slug'] ?>" class="list-group-item list-group-item-action d-flex justify-content-between">
                            <span><?= truncateText($n['title'], 45) ?></span>
                            <span class="badge bg-danger"><?= $n['views'] ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>