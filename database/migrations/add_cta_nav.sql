-- PAP Dashboard — Migration: CTA Buttons & Navigation Links
-- Run this in phpMyAdmin or: mysql -u user -p pap_dashboard < add_cta_nav.sql
-- Safe to run multiple times (uses IF NOT EXISTS / ON DUPLICATE KEY)

-- -----------------------------------------------------------------------
-- Table: cta_buttons
-- One row per CTA button. Tied to a page and a named placement zone.
-- -----------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cta_buttons (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    page_id     INT NOT NULL,
    label       VARCHAR(150)  NOT NULL,
    url         VARCHAR(500)  NOT NULL,
    style       ENUM('btn-gold','btn-teal','btn-navy','btn-outline-navy') NOT NULL DEFAULT 'btn-gold',
    icon        VARCHAR(100)  DEFAULT NULL,   -- remixicon class, e.g. ri-file-list-3-line
    placement   VARCHAR(100)  NOT NULL,       -- e.g. 'hero', 'after_capabilities', 'footer_cta'
    sort_order  INT           NOT NULL DEFAULT 0,
    is_active   TINYINT(1)    NOT NULL DEFAULT 1,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
    INDEX idx_cta_page_placement (page_id, placement)
);

-- Seed: default hero CTA buttons for the home page (page_id = 1 → slug 'home')
-- Uses a sub-select so it works regardless of actual auto-increment IDs.
INSERT INTO cta_buttons (page_id, label, url, style, icon, placement, sort_order)
SELECT p.id, 'Request a Formal Quote', 'contact.php', 'btn-gold', 'ri-file-list-3-line', 'hero', 1
FROM pages p WHERE p.slug = 'home'
ON DUPLICATE KEY UPDATE label = label;

INSERT INTO cta_buttons (page_id, label, url, style, icon, placement, sort_order)
SELECT p.id, 'Explore 3D-Engravix™ & ColdSeal', 'innovation.php', 'btn-teal', 'ri-shield-keyhole-line', 'hero', 2
FROM pages p WHERE p.slug = 'home'
ON DUPLICATE KEY UPDATE label = label;

-- -----------------------------------------------------------------------
-- Table: nav_links
-- Ordered navigation items rendered in the public header.
-- -----------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS nav_links (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    label        VARCHAR(100) NOT NULL,
    url          VARCHAR(500) NOT NULL,
    sort_order   INT          NOT NULL DEFAULT 0,
    is_active    TINYINT(1)   NOT NULL DEFAULT 1,
    open_new_tab TINYINT(1)   NOT NULL DEFAULT 0
);

-- Seed: mirror the current hardcoded nav links
INSERT INTO nav_links (label, url, sort_order) VALUES
('Home',         'index.php',        10),
('About',        'about.php',        20),
('Products',     'products.php',     30),
('Capabilities', 'capabilities.php', 40),
('Innovation',   'innovation.php',   50),
('Quality',      'quality.php',      60),
('Industries',   'industries.php',   70),
('Blog',         'blog.php',         80),
('Contact',      'contact.php',      90)
ON DUPLICATE KEY UPDATE label = label;
