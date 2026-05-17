<?php
// templates/news-card.php
// Reusable news card component
function renderNewsCard($news, $showCategory = true) {
    global $BASE_URL;
    ?>
    <div class="card news-card h-100 border-0 shadow-sm">
        <div class="position-relative">
            <img src="<?= $news['featured_image'] ? $BASE_URL . '/assets/uploads/' . $news['featured_image'] : 'https://picsum.photos/300/180' ?>" 
                 class="card-img-top" style="height: 180px; object-fit: cover;" alt="">
            <?php if ($showCategory && !empty($news['category_name'])): ?>
                <span class="badge bg-danger position-absolute top-0 start-0 m-2"><?= sanitize($news['category_name']) ?></span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <h6 class="card-title">
                <a href="<?= $BASE_URL ?>/news/<?= $news['slug'] ?>" class="text-dark text-decoration-none">
                    <?= sanitize($news['title']) ?>
                </a>
            </h6>
            <p class="card-text small text-muted"><?= truncateText($news['short_description'], 80) ?></p>
        </div>
        <div class="card-footer bg-white border-0 d-flex justify-content-between small text-muted">
            <span><i class="fas fa-user me-1"></i><?= sanitize($news['author_name'] ?? 'Admin') ?></span>
            <span><i class="fas fa-eye me-1"></i><?= $news['views'] ?></span>
        </div>
    </div>
    <?php
}
?>