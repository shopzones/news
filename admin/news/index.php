<?php
session_start();
require_once '../config/config.php';
require_once '../includes/auth.php';
requireAdmin();

$action = $_GET['action'] ?? 'list';
$newsList = getNews('published', 20);
$categories = getCategories(1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage News - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<div class="d-flex">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between mb-4">
            <h4>Manage News</h4>
            <a href="add.php" class="btn btn-danger"><i class="fas fa-plus me-2"></i>Add New Article</a>
        </div>
        
        <?php if ($action === 'list'): ?>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Views</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($newsList as $n): ?>
                    <tr>
                        <td><?= truncateText($n['title'], 60) ?></td>
                        <td><span class="badge bg-secondary"><?= $n['category_name'] ?></span></td>
                        <td><?= $n['author_name'] ?></td>
                        <td><?= number_format($n['views']) ?></td>
                        <td><span class="badge bg-success"><?= $n['status'] ?></span></td>
                        <td><?= formatDate($n['created_at']) ?></td>
                        <td>
                            <a href="../news/<?= $n['slug'] ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                            <a href="edit.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                            <a href="?action=delete&id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this article?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if ($action === 'add' || $action === 'edit'): ?>
        <div class="card">
            <div class="card-body">
                <h5><?= $action === 'add' ? 'Add New Article' : 'Edit Article' ?></h5>
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea name="full_content" class="form-control" rows="10" id="editor"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <?php foreach ($categories as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= $c['category_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Featured Image</label>
                                <input type="file" name="featured_image" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tags (comma separated)</label>
                                <input type="text" name="tags" class="form-control">
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="featured" value="1">
                                <label class="form-check-label">Featured</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_breaking" value="1">
                                <label class="form-check-label">Breaking News</label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                    <option value="pending">Pending Review</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger mt-3">Save Article</button>
                    <a href="?action=list" class="btn btn-secondary mt-3">Cancel</a>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>