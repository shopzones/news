<?php
require_once 'config/config.php';
require_once 'includes/header.php';

$featuredNews = getFeaturedNews(5);
$latestNews = getNews('published', 12);
$trendingNews = getTrendingNews(6);
$categories = getCategories(1);
$breakingNews = getBreakingNews(4);
?>
<div class="container py-4">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner rounded-3 shadow">
                    <?php foreach ($featuredNews as $index => $news): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <a href="<?= BASE_URL ?>/news/<?= $news['slug'] ?>">
                            <img src="<?= $news['featured_image'] ? BASE_URL . '/assets/uploads/' . $news['featured_image'] : 'https://picsum.photos/900/500' ?>" class="d-block w-100" style="height: 500px; object-fit: cover;" alt="">
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-75 p-4 rounded">
                                <span class="badge bg-danger mb-2"><?= sanitize($news['category_name']) ?></span>
                                <h3 class="fw-bold"><?= sanitize($news['title']) ?></h3>
                                <p class="mb-0"><?= truncateText($news['short_description'], 120) ?></p>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
        
        <div class="col-lg-4">
            <h5 class="fw-bold mb-3"><i class="fas fa-chart-line text-danger me-2"></i>Trending Now</h5>
            <?php foreach ($trendingNews as $index => $news): ?>
            <div class="d-flex mb-3">
                <div class="flex-shrink-0 position-relative me-3">
                    <img src="<?= $news['featured_image'] ? BASE_URL . '/assets/uploads/' . $news['featured_image'] : 'https://picsum.photos/80/80' ?>" class="rounded" width="80" height="80" alt="">
                    <span class="position-absolute top-0 start-0 badge bg-danger"><?= $index + 1 ?></span>
                </div>
                <div>
                    <a href="<?= BASE_URL ?>/news/<?= $news['slug'] ?>" class="fw-semibold text-dark text-decoration-none"><?= truncateText($news['title'], 70) ?></a>
                    <div class="small text-muted mt-1"><?= sanitize($news['category_name']) ?> • <?= $news['views'] ?> views</div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Latest News Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold"><i class="fas fa-newspaper text-danger me-2"></i>Latest News</h4>
        <a href="<?= BASE_URL ?>/category/all" class="btn btn-outline-danger btn-sm">View All</a>
    </div>
    
    <div class="row">
        <?php foreach ($latestNews as $news): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card news-card h-100 border-0 shadow-sm">
                <div class="position-relative">
                    <img src="<?= $news['featured_image'] ? BASE_URL . '/assets/uploads/' . $news['featured_image'] : 'https://picsum.photos/300/180' ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="">
                    <span class="badge bg-danger position-absolute top-0 start-0 m-2"><?= sanitize($news['category_name']) ?></span>
                    <?php if ($news['is_breaking']): ?>
                    <span class="badge bg-warning position-absolute top-0 end-0 m-2">BREAKING</span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h6 class="card-title"><a href="<?= BASE_URL ?>/news/<?= $news['slug'] ?>" class="text-dark text-decoration-none"><?= sanitize($news['title']) ?></a></h6>
                    <p class="card-text small text-muted"><?= truncateText($news['short_description'], 80) ?></p>
                </div>
                <div class="card-footer bg-white border-0 d-flex justify-content-between small text-muted">
                    <span><i class="fas fa-user me-1"></i><?= sanitize($news['author_name']) ?></span>
                    <span><i class="fas fa-eye me-1"></i><?= $news['views'] ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Category Sections -->
<?php 
$catNews = [];
foreach (array_slice($categories, 0, 6) as $cat) {
    $catNews[$cat['id']] = getNewsByCategory($cat['id'], 4);
}
foreach ($catNews as $catId => $newsList):
    if (empty($newsList)) continue;
    $cat = getCategoryById($catId);
?>
<div class="bg-light py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="fas fa-folder-open text-danger me-2"></i><?= sanitize($cat['category_name']) ?></h4>
            <a href="<?= BASE_URL ?>/category/<?= $cat['slug'] ?>" class="btn btn-sm btn-outline-danger">More in <?= sanitize($cat['category_name']) ?></a>
        </div>
        <div class="row">
            <?php foreach ($newsList as $news): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="<?= $news['featured_image'] ? BASE_URL . '/assets/uploads/' . $news['featured_image'] : 'https://picsum.photos/300/160' ?>" class="card-img-top" style="height: 160px; object-fit: cover;" alt="">
                    <div class="card-body">
                        <h6 class="card-title"><a href="<?= BASE_URL ?>/news/<?= $news['slug'] ?>" class="text-dark text-decoration-none"><?= sanitize($news['title']) ?></a></h6>
                        <small class="text-muted"><?= formatDate($news['created_at']) ?></small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- Newsletter CTA -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="p-5 bg-danger text-white rounded-3">
                <h3 class="fw-bold">Never Miss a Story</h3>
                <p class="lead">Subscribe to our newsletter and get the most important news delivered to your inbox.</p>
                <form class="row g-2 justify-content-center" id="homeNewsletter">
                    <div class="col-md-5">
                        <input type="email" class="form-control" placeholder="Your email address" required>
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-light text-danger fw-bold px-4">Subscribe Free</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>