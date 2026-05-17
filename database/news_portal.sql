USE news_portal;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255),
    role ENUM('super_admin','admin','editor','reporter','moderator','subscriber') DEFAULT 'subscriber',
    image VARCHAR(255) NULL,
    bio TEXT NULL,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_role (role),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100),
    slug VARCHAR(100) UNIQUE,
    icon VARCHAR(255) NULL,
    parent_id INT NULL,
    seo_title VARCHAR(255) NULL,
    seo_description TEXT NULL,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_parent_id (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    author_id INT,
    title VARCHAR(500),
    slug VARCHAR(500) UNIQUE,
    short_description TEXT,
    full_content LONGTEXT,
    featured_image VARCHAR(255) NULL,
    gallery TEXT NULL,
    tags VARCHAR(500) NULL,
    views INT DEFAULT 0,
    status ENUM('draft','pending','published','rejected') DEFAULT 'draft',
    is_breaking TINYINT DEFAULT 0,
    featured TINYINT DEFAULT 0,
    allow_comments TINYINT DEFAULT 1,
    reading_time INT DEFAULT 0,
    publish_date DATETIME NULL,
    seo_title VARCHAR(255) NULL,
    seo_description TEXT NULL,
    og_image VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_slug (slug),
    INDEX idx_created_at (created_at),
    INDEX idx_is_breaking (is_breaking),
    INDEX idx_featured (featured),
    INDEX idx_category_id (category_id),
    INDEX idx_author_id (author_id),
    INDEX idx_publish_date (publish_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    news_id INT,
    user_id INT NULL,
    parent_id INT NULL,
    comment TEXT,
    status ENUM('pending','approved','rejected','spam') DEFAULT 'pending',
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE,
    INDEX idx_news_id (news_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE advertisements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    type ENUM('banner','popup','video','sidebar') DEFAULT 'banner',
    image VARCHAR(255) NULL,
    code TEXT NULL,
    link VARCHAR(500) NULL,
    position ENUM('header','sidebar','footer','popup','between_posts') DEFAULT 'sidebar',
    width INT DEFAULT 300,
    height INT DEFAULT 250,
    start_date DATE NULL,
    end_date DATE NULL,
    clicks INT DEFAULT 0,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_position (position)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE,
    token VARCHAR(255) NULL,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE,
    setting_value LONGTEXT,
    INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE analytics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_url VARCHAR(500),
    ip_address VARCHAR(45),
    user_agent TEXT NULL,
    referrer VARCHAR(500) NULL,
    visit_date DATE,
    visit_time TIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_visit_date (visit_date),
    INDEX idx_page_url (page_url)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE newsletter_campaigns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(500),
    content LONGTEXT,
    sent_at DATETIME NULL,
    status ENUM('draft','sent','failed') DEFAULT 'draft',
    total_sent INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(255),
    file_path VARCHAR(500),
    file_type VARCHAR(50),
    file_size INT,
    folder VARCHAR(255) DEFAULT '/',
    uploaded_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE polls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(500),
    options TEXT,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE poll_votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    poll_id INT,
    option_index INT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (poll_id) REFERENCES polls(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(255),
    description TEXT NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    session_token VARCHAR(255) UNIQUE,
    expires_at DATETIME,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_session_token (session_token),
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    subject VARCHAR(255),
    message TEXT,
    is_read TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_is_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (name, email, password, role, status) VALUES
('Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 1),
('Reporter', 'reporter@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'reporter', 1);

INSERT INTO categories (category_name, slug) VALUES
('Politics', 'politics'),
('Sports', 'sports'),
('Entertainment', 'entertainment'),
('Technology', 'technology'),
('Business', 'business'),
('International', 'international'),
('Local News', 'local-news'),
('Health', 'health'),
('Education', 'education'),
('Lifestyle', 'lifestyle'),
('Breaking News', 'breaking-news'),
('Trending', 'trending');

INSERT INTO settings (setting_key, setting_value) VALUES
('website_name', 'News Portal'),
('website_url', 'http://localhost/news-portal'),
('contact_email', 'contact@newsportal.com'),
('contact_phone', '+1 234 567 890'),
('address', '123 News Street, New York, NY 10001'),
('logo', ''),
('favicon', ''),
('facebook_url', 'https://facebook.com'),
('twitter_url', 'https://twitter.com'),
('instagram_url', 'https://instagram.com'),
('youtube_url', 'https://youtube.com'),
('seo_title', 'News Portal - Latest Breaking News & Updates'),
('seo_description', 'Your trusted source for the latest breaking news, trending stories, and in-depth coverage across politics, sports, technology, entertainment and more.'),
('meta_keywords', 'news, breaking news, latest news, world news, politics, sports, technology'),
('google_analytics_id', ''),
('google_adsense_id', ''),
('smtp_host', ''),
('smtp_port', '587'),
('smtp_username', ''),
('smtp_password', ''),
('smtp_encryption', 'tls'),
('maintenance_mode', '0'),
('dark_mode_default', '0'),
('posts_per_page', '12'),
('enable_registration', '1'),
('comment_approval', 'manual'),
('timezone', 'UTC'),
('allow_comments', '1');

INSERT INTO news (category_id, author_id, title, slug, short_description, full_content, featured_image, tags, views, status, is_breaking, featured, allow_comments, reading_time, publish_date) VALUES
(4, 1, 'AI Breakthrough: New Model Achieves Human-Level Reasoning in Scientific Research', 'ai-breakthrough-new-model', 'A groundbreaking artificial intelligence model has achieved human-level reasoning capabilities in scientific research, promising to accelerate discoveries in medicine, physics, and climate science.', '<p>Researchers at a leading AI laboratory have unveiled a new model that demonstrates unprecedented reasoning abilities in complex scientific domains. The system, trained on millions of research papers and experimental data, can formulate hypotheses, design experiments, and interpret results with accuracy matching expert human scientists.</p><p>The breakthrough represents a significant leap forward in artificial general intelligence research. Unlike previous models that excelled at pattern recognition but struggled with abstract reasoning, this new architecture integrates causal reasoning and multi-step logical deduction.</p><p>Early applications have already shown promise in drug discovery, where the model identified potential compounds for treating antibiotic-resistant bacteria. Climate scientists are using it to model complex environmental interactions that were previously too computationally expensive to simulate.</p>', '/uploads/ai-breakthrough.jpg', 'AI,technology,science,research', 1542, 'published', 1, 1, 1, 4, '2026-05-15 08:30:00'),
(1, 2, 'Senate Passes Historic Climate Bill with Bipartisan Support', 'senate-passes-climate-bill', 'The U.S. Senate voted 72-28 to pass the most comprehensive climate legislation in decades, including massive investments in renewable energy, carbon capture technology, and electric vehicle infrastructure.', '<p>In a rare display of bipartisan cooperation, the Senate passed the Climate Action and Energy Security Act with overwhelming support from both sides of the aisle. The legislation commits $500 billion over the next decade to transition the nation toward clean energy sources.</p><p>Key provisions include tax incentives for solar and wind energy adoption, federal funding for a nationwide network of electric vehicle charging stations, and grants for communities affected by the transition away from fossil fuels.</p><p>Environmental groups have praised the bill as a critical step toward meeting the country''s Paris Agreement commitments, while industry leaders have welcomed the predictable regulatory framework it provides for long-term investment planning.</p>', '/uploads/climate-bill.jpg', 'politics,climate,energy,environment', 2341, 'published', 1, 1, 1, 5, '2026-05-16 14:00:00'),
(2, 2, 'Champions League Final: Underdogs Clinch Historic Victory in Extra Time', 'champions-league-final-victory', 'In one of the greatest upsets in tournament history, the underdogs secured a dramatic 3-2 victory in extra time, capturing their first ever Champions League title.', '<p>The final, played before a sold-out stadium of 75,000 fans, delivered on every promise of drama and excitement. The underdogs took an early lead in the 12th minute through a stunning long-range strike, but the favorites responded with two goals before halftime.</p><p>Trailing 2-1 at the break, the underdogs emerged with renewed determination in the second half. Their equalizer came in the 67th minute, a controversial penalty that was confirmed after a lengthy VAR review. The match remained level through regulation time.</p><p>Extra time belonged to the underdogs. In the 108th minute, a lightning counter-attack was finished with composure to send their fans into delirium. The final whistle sparked emotional scenes as players and fans celebrated a triumph that defied all expectations and betting odds.</p>', '/uploads/champions-league.jpg', 'sports,football,champions-league', 5678, 'published', 0, 1, 1, 6, '2026-05-14 21:00:00'),
(4, 1, 'Quantum Computing Milestone: First Error-Corrected Logical Qubit Demonstrated', 'quantum-computing-error-corrected-qubit', 'Scientists have successfully demonstrated the first fully error-corrected logical qubit, removing a major obstacle to building practical quantum computers capable of solving real-world problems.', '<p>A team of physicists and engineers has achieved a long-sought goal in quantum computing: a logical qubit that can perform calculations with error rates low enough for practical applications. The demonstration, published in Nature, shows that quantum error correction can work at scale.</p><p>Previous quantum computers were limited by extreme fragility of quantum states, with errors accumulating rapidly during calculations. The new approach uses a lattice of physical qubits to encode a single logical qubit that automatically detects and corrects errors without disturbing the computation.</p><p>Industry experts compare this breakthrough to the invention of error correction in classical computing, which paved the way for reliable digital computers. The achievement could accelerate timelines for quantum advantage in fields such as cryptography, materials science, and pharmaceutical research.</p>', '/uploads/quantum-computing.jpg', 'quantum,computing,technology,science', 892, 'published', 0, 0, 1, 5, '2026-05-13 10:15:00'),
(5, 2, 'Global Markets Rally as Central Banks Signal Coordinated Rate Cuts', 'global-markets-rally-rate-cuts', 'Stock markets surged worldwide after major central banks signaled coordinated interest rate cuts to combat slowing economic growth, with the Dow Jones Industrial Average rising over 600 points.', '<p>Investors reacted with enthusiasm as the Federal Reserve, European Central Bank, and Bank of Japan issued a joint statement indicating they are prepared to ease monetary policy simultaneously. The coordinated approach aims to prevent a global economic slowdown from deepening into a recession.</p><p>The Dow Jones Industrial Average climbed 2.3%, while European and Asian markets posted similar gains. Technology and financial sectors led the rally, with bank stocks benefiting from expectations of increased lending activity.</p><p>Economists remain divided on whether the rate cuts will be sufficient to stimulate growth. Some argue that structural issues, including supply chain disruptions and geopolitical tensions, require policy responses beyond monetary easing. The central banks have indicated they stand ready to take additional measures if necessary.</p>', '/uploads/markets-rally.jpg', 'markets,economy,business,finance', 1245, 'published', 0, 1, 1, 4, '2026-05-12 09:45:00');

INSERT INTO advertisements (title, type, image, link, position, width, height, status) VALUES
('TechCorp Banner Ad', 'banner', '/uploads/ads/techcorp-banner.jpg', 'https://techcorp.example.com', 'header', 728, 90, 1),
('Local Business Sidebar', 'sidebar', '/uploads/ads/local-business.jpg', 'https://localbusiness.example.com', 'sidebar', 300, 250, 1),
('Summer Sale Popup', 'popup', '/uploads/ads/summer-sale.jpg', 'https://summersale.example.com', 'popup', 400, 400, 1);

INSERT INTO comments (news_id, user_id, parent_id, comment, status, ip_address) VALUES
(1, 1, NULL, 'This is truly remarkable progress in AI research. The implications for scientific discovery are enormous.', 'approved', '192.168.1.1'),
(1, 2, NULL, 'I am cautiously optimistic. We need to ensure proper ethical guidelines are in place.', 'approved', '192.168.1.2'),
(2, NULL, NULL, 'Finally some good news on climate policy. Long overdue.', 'approved', '10.0.0.1'),
(2, 1, 3, 'Agreed, but we need to see if the funding actually gets delivered as promised.', 'pending', '192.168.1.1'),
(3, NULL, NULL, 'What a match! I cannot believe they actually pulled it off. History made!', 'approved', '172.16.0.1');