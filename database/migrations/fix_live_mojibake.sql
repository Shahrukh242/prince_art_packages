-- PAP Dashboard — Migration: Fix Mojibake characters in live database
-- Run this in phpMyAdmin SQL tab to fix any corrupted characters in your live database

-- Fix in content_blocks
UPDATE content_blocks SET content = REPLACE(content, 'ΓÇó', '•') WHERE content LIKE '%ΓÇó%';
UPDATE content_blocks SET content = REPLACE(content, 'ΓÇô', '–') WHERE content LIKE '%ΓÇô%';
UPDATE content_blocks SET content = REPLACE(content, 'ΓÇö', '—') WHERE content LIKE '%ΓÇö%';
UPDATE content_blocks SET content = REPLACE(content, 'Γäó', '™') WHERE content LIKE '%Γäó%';
UPDATE content_blocks SET content = REPLACE(content, 'ΓÇÖ', '’') WHERE content LIKE '%ΓÇÖ%';
UPDATE content_blocks SET content = REPLACE(content, 'ΓÇ£', '“') WHERE content LIKE '%ΓÇ£%';
UPDATE content_blocks SET content = REPLACE(content, 'ΓÇ¥', '”') WHERE content LIKE '%ΓÇ¥%';
UPDATE content_blocks SET content = REPLACE(content, '┬░', '°') WHERE content LIKE '%┬░%';

UPDATE content_blocks SET content = REPLACE(content, 'гÇó', '•') WHERE content LIKE '%гÇó%';
UPDATE content_blocks SET content = REPLACE(content, 'гÇô', '–') WHERE content LIKE '%гÇô%';
UPDATE content_blocks SET content = REPLACE(content, 'гÇö', '—') WHERE content LIKE '%гÇö%';
UPDATE content_blocks SET content = REPLACE(content, 'гäó', '™') WHERE content LIKE '%гäó%';

-- Fix in blog_posts
UPDATE blog_posts SET title = REPLACE(title, 'ΓÇó', '•') WHERE title LIKE '%ΓÇó%';
UPDATE blog_posts SET title = REPLACE(title, 'ΓÇô', '–') WHERE title LIKE '%ΓÇô%';
UPDATE blog_posts SET title = REPLACE(title, 'ΓÇö', '—') WHERE title LIKE '%ΓÇö%';
UPDATE blog_posts SET title = REPLACE(title, 'Γäó', '™') WHERE title LIKE '%Γäó%';

UPDATE blog_posts SET excerpt = REPLACE(excerpt, 'ΓÇó', '•') WHERE excerpt LIKE '%ΓÇó%';
UPDATE blog_posts SET excerpt = REPLACE(excerpt, 'ΓÇô', '–') WHERE excerpt LIKE '%ΓÇô%';
UPDATE blog_posts SET excerpt = REPLACE(excerpt, 'ΓÇö', '—') WHERE excerpt LIKE '%ΓÇö%';
UPDATE blog_posts SET excerpt = REPLACE(excerpt, 'Γäó', '™') WHERE excerpt LIKE '%Γäó%';

UPDATE blog_posts SET content = REPLACE(content, 'ΓÇó', '•') WHERE content LIKE '%ΓÇó%';
UPDATE blog_posts SET content = REPLACE(content, 'ΓÇô', '–') WHERE content LIKE '%ΓÇô%';
UPDATE blog_posts SET content = REPLACE(content, 'ΓÇö', '—') WHERE content LIKE '%ΓÇö%';
UPDATE blog_posts SET content = REPLACE(content, 'Γäó', '™') WHERE content LIKE '%Γäó%';
UPDATE blog_posts SET content = REPLACE(content, '┬░', '°') WHERE content LIKE '%┬░%';
