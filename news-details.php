<?php
require_once 'config/config.php';
require_once 'includes/header.php';

$slug = $_GET['slug'] ?? '';
$news = getNewsBySlug($slug);

if (!$news) {
    header("HTTP/1.0 404 Not Found");
    include '404.php';
    exit;
}

incrementViews($news['id']);
$relatedNews = getRelatedNews($news['category_id'], $news['id'], 4);
$comments = getCommentsByNews($news['id']);
$commentCount = getCommentCount($news['id']);
$ad = getAdvertisement('between_posts');
?>
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/category/<?= $news['category_slug'] ?>"><?= sanitize($news['category_name']) ?></a></li>
            <li class="breadcrumb-item active"><?= truncateText($news['title'], 50) ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <!-- Article Header -->
            <h1 class="fw-bold mb-3"><?= sanitize($news['title']) ?></h1>
            
            <div class="d-flex flex-wrap align-items-center gap-3 mb-4 text-muted small">
                <span><i class="fas fa-user me-1"></i> <?= sanitize($news['author_name']) ?></span>
                <span><i class="fas fa-calendar me-1"></i> <?= formatDate($news['publish_date'] ?: $news['created_at']) ?></span>
                <span><i class="fas fa-clock me-1"></i> <?= readingTime($news['full_content']) ?> min read</span>
                <span><i class="fas fa-eye me-1"></i> <?= number_format($news['views']) ?> views</span>
                <span><i class="fas fa-comments me-1"></i> <?= $commentCount ?> comments</span>
                
                <div class="ms-auto d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary copy-link-btn"><i class="fas fa-link me-1"></i> Copy</button>
                    <button class="btn btn-sm btn-outline-secondary" onclick="printArticle()"><i class="fas fa-print me-1"></i> Print</button>
                </div>
            </div>

            <!-- Featured Image -->
            <?php if ($news['featured_image']): ?>
            <img src="<?= BASE_URL ?>/assets/uploads/<?= $news['featured_image'] ?>" class="img-fluid rounded-3 shadow mb-4 w-100" style="max-height: 520px; object-fit: cover;" alt="">
            <?php endif; ?>

            <!-- Social Share -->
            <div class="mb-4 d-flex gap-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL . '/news/' . $news['slug']) ?>" target="_blank" class="btn btn-sm btn-primary"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode(BASE_URL . '/news/' . $news['slug']) ?>&text=<?= urlencode($news['title']) ?>" target="_blank" class="btn btn-sm btn-info"><i class="fab fa-twitter"></i></a>
                <a href="https://wa.me/?text=<?= urlencode($news['title'] . ' ' . BASE_URL . '/news/' . $news['slug']) ?>" target="_blank" class="btn btn-sm btn-success"><i class="fab fa-whatsapp"></i></a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode(BASE_URL . '/news/' . $news['slug']) ?>" target="_blank" class="btn btn-sm btn-secondary"><i class="fab fa-linkedin-in"></i></a>
            </div>

            <!-- Article Content -->
            <div class="article-content fs-6">
                <?= $news['full_content'] ?>
            </div>

            <!-- Tags -->
            <?php if ($news['tags']): ?>
            <div class="mt-4 pt-4 border-top">
                <strong>Tags:</strong>
                <?php foreach (explode(',', $news['tags']) as $tag): ?>
                <a href="<?= BASE_URL ?>/search.php?q=<?= urlencode(trim($tag)) ?>" class="badge bg-light text-dark me-1">#<?= sanitize(trim($tag)) ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Like / Dislike -->
            <div class="mt-4 d-flex align-items-center gap-3">
                <button class="btn btn-outline-danger btn-sm"><i class="fas fa-thumbs-up"></i> <span id="likeCount">124</span></button>
                <button class="btn btn-outline-secondary btn-sm"><i class="fas fa-thumbs-down"></i></button>
                <span class="text-muted small">Was this article helpful?</span>
            </div>

            <!-- Advertisement -->
            <?php if ($ad): ?>
            <div class="my-5 text-center">
                <?php if ($ad['image']): ?>
                <a href="<?= $ad['link'] ?: '#' ?>" target="_blank">
                    <img src="<?= BASE_URL ?>/assets/uploads/<?= $ad['image'] ?>" class="img-fluid rounded" style="max-height: 180px;" alt="">
                </a>
                <?php else: ?>
                <div class="p-4 bg-light rounded"><?= $ad['code'] ?></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Comments Section -->
            <div class="mt-5">
                <h5 class="fw-bold mb-4">Comments (<?= $commentCount ?>)</h5>
                
                <?php if (isLoggedIn()): ?>
                <form method="post" action="submit-comment.php" class="mb-4">
                    <?= csrfField() ?>
                    <input type="hidden" name="news_id" value="<?= $news['id'] ?>">
                    <div class="mb-3">
                        <textarea name="comment" class="form-control" rows="3" placeholder="Write your comment..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Post Comment</button>
                </form>
                <?php else: ?>
                <div class="alert alert-info">Please <a href="<?= BASE_URL ?>/login.php">login</a> to leave a comment.</div>
                <?php endif; ?>

                <?php foreach ($comments as $comment): ?>
                <div class="d-flex mb-3">
                    <img src="<?= $comment['user_image'] ? BASE_URL . '/assets/uploads/' . $comment['user_image'] : 'https://ui-avatars.com/api/?name=' . urlencode($comment['user_name']) ?>" class="rounded-circle me-3" width="48" height="48" alt="">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <strong><?= sanitize($comment['user_name']) ?></strong>
                            <small class="text-muted"><?= timeAgo($comment['created_at']) ?></small>
                        </div>
                        <p class="mb-1"><?= nl2br(sanitize($comment['comment'])) ?></p>
                        <a href="#" class="small text-muted">Reply</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <?php include 'includes/sidebar.php'; ?>
        </div>
    </div>

    <!-- Related News -->
    <div class="mt-5">
        <h5 class="fw-bold mb-4">Related Articles</h5>
        <div class="row">
            <?php foreach ($relatedNews as $rel): ?>
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="<?= $rel['featured_image'] ? BASE_URL . '/assets/uploads/' . $rel['featured_image'] : 'https://picsum.photos/300/160' ?>" class="card-img-top" style="height: 160px; object-fit: cover;" alt="">
                    <div class="card-body">
                        <h6><a href="<?= BASE_URL ?>/news/<?= $rel['slug'] ?>" class="text-dark text-decoration-none"><?= sanitize($rel['title']) ?></a></h6>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>