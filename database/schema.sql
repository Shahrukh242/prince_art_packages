-- Prince Art Packages — Custom Dashboard Database Schema
-- Run this once in phpMyAdmin (or `mysql -u user -p dbname < schema.sql`)

CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','editor') NOT NULL DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- One row per page, holds SEO metadata for that page
CREATE TABLE IF NOT EXISTS pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,       -- e.g. 'home', 'about', 'quality-compliance'
    title VARCHAR(150) NOT NULL,
    meta_title VARCHAR(160),
    meta_description VARCHAR(300),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Editable content blocks within a page (hero title, hero subtitle, section body, etc.)
CREATE TABLE IF NOT EXISTS content_blocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_id INT NOT NULL,
    block_key VARCHAR(100) NOT NULL,          -- e.g. 'hero_title', 'hero_subtitle', 'trust_bar_1'
    block_type ENUM('text','richtext','image') NOT NULL DEFAULT 'text',
    content TEXT,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
    UNIQUE KEY unique_block_per_page (page_id, block_key)
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    category VARCHAR(100),                    -- e.g. 'Folding Cartons', 'Cold-Seal Packaging'
    short_description VARCHAR(300),
    description TEXT,
    spec_sheet TEXT,                          -- can store simple JSON: {"Material":"...","MOQ":"..."}
    image_path VARCHAR(255),
    meta_title VARCHAR(160),
    meta_description VARCHAR(300),
    is_published TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS case_studies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    summary VARCHAR(300),
    content TEXT,
    image_path VARCHAR(255),
    is_published TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    excerpt VARCHAR(300),
    content TEXT,
    image_path VARCHAR(255),
    meta_title VARCHAR(160),
    meta_description VARCHAR(300),
    is_published TINYINT(1) DEFAULT 0,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- RFQ / quote request leads from the contact form
CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    company VARCHAR(150),
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(50),
    product_type VARCHAR(150),
    quantity VARCHAR(100),
    message TEXT,
    status ENUM('new','contacted','quoted','won','lost') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Global site settings (GA4 ID, Search Console tag, etc.)
CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT
);

-- Seed pages (one row per public page — extend as needed)
INSERT INTO pages (slug, title, meta_title, meta_description) VALUES
('home', 'Home', 'Prince Art Packages — Pharmaceutical Secondary Packaging Manufacturer', 'ISO 9001:2015 & FSC certified pharmaceutical secondary packaging manufacturer in Karachi, Pakistan.'),
('about', 'About Us', 'About Prince Art Packages', 'Learn about our history, facilities, and commitment to pharmaceutical packaging compliance.'),
('quality-compliance', 'Quality & Compliance', 'Quality & Compliance | Prince Art Packages', 'ISO 9001:2015 and FSC certified. Learn about our cGMP-compliant quality assurance process.'),
('innovation', 'Innovation & Technology', 'Innovation | Prince Art Packages', 'ColdSeal Blister Wallet and 3D-Engravix anti-counterfeit technology.'),
('contact', 'Contact / Request a Quote', 'Contact Us | Prince Art Packages', 'Get in touch for a pharmaceutical packaging quote.')
ON DUPLICATE KEY UPDATE slug = slug;

-- No admin user is created by this script on purpose — run setup.php (in the project root)
-- once after import to create your real admin account with a properly hashed password.
-- Do NOT hand-write a password hash into SQL; always let PHP's password_hash() generate it.
