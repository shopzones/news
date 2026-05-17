<?php
session_start();
require_once '../config/config.php';
require_once '../includes/auth.php';
requireAdmin();

$settings = getSettings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
        $stmt->execute([sanitize($value), $key]);
    }
    header('Location: index.php?saved=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-grow-1 p-4">
        <h4>Website Settings</h4>
        
        <?php if (isset($_GET['saved'])): ?>
        <div class="alert alert-success">Settings saved successfully.</div>
        <?php endif; ?>
        
        <form method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Website Name</label>
                        <input type="text" name="website_name" class="form-control" value="<?= $settings['website_name'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email" class="form-control" value="<?= $settings['contact_email'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Facebook URL</label>
                        <input type="url" name="facebook_url" class="form-control" value="<?= $settings['facebook_url'] ?? '' ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">SEO Title</label>
                        <input type="text" name="seo_title" class="form-control" value="<?= $settings['seo_title'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SEO Description</label>
                        <textarea name="seo_description" class="form-control" rows="3"><?= $settings['seo_description'] ?? '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Google Analytics ID</label>
                        <input type="text" name="google_analytics_id" class="form-control" value="<?= $settings['google_analytics_id'] ?? '' ?>">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-danger mt-3">Save Settings</button>
        </form>
    </div>
</div>
</body>
</html>