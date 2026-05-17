<?php
require_once 'config/config.php';
require_once 'includes/header.php';

$query = sanitize($_GET['q'] ?? '');
$results = $query ? searchNews($query, 20) : [];
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-4">Search Results</h2>
            
            <form class="mb-4" method="get">
                <div class="input-group input-group-lg">
                    <input type="text" name="q" class="form-control" placeholder="Search news..." value="<?= $query ?>">
                    <button class="btn btn-danger" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>
            
            <?php if ($query): ?>
                <p class="text-muted">Showing results for "<?= $query ?>"</p>
                
                <?php if (empty($results)): ?>
                    <div class="alert alert-warning">No results found.</div>
                <?php else: ?>
                    <?php foreach ($results as $news): ?>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="<?= $news['featured_image'] ? BASE_URL . '/assets/uploads/' . $news['featured_image'] : 'https://picsum.photos/300/180' ?>" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5><a href="<?= BASE_URL ?>/news/<?= $news['slug'] ?>" class="text-dark"><?= sanitize($news['title']) ?></a></h5>
                                    <p class="card-text small"><?= truncateText($news['short_description'], 120) ?></p>
                                    <small class="text-muted"><?= sanitize($news['category_name']) ?> • <?= formatDate($news['created_at']) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>