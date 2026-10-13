-- PAP Dashboard — Migration: Seed all real page content into content_blocks
-- Run in phpMyAdmin SQL tab — safe to run multiple times (ON DUPLICATE KEY UPDATE)
-- After running this, every block will appear pre-filled in Admin → Page Content

-- ============================================================
-- HOME PAGE blocks
-- ============================================================
INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'hero_title', 'text',
  'Precision Pharmaceutical Secondary Packaging Engineered for Global Audit Compliance', 10
FROM pages p WHERE p.slug = 'home'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'hero_subtitle', 'text',
  'ISO 9001:2015 certified manufacturer of printed cartons, leaf-inserts, printed labels, ColdSeal blister wallets, and 3D-Engravix™ optical anti-counterfeit security packaging based in Korangi Creek Industrial Park, Karachi.', 20
FROM pages p WHERE p.slug = 'home'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'capability_intro', 'text',
  'Purpose-engineered paperboard packaging, precision inserts, self-adhesive roll labels, and proprietary security features.', 30
FROM pages p WHERE p.slug = 'home'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

-- ============================================================
-- ABOUT PAGE blocks
-- ============================================================
INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'page_eyebrow', 'text', 'Corporate History & Facilities', 10
FROM pages p WHERE p.slug = 'about'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'page_title', 'text', 'Prince Art Packages (Private) Limited', 20
FROM pages p WHERE p.slug = 'about'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'page_intro', 'text',
  'Formerly Prince Art Press, our company has evolved into a premier ISO 9001:2015 and FSC certified manufacturer of pharmaceutical secondary packaging in Pakistan.', 30
FROM pages p WHERE p.slug = 'about'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'section1_heading', 'text', 'Industrial Heritage & Dual Manufacturing Units', 40
FROM pages p WHERE p.slug = 'about'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'section1_body', 'text',
  'Prince Art Packages (Private) Limited operates two modern manufacturing units in Korangi Creek Industrial Park, Karachi. Our facilities are designed specifically to eliminate cross-contamination, artwork mix-ups, and batch discrepancies.', 50
FROM pages p WHERE p.slug = 'about'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'locations_heading', 'text', 'Manufacturing Unit Locations:', 60
FROM pages p WHERE p.slug = 'about'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'unit1_address', 'text', 'WH-17-A8, ST-1, Sector 38, Korangi Creek Industrial Park, Karachi, Pakistan.', 70
FROM pages p WHERE p.slug = 'about'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'unit2_address', 'text', 'Plot 239, Opposite Masco, Main Korangi Creek Road, Karachi, Pakistan.', 80
FROM pages p WHERE p.slug = 'about'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

-- ============================================================
-- CONTACT PAGE blocks
-- ============================================================
INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'intro_text', 'text',
  'Submit your packaging requirements below. Our team responds within one business day with specifications and pricing.', 10
FROM pages p WHERE p.slug = 'contact'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

-- ============================================================
-- INNOVATION PAGE blocks
-- ============================================================
INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'coldseal_intro', 'text',
  'ColdSeal Blister Wallet is a pressure-sealed packaging format that eliminates heat sealing, reducing plastic and foil consumption by up to 50% while maintaining child-resistant and tamper-evident properties.', 10
FROM pages p WHERE p.slug = 'innovation'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'engravix_intro', 'text',
  '3D-Engravix™ is a proprietary optical security feature integrated directly onto printed cartons. Authentication requires no scanner or app — visual inspection reveals micro-optic motion effects, a flip effect, and a colour-shifting seal-base.', 20
FROM pages p WHERE p.slug = 'innovation'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);

-- ============================================================
-- QUALITY PAGE blocks
-- ============================================================
INSERT INTO content_blocks (page_id, block_key, block_type, content, sort_order)
SELECT p.id, 'intro_text', 'text',
  'Our quality assurance system covers incoming material inspection, in-process quality control, and finished goods verification — all documented under ISO 9001:2015 (Cert No. KQ.2025.5393).', 10
FROM pages p WHERE p.slug = 'quality'
ON DUPLICATE KEY UPDATE content = IF(content = '' OR content IS NULL, VALUES(content), content);
