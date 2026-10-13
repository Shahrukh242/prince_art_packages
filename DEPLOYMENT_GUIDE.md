# 🚀 Production Deployment Guide — Prince Art Packages

This repository contains the complete **Prince Art Packages (Private) Limited** enterprise website and custom Admin CMS.

---

## 📋 System Requirements
- **Web Server:** Apache (with `mod_rewrite`) or Nginx / LiteSpeed
- **PHP Version:** PHP 8.0, 8.1, 8.2, or 8.3
- **Database:** MySQL 5.7+ or MariaDB 10.3+
- **PHP Extensions:** `pdo_mysql`, `mbstring`, `json`, `session`, `curl`, `openssl`

---

## ⚡ 4-Step Deployment Instructions

### Step 1: Create Database & Import SQL Dump
1. In your hosting cPanel / phpMyAdmin / MySQL CLI, create a new database (e.g. `pap_production_db`).
2. Create a database user with full privileges.
3. Import the file:
   📁 `database/production_schema_and_data.sql` (or `database/schema.sql`)

---

### Step 2: Configure Database Credentials
Open `includes/db.php` on your live server and enter your live database credentials:

```php
function get_db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $host = 'localhost';          // or database server host
        $db   = 'pap_production_db';  // your database name
        $user = 'pap_user';           // your database username
        $pass = 'your_secure_pass';   // your database password
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, $user, $pass, $options);
    }
    return $pdo;
}
```

---

### Step 3: Upload Project Files
Upload the project files to your live server document root (`public_html`):
- If your domain points to the repository root, the root `index.php` automatically routes visitors to `public_site/`.
- If your web server root points directly inside `public_site/`, upload contents accordingly.

---

### Step 4: Configure Live Production Settings
1. Log in to the Admin Dashboard:  
   🔗 `https://yourdomain.com/admin/login.php`  
   - **Default Admin Username:** `admin`  
   - **Default Admin Password:** `admin123` *(Change this immediately in Settings > User Management)*
2. Navigate to **System Settings > SEO, Sitemaps & AI Crawlers**:
   - Set **Canonical Live Website URL** to your live domain:  
     `https://princeartpackages.com`
   - Click **"Save SEO Settings & Sync Crawler Files"**. This instantly updates `sitemap.xml`, `robots.txt`, and `llms.txt` with your live domain!
3. In **System Settings > SMTP Email Server**, enter your official email SMTP credentials (e.g. Google Workspace / Gmail App Password or cPanel mail) so lead quotation notifications are delivered reliably.

---

## 🛡️ Security & Performance Best Practices Included
- ✅ **Parameterized SQL Queries:** 100% protection against SQL injection.
- ✅ **CSRF Protection:** Secure token verification on every POST request.
- ✅ **XSS Sanitization:** Contextual escaping (`h()`) and safe HTML formatting (`render_content()`).
- ✅ **Authentication:** Bcrypt password hashing (`password_hash` / `password_verify`) and session fixation defense.
- ✅ **Mobile-Friendly Suite:** 100% responsive on smartphones, tablets, and desktops.
- ✅ **SEO & AI Search Ready:** Dynamic XML sitemap, `robots.txt`, and `llms.txt` knowledge file.
