<?php
session_start();
if (!isset($_SESSION['installed'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Installation Complete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white d-flex align-items-center" style="min-height:100vh">
<div class="container text-center">
    <h1 class="display-5 fw-bold text-success">Installation Complete!</h1>
    <p class="lead mt-3">Your News Portal has been successfully installed.</p>
    <a href="../index.php" class="btn btn-danger mt-4 px-5">Go to Website</a>
    <a href="../admin/login.php" class="btn btn-outline-light mt-4 px-5">Go to Admin Panel</a>
</div>
</body>
</html>