<?php
require_once __DIR__ . '/../config/config.php';
$settings = getSettings();
$categories = getNavigationMenu();
$breakingNews = getBreakingNews(5);
$currentUser = isLoggedIn() ? getUserById($_SESSION['user_id']) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $settings['seo_title'] ?? SITE_NAME ?></title>
    <meta name="description" content="<?= $settings['seo_description'] ?? '' ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="icon" href="<?= $settings['favicon'] ?: BASE_URL . '/assets/images/favicon.png' ?>">
    <?php if (!empty($settings['google_analytics_id'])): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $settings['google_analytics_id'] ?>"></script>
    <script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', '<?= $settings['google_analytics_id'] ?>');</script>
    <?php endif; ?>
</head>
<body class="<?= getSetting('dark_mode_default') == '1' ? 'dark-mode' : '' ?>">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">
            <?php if (!empty($settings['logo'])): ?>
                <img src="<?= BASE_URL ?>/assets/uploads/<?= $settings['logo'] ?>" alt="<?= SITE_NAME ?>" height="40">
            <?php else: ?>
                <span class="text-danger"><?= $settings['website_name'] ?? SITE_NAME ?></span>
            <?php endif; ?>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">Home</a></li>
                <?php foreach (array_slice($categories, 0, 8) as $cat): ?>
                <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/category/<?= $cat['slug'] ?>"><?= sanitize($cat['category_name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Search -->
                <form class="d-flex" action="<?= BASE_URL ?>/search.php" method="get">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search news..." required>
                        <button class="btn btn-outline-light btn-sm" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
                
                <!-- Dark Mode Toggle -->
                <button id="darkModeToggle" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-moon"></i>
                </button>
                
                <!-- Social Icons -->
                <div class="d-none d-lg-flex gap-2">
                    <?php if (!empty($settings['facebook_url'])): ?>
                    <a href="<?= $settings['facebook_url'] ?>" target="_blank" class="text-light"><i class="fab fa-facebook-f"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($settings['twitter_url'])): ?>
                    <a href="<?= $settings['twitter_url'] ?>" target="_blank" class="text-light"><i class="fab fa-twitter"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($settings['instagram_url'])): ?>
                    <a href="<?= $settings['instagram_url'] ?>" target="_blank" class="text-light"><i class="fab fa-instagram"></i></a>
                    <?php endif; ?>
                </div>
                
                <!-- User Menu -->
                <?php if (isLoggedIn()): ?>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-light text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="<?= $currentUser['image'] ? BASE_URL . '/assets/uploads/' . $currentUser['image'] : 'https://ui-avatars.com/api/?name=' . urlencode($currentUser['name']) ?>" class="rounded-circle me-2" width="32" height="32" alt="">
                        <span class="d-none d-lg-inline"><?= sanitize($currentUser['name']) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <?php if (isAdmin()): ?>
                        <li><a class="dropdown-item" href="<?= ADMIN_URL ?>/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i>Admin Panel</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
                <?php else: ?>
                <a href="<?= BASE_URL ?>/login.php" class="btn btn-outline-light btn-sm">Login</a>
                <a href="<?= BASE_URL ?>/register.php" class="btn btn-danger btn-sm">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Breaking News Ticker -->
<?php if (!empty($breakingNews)): ?>
<div class="breaking-news bg-danger text-white py-1">
    <div class="container d-flex align-items-center">
        <span class="fw-bold me-3"><i class="fas fa-bolt me-1"></i> BREAKING</span>
        <div class="ticker-wrapper flex-grow-1 overflow-hidden">
            <div class="ticker">
                <?php foreach ($breakingNews as $news): ?>
                <a href="<?= BASE_URL ?>/news/<?= $news['slug'] ?>" class="text-white text-decoration-none me-4"><?= sanitize($news['title']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>