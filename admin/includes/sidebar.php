<div class="admin-sidebar p-3" style="width: 260px; background: #1F2937; min-height: 100vh;">
    <div class="text-center mb-4 pt-3">
        <h4 class="text-white fw-bold"><span class="text-danger">News</span>Portal</h4>
        <small class="text-muted">Admin Panel</small>
    </div>
    
    <nav class="nav flex-column">
        <a href="<?= ADMIN_URL ?>/dashboard.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
        <a href="<?= ADMIN_URL ?>/news/" class="nav-link">
            <i class="fas fa-newspaper me-2"></i> Manage News
        </a>
        <a href="<?= ADMIN_URL ?>/categories/" class="nav-link">
            <i class="fas fa-folder me-2"></i> Categories
        </a>
        <a href="<?= ADMIN_URL ?>/users/" class="nav-link">
            <i class="fas fa-users me-2"></i> Users
        </a>
        <a href="<?= ADMIN_URL ?>/comments/" class="nav-link">
            <i class="fas fa-comments me-2"></i> Comments
        </a>
        <a href="<?= ADMIN_URL ?>/ads/" class="nav-link">
            <i class="fas fa-ad me-2"></i> Advertisements
        </a>
        <a href="<?= ADMIN_URL ?>/settings/" class="nav-link">
            <i class="fas fa-cog me-2"></i> Settings
        </a>
        <a href="<?= ADMIN_URL ?>/analytics/" class="nav-link">
            <i class="fas fa-chart-bar me-2"></i> Analytics
        </a>
        <hr class="border-secondary">
        <a href="<?= BASE_URL ?>" class="nav-link" target="_blank">
            <i class="fas fa-globe me-2"></i> View Website
        </a>
        <a href="<?= BASE_URL ?>/logout.php" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </nav>
</div>