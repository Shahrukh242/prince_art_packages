-- Run once on existing deployments after taking a database backup.
-- These indexes match the public/admin queries and avoid table scans as data grows.

ALTER TABLE products
  ADD INDEX idx_products_published_category_name (is_published, category, name);

ALTER TABLE blog_posts
  ADD INDEX idx_blog_published_date (is_published, published_at);

ALTER TABLE leads
  ADD INDEX idx_leads_status_created (status, created_at);
