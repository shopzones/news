<?php
session_start();
require_once '../config/config.php';
require_once '../includes/auth.php';
requireAdmin();

$analyticsData = getAnalytics(30);
$totalVisitors = getTotalVisitors(30);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Analytics - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-grow-1 p-4">
        <h4 class="mb-4">Website Analytics</h4>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center p-4">
                    <h2 class="fw-bold text-danger"><?= $totalVisitors ?></h2>
                    <p class="mb-0">Unique Visitors (30 days)</p>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <canvas id="visitsChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('visitsChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($analyticsData, 'visit_date')) ?>,
        datasets: [{
            label: 'Daily Visits',
            data: <?= json_encode(array_column($analyticsData, 'visits')) ?>,
            borderColor: '#E50914',
            tension: 0.3
        }]
    }
});
</script>
</body>
</html>