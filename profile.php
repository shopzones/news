<?php
require_once 'config/config.php';
requireLogin();

$user = getUserById($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => sanitize($_POST['name']),
        'phone' => sanitize($_POST['phone']),
        'bio' => sanitize($_POST['bio'])
    ];
    updateUser($_SESSION['user_id'], $data);
    header('Location: profile.php?updated=1');
    exit;
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="fw-bold mb-4">My Profile</h4>
                    
                    <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success">Profile updated successfully.</div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?= sanitize($user['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" value="<?= sanitize($user['email']) ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?= sanitize($user['phone']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="3"><?= sanitize($user['bio'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>