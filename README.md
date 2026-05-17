# Advanced News Portal CMS

A professional, fully-featured News Website and Admin Panel built with Core PHP and MySQL.

## Features

- Modern responsive design with Bootstrap 5
- Dark mode support
- Breaking news ticker
- Full admin dashboard
- Role-based access control
- News management with rich content
- Category & tag system
- Comment system with moderation
- Advertisement management
- Newsletter subscription
- SEO friendly URLs & meta tags
- REST API endpoints
- Analytics & activity logging
- Secure authentication with password hashing

## Installation

1. Clone or download the project into your web server directory (htdocs/www)
2. Create MySQL database named `news_portal`
3. Import `database/news_portal.sql`
4. Update database credentials in `config/database.php`
5. Open `http://localhost/news-portal` in your browser

## Admin Access

- URL: `http://localhost/news-portal/admin/login.php`
- Default credentials:
  - Email: `admin@example.com`
  - Password: `admin123`

## Project Structure

```
news-portal/
├── admin/                  # Admin panel
├── api/                    # REST API endpoints
├── assets/                 # CSS, JS, uploads
├── config/                 # Database & functions
├── database/               # SQL file
├── includes/               # Header, footer, sidebar, auth
├── admin/
├── index.php
├── news-details.php
├── category.php
├── search.php
├── login.php
├── register.php
└── .htaccess
```

## Security

- PDO prepared statements
- CSRF protection
- Password hashing (bcrypt)
- Input sanitization
- XSS & SQL injection protection

## License

Educational and commercial use permitted.

---

**Production-ready News Portal CMS** built following modern PHP practices.