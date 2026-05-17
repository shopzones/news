<?php
require_once 'config/config.php';
require_once 'includes/header.php';

$slug = $_GET['slug'] ?? '';
$category = getCategoryBySlug($slug);
if (!$category) {
    header("Location: index.php");
    exit;
}

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;

$newsList = getNewsByCategory($category['id'], $perPage, null);
$totalNews = getNewsCount('published'); // simplified
?>
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-4"><?= sanitize($category['category_name']) ?></h2>
            
            <div class="row">
                <?php foreach ($newsList as $news): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <img src="<?= $news['featured_image'] ? BASE_URL . '/assets/uploads/' . $news['featured_image'] : 'https://picsum.photos/600/300' ?>" class="card-img-top" style="height: 220px; object-fit: cover;" alt="">
                        <div class="card-body">
                            <span class="badge bg-danger mb-2"><?= sanitize($category['category_name']) ?></span>
                            <h5><a href="<?= BASE_URL ?>/news/<?= $news['slug'] ?>" class="text-dark text-decoration-none"><?= sanitize($news['title']) ?></a></h5>
                            <p class="text-muted small"><?= truncateText($news['short_description'], 100) ?></p>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between small text-muted">
                            <span><?= sanitize($news['author_name']) ?></span>
                            <span><?= formatDate($news['created_at']) ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?= getPagination($totalNews, $page, $perPage, BASE_URL . '/category/' . $slug) ?>
        </div>
        
        <div class="col-lg-4">
            <?php include 'includes/sidebar.php'; ?>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>