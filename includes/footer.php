<?php
$settings = getSettings();
?>
<footer class="bg-dark text-light pt-5 pb-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5 class="text-danger fw-bold mb-3"><?= $settings['website_name'] ?? SITE_NAME ?></h5>
                <p class="text-muted">Your trusted source for the latest breaking news, in-depth analysis, and trending stories from around the world.</p>
                <div class="d-flex gap-3 mt-3">
                    <?php if (!empty($settings['facebook_url'])): ?><a href="<?= $settings['facebook_url'] ?>" class="text-light"><i class="fab fa-facebook fa-lg"></i></a><?php endif; ?>
                    <?php if (!empty($settings['twitter_url'])): ?><a href="<?= $settings['twitter_url'] ?>" class="text-light"><i class="fab fa-twitter fa-lg"></i></a><?php endif; ?>
                    <?php if (!empty($settings['instagram_url'])): ?><a href="<?= $settings['instagram_url'] ?>" class="text-light"><i class="fab fa-instagram fa-lg"></i></a><?php endif; ?>
                    <?php if (!empty($settings['youtube_url'])): ?><a href="<?= $settings['youtube_url'] ?>" class="text-light"><i class="fab fa-youtube fa-lg"></i></a><?php endif; ?>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 mb-4">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="<?= BASE_URL ?>/about.php" class="text-muted text-decoration-none">About Us</a></li>
                    <li><a href="<?= BASE_URL ?>/contact.php" class="text-muted text-decoration-none">Contact Us</a></li>
                    <li><a href="<?= BASE_URL ?>/privacy.php" class="text-muted text-decoration-none">Privacy Policy</a></li>
                    <li><a href="<?= BASE_URL ?>/terms.php" class="text-muted text-decoration-none">Terms & Conditions</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-4 mb-4">
                <h6 class="fw-bold mb-3">Categories</h6>
                <ul class="list-unstyled">
                    <?php foreach (array_slice($categories ?? [], 0, 6) as $cat): ?>
                    <li><a href="<?= BASE_URL ?>/category/<?= $cat['slug'] ?>" class="text-muted text-decoration-none"><?= sanitize($cat['category_name']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="col-lg-4 col-md-4 mb-4">
                <h6 class="fw-bold mb-3">Newsletter</h6>
                <p class="text-muted small">Stay updated with the latest news and exclusive stories.</p>
                <form id="newsletterForm" class="d-flex gap-2">
                    <input type="email" id="newsletterEmail" class="form-control form-control-sm" placeholder="Your email" required>
                    <button type="submit" class="btn btn-danger btn-sm">Subscribe</button>
                </form>
                <div id="newsletterMsg" class="small mt-2"></div>
            </div>
        </div>
        
        <hr class="my-4 border-secondary">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-muted small">
            <div>&copy; <?= date('Y') ?> <?= $settings['website_name'] ?? SITE_NAME ?>. All Rights Reserved.</div>
            <div class="mt-2 mt-md-0">Designed with <i class="fas fa-heart text-danger"></i> for journalism</div>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>