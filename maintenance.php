<?php
$settings = getSettings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - <?= $settings['website_name'] ?? 'News Portal' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white d-flex align-items-center" style="min-height: 100vh;">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">Under Maintenance</h1>
        <p class="lead">We are currently performing scheduled maintenance.<br>Please check back soon.</p>
        <div class="mt-4">
            <i class="fas fa-tools fa-3x text-warning"></i>
        </div>
    </div>
</body>
</html>