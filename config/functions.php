<?php
require_once __DIR__ . '/database.php';
global $db;
if (!$db) {
    $db = (new Database())->getConnection();
}

function getSettings() {
    global $db;
    $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
    $settings = [];
    foreach ($stmt->fetchAll() as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

function getSetting($key) {
    global $db;
    $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    return $stmt->fetchColumn() ?: '';
}

function getCategories($status = 1, $limit = null) {
    global $db;
    $sql = "SELECT * FROM categories WHERE status = ? ORDER BY category_name ASC";
    if ($limit) $sql .= " LIMIT " . (int)$limit;
    $stmt = $db->prepare($sql);
    $stmt->execute([$status]);
    return $stmt->fetchAll();
}

function getCategoryBySlug($slug) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM categories WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function getCategoryById($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getNews($status = 'published', $limit = 12, $offset = 0) {
    global $db;
    $sql = "SELECT n.*, c.category_name, c.slug as category_slug, u.name as author_name 
            FROM news n 
            LEFT JOIN categories c ON n.category_id = c.id 
            LEFT JOIN users u ON n.author_id = u.id 
            WHERE n.status = ? 
            ORDER BY n.created_at DESC 
            LIMIT ? OFFSET ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$status, $limit, $offset]);
    return $stmt->fetchAll();
}

function getNewsBySlug($slug) {
    global $db;
    $stmt = $db->prepare("SELECT n.*, c.category_name, c.slug as category_slug, u.name as author_name, u.image as author_image 
                          FROM news n 
                          LEFT JOIN categories c ON n.category_id = c.id 
                          LEFT JOIN users u ON n.author_id = u.id 
                          WHERE n.slug = ? AND n.status = 'published'");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function getNewsById($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getFeaturedNews($limit = 5) {
    global $db;
    $stmt = $db->prepare("SELECT n.*, c.category_name, u.name as author_name FROM news n 
                          LEFT JOIN categories c ON n.category_id = c.id 
                          LEFT JOIN users u ON n.author_id = u.id 
                          WHERE n.featured = 1 AND n.status = 'published' 
                          ORDER BY n.created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getBreakingNews($limit = 5) {
    global $db;
    $stmt = $db->prepare("SELECT title, slug FROM news WHERE is_breaking = 1 AND status = 'published' ORDER BY created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getTrendingNews($limit = 5) {
    global $db;
    $stmt = $db->prepare("SELECT n.*, c.category_name FROM news n 
                          LEFT JOIN categories c ON n.category_id = c.id 
                          WHERE n.status = 'published' ORDER BY n.views DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getNewsByCategory($categoryId, $limit = 10, $excludeId = null) {
    global $db;
    $sql = "SELECT n.*, c.category_name, u.name as author_name FROM news n 
            LEFT JOIN categories c ON n.category_id = c.id 
            LEFT JOIN users u ON n.author_id = u.id 
            WHERE n.category_id = ? AND n.status = 'published'";
    if ($excludeId) $sql .= " AND n.id != ?";
    $sql .= " ORDER BY n.created_at DESC LIMIT ?";
    $stmt = $db->prepare($sql);
    $params = $excludeId ? [$categoryId, $excludeId, $limit] : [$categoryId, $limit];
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getRelatedNews($categoryId, $newsId, $limit = 4) {
    global $db;
    $stmt = $db->prepare("SELECT n.*, c.category_name FROM news n 
                          LEFT JOIN categories c ON n.category_id = c.id 
                          WHERE n.category_id = ? AND n.id != ? AND n.status = 'published' 
                          ORDER BY n.created_at DESC LIMIT ?");
    $stmt->execute([$categoryId, $newsId, $limit]);
    return $stmt->fetchAll();
}

function getPopularPosts($limit = 5) {
    global $db;
    return getTrendingNews($limit);
}

function searchNews($query, $limit = 12, $offset = 0) {
    global $db;
    $search = "%$query%";
    $stmt = $db->prepare("SELECT n.*, c.category_name, u.name as author_name FROM news n 
                          LEFT JOIN categories c ON n.category_id = c.id 
                          LEFT JOIN users u ON n.author_id = u.id 
                          WHERE (n.title LIKE ? OR n.short_description LIKE ? OR n.full_content LIKE ?) 
                          AND n.status = 'published' 
                          ORDER BY n.created_at DESC LIMIT ? OFFSET ?");
    $stmt->execute([$search, $search, $search, $limit, $offset]);
    return $stmt->fetchAll();
}

function getNewsCount($status = 'published') {
    global $db;
    $stmt = $db->prepare("SELECT COUNT(*) FROM news WHERE status = ?");
    $stmt->execute([$status]);
    return $stmt->fetchColumn();
}

function getCommentsByNews($newsId, $status = 'approved') {
    global $db;
    $stmt = $db->prepare("SELECT c.*, u.name as user_name, u.image as user_image FROM comments c 
                          LEFT JOIN users u ON c.user_id = u.id 
                          WHERE c.news_id = ? AND c.status = ? 
                          ORDER BY c.created_at DESC");
    $stmt->execute([$newsId, $status]);
    return $stmt->fetchAll();
}

function getCommentCount($newsId) {
    global $db;
    $stmt = $db->prepare("SELECT COUNT(*) FROM comments WHERE news_id = ? AND status = 'approved'");
    $stmt->execute([$newsId]);
    return $stmt->fetchColumn();
}

function addComment($newsId, $userId, $comment, $parentId = null) {
    global $db;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $stmt = $db->prepare("INSERT INTO comments (news_id, user_id, parent_id, comment, ip_address) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$newsId, $userId, $parentId, $comment, $ip]);
}

function getTags() {
    global $db;
    $stmt = $db->query("SELECT tags FROM news WHERE tags IS NOT NULL AND status = 'published'");
    $allTags = [];
    foreach ($stmt->fetchAll() as $row) {
        $tags = explode(',', $row['tags']);
        foreach ($tags as $t) {
            $t = trim($t);
            if ($t) $allTags[$t] = true;
        }
    }
    return array_keys($allTags);
}

function getNewsByTag($tag, $limit = 10) {
    global $db;
    $search = "%$tag%";
    $stmt = $db->prepare("SELECT n.*, c.category_name FROM news n 
                          LEFT JOIN categories c ON n.category_id = c.id 
                          WHERE n.tags LIKE ? AND n.status = 'published' 
                          ORDER BY n.created_at DESC LIMIT ?");
    $stmt->execute([$search, $limit]);
    return $stmt->fetchAll();
}

function incrementViews($newsId) {
    global $db;
    $stmt = $db->prepare("UPDATE news SET views = views + 1 WHERE id = ?");
    return $stmt->execute([$newsId]);
}

function getAdvertisement($position) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM advertisements WHERE position = ? AND status = 1 AND (start_date IS NULL OR start_date <= CURDATE()) AND (end_date IS NULL OR end_date >= CURDATE()) ORDER BY RAND() LIMIT 1");
    $stmt->execute([$position]);
    return $stmt->fetch();
}

function subscribeEmail($email) {
    global $db;
    $token = bin2hex(random_bytes(16));
    $stmt = $db->prepare("INSERT IGNORE INTO subscribers (email, token) VALUES (?, ?)");
    return $stmt->execute([$email, $token]);
}

function unsubscribeEmail($token) {
    global $db;
    $stmt = $db->prepare("UPDATE subscribers SET status = 0 WHERE token = ?");
    return $stmt->execute([$token]);
}

function getUserById($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getUserByEmail($email) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function registerUser($data) {
    global $db;
    $stmt = $db->prepare("INSERT INTO users (name, email, phone, password, role, image) VALUES (?, ?, ?, ?, ?, ?)");
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    return $stmt->execute([$data['name'], $data['email'], $data['phone'] ?? '', $password, $data['role'] ?? 'subscriber', $data['image'] ?? '']);
}

function updateUser($id, $data) {
    global $db;
    $fields = [];
    $values = [];
    foreach ($data as $k => $v) {
        $fields[] = "$k = ?";
        $values[] = $v;
    }
    $values[] = $id;
    $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
    $stmt = $db->prepare($sql);
    return $stmt->execute($values);
}

function loginUser($email, $password) {
    global $db;
    $user = getUserByEmail($email);
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && in_array($_SESSION['role'], ['super_admin', 'admin', 'editor', 'moderator']);
}

function getRoleName($role) {
    $roles = [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'editor' => 'Editor',
        'reporter' => 'Reporter',
        'moderator' => 'Moderator',
        'subscriber' => 'Subscriber'
    ];
    return $roles[$role] ?? 'User';
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    return strtolower($text);
}

function truncateText($text, $length = 100) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

function formatDate($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    if ($diff->y) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'just now';
}

function readingTime($content) {
    $words = str_word_count(strip_tags($content));
    $minutes = ceil($words / 200);
    return max(1, $minutes);
}

function generateMetaTags($title, $description, $image = '', $url = '') {
    global $settings;
    $base = BASE_URL;
    $image = $image ?: ($settings['og_image'] ?? '');
    $url = $url ?: $base;
    return '
    <meta name="title" content="' . sanitize($title) . '">
    <meta name="description" content="' . sanitize($description) . '">
    <meta property="og:type" content="website">
    <meta property="og:url" content="' . $url . '">
    <meta property="og:title" content="' . sanitize($title) . '">
    <meta property="og:description" content="' . sanitize($description) . '">
    <meta property="og:image" content="' . $image . '">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="' . $url . '">
    <meta property="twitter:title" content="' . sanitize($title) . '">
    <meta property="twitter:description" content="' . sanitize($description) . '">
    <meta property="twitter:image" content="' . $image . '">';
}

function logActivity($userId, $action, $description = '') {
    global $db;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$userId, $action, $description, $ip]);
}

function getAnalytics($days = 30) {
    global $db;
    $stmt = $db->prepare("SELECT visit_date, COUNT(*) as visits FROM analytics WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY) GROUP BY visit_date ORDER BY visit_date");
    $stmt->execute([$days]);
    return $stmt->fetchAll();
}

function recordVisit($pageUrl) {
    global $db;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $ref = $_SERVER['HTTP_REFERER'] ?? '';
    $stmt = $db->prepare("INSERT INTO analytics (page_url, ip_address, user_agent, referrer, visit_date, visit_time) VALUES (?, ?, ?, ?, CURDATE(), CURTIME())");
    return $stmt->execute([$pageUrl, $ip, $ua, $ref]);
}

function getTotalVisitors($days = 30) {
    global $db;
    $stmt = $db->prepare("SELECT COUNT(DISTINCT ip_address) FROM analytics WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)");
    $stmt->execute([$days]);
    return $stmt->fetchColumn();
}

function getRecentActivity($limit = 10) {
    global $db;
    $stmt = $db->prepare("SELECT a.*, u.name as user_name FROM activity_logs a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function uploadFile($file, $folder = 'news') {
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'mp4', 'mov'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return false;
    $newName = uniqid() . '.' . $ext;
    $path = UPLOAD_PATH . $folder . '/' . $newName;
    if (!is_dir(dirname($path))) mkdir(dirname($path), 0755, true);
    if (move_uploaded_file($file['tmp_name'], $path)) {
        return $folder . '/' . $newName;
    }
    return false;
}

function deleteFile($filePath) {
    $full = UPLOAD_PATH . $filePath;
    if (file_exists($full)) return unlink($full);
    return false;
}

function getMedia($folder = '/', $limit = 20) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM media WHERE folder = ? ORDER BY created_at DESC LIMIT ?");
    $stmt->execute([$folder, $limit]);
    return $stmt->fetchAll();
}

function sendEmail($to, $subject, $message) {
    $headers = "From: " . getSetting('contact_email') . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    return mail($to, $subject, $message, $headers);
}

function getPolls() {
    global $db;
    $stmt = $db->query("SELECT * FROM polls WHERE status = 1 ORDER BY created_at DESC");
    $polls = $stmt->fetchAll();
    foreach ($polls as &$p) {
        $p['options'] = json_decode($p['options'], true);
    }
    return $polls;
}

function votePoll($pollId, $optionIndex, $ip) {
    global $db;
    $stmt = $db->prepare("INSERT INTO poll_votes (poll_id, option_index, ip_address) VALUES (?, ?, ?)");
    return $stmt->execute([$pollId, $optionIndex, $ip]);
}

function getPollResults($pollId) {
    global $db;
    $stmt = $db->prepare("SELECT option_index, COUNT(*) as votes FROM poll_votes WHERE poll_id = ? GROUP BY option_index");
    $stmt->execute([$pollId]);
    return $stmt->fetchAll();
}

function getNavigationMenu() {
    return getCategories(1, 10);
}

function getSeoMeta($type, $data = []) {
    $settings = getSettings();
    if ($type === 'home') {
        return generateMetaTags($settings['seo_title'] ?? SITE_NAME, $settings['seo_description'] ?? '', '', BASE_URL);
    }
    if ($type === 'news' && isset($data['title'])) {
        return generateMetaTags($data['title'], $data['seo_description'] ?? '', $data['featured_image'] ?? '', BASE_URL . '/news/' . $data['slug']);
    }
    return generateMetaTags(SITE_NAME, '');
}

function generateSitemap() {
    global $db;
    header('Content-Type: application/xml');
    $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    $xml .= '<url><loc>' . BASE_URL . '</loc></url>';
    $news = $db->query("SELECT slug, updated_at FROM news WHERE status='published'")->fetchAll();
    foreach ($news as $n) {
        $xml .= '<url><loc>' . BASE_URL . '/news/' . $n['slug'] . '</loc><lastmod>' . date('Y-m-d', strtotime($n['updated_at'])) . '</lastmod></url>';
    }
    $cats = $db->query("SELECT slug FROM categories WHERE status=1")->fetchAll();
    foreach ($cats as $c) {
        $xml .= '<url><loc>' . BASE_URL . '/category/' . $c['slug'] . '</loc></url>';
    }
    $xml .= '</urlset>';
    return $xml;
}

function validateCsrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . generateCsrfToken() . '">';
}

function getPagination($total, $currentPage, $perPage, $url) {
    $totalPages = ceil($total / $perPage);
    if ($totalPages <= 1) return '';
    $html = '<nav><ul class="pagination justify-content-center">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = $i == $currentPage ? 'active' : '';
        $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="' . $url . '?page=' . $i . '">' . $i . '</a></li>';
    }
    $html .= '</ul></nav>';
    return $html;
}