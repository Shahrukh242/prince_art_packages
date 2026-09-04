-- PAP Dashboard — Remove Duplicate Nav Links & CTA Buttons
-- Run this in phpMyAdmin SQL tab to remove any duplicate rows

-- 1. Remove duplicate navigation links (keeps the first occurrence)
DELETE n1 FROM nav_links n1
INNER JOIN nav_links n2 
WHERE n1.id > n2.id AND LOWER(TRIM(n1.label)) = LOWER(TRIM(n2.label));

-- 2. Remove duplicate CTA buttons (keeps the first occurrence)
DELETE c1 FROM cta_buttons c1
INNER JOIN cta_buttons c2 
WHERE c1.id > c2.id AND c1.page_id = c2.page_id AND c1.placement = c2.placement AND LOWER(TRIM(c1.label)) = LOWER(TRIM(c2.label));
