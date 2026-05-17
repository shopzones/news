<?php
session_start();
require_once '../config/config.php';

if (isset($_SESSION['user_id']) && isAdmin()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = loginUser($email, $password);
    if ($user && in_array($user['role'], ['super_admin','admin','editor','moderator','reporter'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        logActivity($user['id'], 'login', 'Admin login successful');
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials or insufficient permissions';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - News Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #1F2937, #111827); height: 100vh; display: flex; align-items: center; }
        .admin-card { max-width: 420px; margin: auto; box-shadow: 0 20px 50px rgba(0,0,0,0.3); }
    </style>
</head>
<body>
<div class="container">
    <div class="card admin-card border-0 rounded-4">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-danger">News Portal</h3>
                <p class="text-muted">Admin Panel</p>
            </div>
            
            <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@example.com" required value="admin@example.com">
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required value="admin123">
                </div>
                <button type="submit" class="btn btn-danger w-100 py-2 fw-bold">Sign In</button>
                
                <div class="text-center mt-3">
                    <small class="text-muted">Default: admin@example.com / admin123</small>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>