<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/cta_buttons_manifest.php';

$pdo = get_db();
$all = get_all_default_cta_buttons();

// Ensure global header page exists
$pdo->prepare("INSERT INTO pages (slug, title, meta_title, meta_description) VALUES ('global', 'Global Header & Site-Wide', '', '') ON DUPLICATE KEY UPDATE title = VALUES(title)")->execute();

$count = 0;
foreach (array_keys($all) as $slug) {
    $count += restore_page_default_cta_buttons($pdo, $slug);
}

echo "Successfully seeded and synchronized {$count} CTA buttons across all pages!\n";
