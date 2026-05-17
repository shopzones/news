<?php
session_start();
require_once '../config/config.php';
require_once '../includes/auth.php';
requireAdmin();

$id = intval($_GET['id'] ?? 0);
$news = getNewsById($id);
if (!$news) die('Article not found');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => sanitize($_POST['title']),
        'short_description' => sanitize($_POST['short_description']),
        'full_content' => $_POST['full_content'],
        'category_id' => intval($_POST['category_id']),
        'status' => $_POST['status'] ?? 'published',
        'featured' => isset($_POST['featured']) ? 1 : 0,
        'is_breaking' => isset($_POST['is_breaking']) ? 1 : 0,
        'tags' => sanitize($_POST['tags']),
        'seo_title' => sanitize($_POST['seo_title']),
        'seo_description' => sanitize($_POST['seo_description'])
    ];
    
    if (!empty($_FILES['featured_image']['name'])) {
        $upload = uploadFile($_FILES['featured_image'], 'news');
        if ($upload) $data['featured_image'] = $upload;
    }
    
    $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
    $stmt = $db->prepare("UPDATE news SET $set, updated_at = NOW() WHERE id = ?");
    $stmt->execute([...array_values($data), $id]);
    
    logActivity($_SESSION['user_id'], 'news_update', "Updated news #$id");
    header('Location: index.php?updated=1');
    exit;
}

$categories = getCategories(1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit News - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex-grow-1 p-4">
        <h4 class="mb-4">Edit Article</h4>
        
        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-8">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" value="<?= sanitize($news['title']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="2"><?= sanitize($news['short_description']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Content</label>
                        <textarea name="full_content" id="editor" class="form-control" rows="15"><?= $news['full_content'] ?></textarea>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $news['category_id'] == $c['id'] ? 'selected' : '' ?>><?= sanitize($c['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Featured Image</label>
                        <?php if ($news['featured_image']): ?>
                            <img src="<?= BASE_URL ?>/assets/uploads/<?= $news['featured_image'] ?>" class="img-fluid mb-2 rounded" style="max-height: 120px;">
                        <?php endif; ?>
                        <input type="file" name="featured_image" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <input type="text" name="tags" class="form-control" value="<?= sanitize($news['tags']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SEO Title</label>
                        <input type="text" name="seo_title" class="form-control" value="<?= sanitize($news['seo_title']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SEO Description</label>
                        <textarea name="seo_description" class="form-control" rows="2"><?= sanitize($news['seo_description']) ?></textarea>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="featured" value="1" <?= $news['featured'] ? 'checked' : '' ?>>
                        <label class="form-check-label">Featured</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_breaking" value="1" <?= $news['is_breaking'] ? 'checked' : '' ?>>
                        <label class="form-check-label">Breaking News</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="published" <?= $news['status'] == 'published' ? 'selected' : '' ?>>Published</option>
                            <option value="draft" <?= $news['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="pending" <?= $news['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-danger mt-3 px-5">Update Article</button>
            <a href="index.php" class="btn btn-secondary mt-3">Cancel</a>
        </form>
    </div>
</div>

<script>
ClassicEditor.create(document.querySelector('#editor')).catch(error => console.error(error));
</script>
</body>
</html>