<?php
// includes/functions.php — shared helpers used by every public page.

require_once __DIR__ . '/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']),
    ]);
    session_start();
}

/**
 * Get a single content block's text for a page, by its block_key.
 * Usage in a template: echo get_content('home', 'hero_title');
 */
function get_content(string $pageSlug, string $blockKey, string $fallback = ''): string {
    $pdo = get_db();
    $stmt = $pdo->prepare(
        "SELECT cb.content
         FROM content_blocks cb
         JOIN pages p ON p.id = cb.page_id
         WHERE p.slug = ? AND cb.block_key = ?
         LIMIT 1"
    );
    $stmt->execute([$pageSlug, $blockKey]);
    $row = $stmt->fetch();
    return $row ? $row['content'] : $fallback;
}

/**
 * Get a page's SEO metadata (title tag + meta description) to output in <head>.
 */
function get_page_meta(string $pageSlug): array {
    $pdo = get_db();
    $stmt = $pdo->prepare("SELECT title, meta_title, meta_description FROM pages WHERE slug = ? LIMIT 1");
    $stmt->execute([$pageSlug]);
    $row = $stmt->fetch();
    return $row ?: ['title' => 'Prince Art Packages', 'meta_title' => 'Prince Art Packages', 'meta_description' => ''];
}

/**
 * Get all published products, optionally filtered by category.
 */
function get_products(?string $category = null): array {
    $pdo = get_db();
    if ($category) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE is_published = 1 AND category = ? ORDER BY name ASC");
        $stmt->execute([$category]);
    } else {
        $stmt = $pdo->query("SELECT * FROM products WHERE is_published = 1 ORDER BY category, name ASC");
    }
    return $stmt->fetchAll();
}

function get_product_by_slug(string $slug): ?array {
    $pdo = get_db();
    $stmt = $pdo->prepare("SELECT * FROM products WHERE slug = ? AND is_published = 1 LIMIT 1");
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ?: null;
}

/** Escape output safely for HTML context — use this around every echoed value. */
function h(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Generate and check CSRF tokens for any form that writes data (RFQ form, admin forms). */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_verify(?string $token): bool {
    return isset($_SESSION['csrf_token']) && $token !== null && hash_equals($_SESSION['csrf_token'], $token);
}
