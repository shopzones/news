<?php
session_start();
require_once '../config/config.php';
require_once '../includes/auth.php';
requireAdmin();

$users = $db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between mb-4">
            <h4>Manage Users</h4>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addUserModal">Add New User</button>
        </div>
        
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= sanitize($u['name']) ?></td>
                        <td><?= sanitize($u['email']) ?></td>
                        <td><span class="badge bg-primary"><?= getRoleName($u['role']) ?></span></td>
                        <td><?= $u['status'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Blocked</span>' ?></td>
                        <td><?= formatDate($u['created_at']) ?></td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-warning">Edit</a>
                            <a href="#" class="btn btn-sm btn-outline-danger">Block</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="add-user.php">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="Full Name" required></div>
                    <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                    <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                    <div class="mb-3">
                        <select name="role" class="form-select">
                            <option value="subscriber">Subscriber</option>
                            <option value="reporter">Reporter</option>
                            <option value="editor">Editor</option>
                            <option value="moderator">Moderator</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>