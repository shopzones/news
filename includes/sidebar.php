<?php
$popularPosts = getPopularPosts(5);
$categories = getCategories(1);
$tags = getTags();
$sidebarAd = getAdvertisement('sidebar');
?>
<div class="sidebar">
    <!-- Popular Posts -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h6 class="mb-0"><i class="fas fa-fire me-2"></i>Popular Posts</h6>
        </div>
        <div class="card-body p-0">
            <?php foreach ($popularPosts as $post): ?>
            <a href="<?= BASE_URL ?>/news/<?= $post['slug'] ?>" class="d-flex p-3 text-decoration-none border-bottom">
                <div class="flex-shrink-0 me-3">
                    <img src="<?= $post['featured_image'] ? BASE_URL . '/assets/uploads/' . $post['featured_image'] : 'https://picsum.photos/60/60' ?>" class="rounded" width="60" height="60" alt="">
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 text-dark small"><?= truncateText($post['title'], 60) ?></h6>
                    <small class="text-muted"><?= formatDate($post['created_at']) ?> • <?= $post['views'] ?> views</small>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Categories -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h6 class="mb-0"><i class="fas fa-folder me-2"></i>Categories</h6>
        </div>
        <div class="list-group list-group-flush">
            <?php foreach ($categories as $cat): ?>
            <a href="<?= BASE_URL ?>/category/<?= $cat['slug'] ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <?= sanitize($cat['category_name']) ?>
                <span class="badge bg-danger rounded-pill">12</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Tags -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h6 class="mb-0"><i class="fas fa-tags me-2"></i>Tags</h6>
        </div>
        <div class="card-body">
            <?php foreach (array_slice($tags, 0, 15) as $tag): ?>
            <a href="<?= BASE_URL ?>/search.php?q=<?= urlencode($tag) ?>" class="badge bg-light text-dark me-1 mb-1 text-decoration-none">#<?= sanitize($tag) ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Advertisement -->
    <?php if ($sidebarAd): ?>
    <div class="card mb-4">
        <div class="card-body p-0">
            <?php if ($sidebarAd['image']): ?>
            <a href="<?= $sidebarAd['link'] ?: '#' ?>" target="_blank">
                <img src="<?= BASE_URL ?>/assets/uploads/<?= $sidebarAd['image'] ?>" class="img-fluid rounded" alt="<?= sanitize($sidebarAd['title']) ?>">
            </a>
            <?php else: ?>
            <div class="p-4 text-center bg-light"><?= $sidebarAd['code'] ?: 'Advertisement' ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Newsletter -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h6 class="mb-0"><i class="fas fa-envelope me-2"></i>Newsletter</h6>
        </div>
        <div class="card-body">
            <p class="small text-muted">Get the latest news delivered straight to your inbox.</p>
            <form id="sidebarNewsletter">
                <div class="input-group">
                    <input type="email" class="form-control" placeholder="Email address" required>
                    <button class="btn btn-danger" type="submit">Join</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Weather Widget -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-cloud-sun me-2"></i>Weather</h6>
        </div>
        <div class="card-body text-center">
            <i class="fas fa-sun fa-3x text-warning mb-2"></i>
            <h4>24°C</h4>
            <p class="mb-0 text-muted">New York, NY</p>
            <small class="text-muted">Sunny • Feels like 26°C</small>
        </div>
    </div>
</div>