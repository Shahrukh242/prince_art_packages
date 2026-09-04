<?php
// includes/functions.php — shared helpers used by every public page.

require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => !empty($_SERVER['HTTPS']), // only require HTTPS once live with SSL
    ]);
    session_start();
}

// ---------------------------------------------------------------------------
// REQUEST-SCOPED CACHES — eliminates N+1 DB queries per page load.
// ---------------------------------------------------------------------------
$_pac_content_cache  = [];   // keyed by [pageSlug][block_key]
$_pac_settings_cache = null; // null = not loaded yet; [] = loaded (even if empty)
$_pac_nav_cache      = null; // null = not loaded yet
$_pac_cta_cache      = null; // null = not loaded yet

/**
 * Pre-load ALL content blocks for a page slug in one query.
 * Call this once at the top of each page: preload_content('home');
 * Subsequent get_content() calls for the same slug read from the in-memory array.
 */
function preload_content(string $pageSlug): void {
    global $_pac_content_cache;
    if (isset($_pac_content_cache[$pageSlug])) return; // already loaded
    try {
        $pdo = get_db();
        $stmt = $pdo->prepare(
            "SELECT cb.block_key, cb.content
             FROM content_blocks cb
             JOIN pages p ON p.id = cb.page_id
             WHERE p.slug = ?"
        );
        $stmt->execute([$pageSlug]);
        $_pac_content_cache[$pageSlug] = [];
        while ($row = $stmt->fetch()) {
            $_pac_content_cache[$pageSlug][$row['block_key']] = $row['content'];
        }
    } catch (\Throwable $e) {
        // Fail silently — individual get_content() calls will fall back to single queries
        $_pac_content_cache[$pageSlug] = [];
    }
}

/**
 * Pre-load ALL settings in one query.
 * Call this once at start of each request: preload_settings();
 * Subsequent get_setting() calls read from the in-memory array.
 */
function preload_settings(): void {
    global $_pac_settings_cache;
    if ($_pac_settings_cache !== null) return; // already loaded
    try {
        $pdo = get_db();
        $rows = $pdo->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
        $_pac_settings_cache = [];
        foreach ($rows as $row) {
            $_pac_settings_cache[$row['setting_key']] = $row['setting_value'];
        }
    } catch (\Throwable $e) {
        // Fail silently — individual get_setting() calls will fall back to single queries
        $_pac_settings_cache = [];
    }
}

/**
 * Get a single content block's text for a page, by its block_key.
 * Usage in a template: echo get_content('home', 'hero_title');
 *
 * Automatically preloads all content blocks for the requested page slug
 * into PHP memory on the very first call, completely eliminating N+1 queries.
 */
function get_content(string $pageSlug, string $blockKey, string $fallback = ''): string {
    global $_pac_content_cache;
    if (!isset($_pac_content_cache[$pageSlug])) {
        preload_content($pageSlug);
    }
    $val = $_pac_content_cache[$pageSlug][$blockKey] ?? null;
    return ($val !== null && $val !== '') ? $val : $fallback;
}

/**
 * Get a page's SEO metadata (title tag + meta description) to output in <head>.
 * Memoized per request in PHP memory.
 */
function get_page_meta(string $pageSlug): array {
    static $metaCache = [];
    if (isset($metaCache[$pageSlug])) {
        return $metaCache[$pageSlug];
    }
    try {
        $pdo = get_db();
        $stmt = $pdo->prepare("SELECT title, meta_title, meta_description FROM pages WHERE slug = ? LIMIT 1");
        $stmt->execute([$pageSlug]);
        $row = $stmt->fetch();
        $metaCache[$pageSlug] = $row ?: ['title' => 'Prince Art Packages', 'meta_title' => 'Prince Art Packages', 'meta_description' => ''];
        return $metaCache[$pageSlug];
    } catch (\Throwable $e) {
        return ['title' => 'Prince Art Packages', 'meta_title' => 'Prince Art Packages', 'meta_description' => ''];
    }
}

/**
 * Get all published products, optionally filtered by category.
 */
function get_products(?string $category = null): array {
    $pdo = get_db();
    $cols = 'id, name, slug, category, short_description, description, image_path, image_path AS image_url, is_published, created_at';
    if ($category) {
        $stmt = $pdo->prepare("SELECT {$cols} FROM products WHERE is_published = 1 AND category = ? ORDER BY name ASC");
        $stmt->execute([$category]);
    } else {
        $stmt = $pdo->query("SELECT {$cols} FROM products WHERE is_published = 1 ORDER BY category, name ASC");
    }
    return $stmt->fetchAll();
}

function get_product_by_slug(string $slug): ?array {
    $pdo = get_db();
    $cols = 'id, name, slug, category, short_description, description, image_path, image_path AS image_url, is_published, created_at';
    $stmt = $pdo->prepare(
        "SELECT {$cols}
         FROM products WHERE slug = ? AND is_published = 1 LIMIT 1"
    );
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function get_all_media(): array {
    $pdo = get_db();
    return $pdo->query(
        "SELECT id, filename, filepath, mime_type, uploaded_at FROM media ORDER BY uploaded_at DESC"
    )->fetchAll();
}

function get_media_by_id(?int $id): ?array {
    if (!$id) return null;
    $pdo = get_db();
    $stmt = $pdo->prepare(
        "SELECT id, filename, filepath, mime_type, uploaded_at FROM media WHERE id = ? LIMIT 1"
    );
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

/** Escape output safely for HTML context — use this around every echoed value. */
function h(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Render text with full support for inline page links (<a href="...">, [text](url)),
 * bold (<strong>, <b>), italics (<em>, <i>), and safe HTML tags.
 */
function render_content(?string $value): string {
    if ($value === null || $value === '') return '';
    
    // Support markdown links [Text](URL)
    $value = preg_replace_callback('/\[([^\]]+)\]\(([^)]+)\)/', function($m) {
        $text = htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8');
        $url  = htmlspecialchars($m[2], ENT_QUOTES, 'UTF-8');
        return '<a href="' . $url . '" class="text-link">' . $text . '</a>';
    }, $value);

    // If string contains HTML tags, sanitize and ensure text-link class on anchors without classes
    if (strpos($value, '<') !== false) {
        $value = preg_replace('/<a\s+(?!.*?class=)(href="[^"]*")/i', '<a class="text-link" $1', $value);
        return strip_tags($value, '<a><strong><b><em><i><u><span><br><p><code><small><ul><ol><li><h3><h4>');
    }

    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
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

/**
 * Preload all active CTA buttons in one query to eliminate N+1 queries.
 */
function preload_cta_buttons(): void {
    global $_pac_cta_cache;
    if ($_pac_cta_cache !== null) return;
    $_pac_cta_cache = [];
    try {
        $pdo = get_db();
        $rows = $pdo->query(
            "SELECT cb.*, p.slug AS page_slug
             FROM cta_buttons cb
             JOIN pages p ON p.id = cb.page_id
             WHERE cb.is_active = 1
             ORDER BY cb.sort_order ASC"
        )->fetchAll();
        foreach ($rows as $row) {
            $key = $row['page_slug'] . '|' . $row['placement'];
            if (!isset($_pac_cta_cache[$key])) {
                $_pac_cta_cache[$key] = [];
            }
            $_pac_cta_cache[$key][] = $row;
        }
    } catch (\Throwable $e) {
        // Fall back gracefully if table or relation doesn't exist
    }
}

/**
 * Get all active CTA buttons for a page + placement zone.
 * Falls back gracefully to an empty array if the table doesn't exist yet.
 *
 * Usage: get_cta_buttons('home', 'hero')
 */
function get_cta_buttons(string $pageSlug, string $placement): array {
    global $_pac_cta_cache;
    if ($_pac_cta_cache === null) {
        preload_cta_buttons();
    }
    $cacheKey = $pageSlug . '|' . $placement;
    return $_pac_cta_cache[$cacheKey] ?? [];
}

/**
 * Render HTML for CTA buttons with safe fallback.
 */
function render_cta_buttons(string $pageSlug, string $placement, string $fallbackHtml = ''): string {
    $buttons = get_cta_buttons($pageSlug, $placement);
    if (empty($buttons)) {
        return $fallbackHtml;
    }
    $html = '';
    foreach ($buttons as $btn) {
        $icon = !empty($btn['icon']) ? '<i class="' . h($btn['icon']) . '"></i> ' : '';
        $style = !empty($btn['style']) ? h($btn['style']) : 'btn-gold';
        $actionType = $btn['action_type'] ?? 'link';
        $sourceBtnLabel = h($btn['label']) . ' (' . ucfirst($pageSlug) . ' — ' . ucfirst($placement) . ')';

        if ($actionType === 'popup') {
            $html .= '<button type="button" class="btn ' . $style . ' open-rfq-modal" data-source-button="' . h($sourceBtnLabel) . '" style="cursor:pointer;">' . $icon . h($btn['label']) . '</button>';
        } else {
            $html .= '<a href="' . h($btn['url']) . '" class="btn ' . $style . '">' . $icon . h($btn['label']) . '</a>';
        }
    }
    return $html;
}

/**
 * Get all active navigation links ordered by sort_order.
 * Falls back to an empty array if the nav_links table doesn't exist yet.
 */
function get_nav_links(): array {
    global $_pac_nav_cache;
    if ($_pac_nav_cache !== null) {
        return $_pac_nav_cache;
    }
    try {
        $pdo = get_db();
        $_pac_nav_cache = $pdo->query(
            "SELECT * FROM nav_links WHERE is_active = 1 ORDER BY sort_order ASC"
        )->fetchAll();
        return $_pac_nav_cache;
    } catch (\Throwable $e) {
        return [];
    }
}

/**
 * Render an optimized responsive image tag with WebP source, width, height, and lazy/eager loading.
 */
function render_image(string $src, string $alt = '', string $class = '', array $attrs = []): string {
    $cleanSrc = ltrim($src, '/');
    $webpSrc = preg_replace('/\.(jpe?g|png)$/i', '.webp', $cleanSrc);
    $loading = $attrs['loading'] ?? 'lazy';
    $decoding = $attrs['decoding'] ?? 'async';
    $width = $attrs['width'] ?? 1376;
    $height = $attrs['height'] ?? 768;
    
    $extra = '';
    if (!empty($attrs['fetchpriority'])) {
        $extra .= ' fetchpriority="' . h($attrs['fetchpriority']) . '"';
    }
    if (!empty($attrs['style'])) {
        $extra .= ' style="' . h($attrs['style']) . '"';
    }
    if (!empty($attrs['id'])) {
        $extra .= ' id="' . h($attrs['id']) . '"';
    }

    $docRoot = dirname(__DIR__) . '/public_site/';
    if (file_exists($docRoot . $webpSrc)) {
        return '<picture>' .
               '<source srcset="' . h($webpSrc) . '" type="image/webp">' .
               '<img src="' . h($src) . '" alt="' . h($alt) . '"' . ($class ? ' class="' . h($class) . '"' : '') . ' width="' . h((string)$width) . '" height="' . h((string)$height) . '" loading="' . h($loading) . '" decoding="' . h($decoding) . '"' . $extra . '>' .
               '</picture>';
    }

    return '<img src="' . h($src) . '" alt="' . h($alt) . '"' . ($class ? ' class="' . h($class) . '"' : '') . ' width="' . h((string)$width) . '" height="' . h((string)$height) . '" loading="' . h($loading) . '" decoding="' . h($decoding) . '"' . $extra . '>';
}

/**
 * Get a global setting value by its setting_key.
 * Memoized via preload_settings() to avoid single queries.
 */
function get_setting(string $key, string $default = ''): string {
    global $_pac_settings_cache;
    if ($_pac_settings_cache === null) {
        preload_settings();
    }
    if (isset($_pac_settings_cache[$key]) && $_pac_settings_cache[$key] !== null && $_pac_settings_cache[$key] !== '') {
        return $_pac_settings_cache[$key];
    }
    return $default;
}

/**
 * Get canonical base site URL for sitemaps, robots, and canonical tags.
 */
function get_site_base_url(): string {
    $configured = get_setting('site_base_url', '');
    if (!empty(trim($configured))) {
        return rtrim(trim($configured), '/');
    }
    $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    if (strpos($scriptDir, 'public_site') !== false || strpos($scriptDir, 'admin') !== false) {
        $parent = dirname($scriptDir);
        return $proto . '://' . $host . rtrim($parent, '/') . '/public_site';
    }
    return $proto . '://' . $host;
}

/**
 * Generate standard XML Sitemap dynamically from database pages, products, and blogs.
 */
function generate_sitemap_xml(): string {
    $pdo = get_db();
    $baseUrl = get_site_base_url();
    $now = date('Y-m-d');

    $corePages = [
        ['url' => '', 'freq' => 'weekly', 'prio' => '1.0'],
        ['url' => 'about', 'freq' => 'monthly', 'prio' => '0.8'],
        ['url' => 'products', 'freq' => 'weekly', 'prio' => '0.9'],
        ['url' => 'quality', 'freq' => 'monthly', 'prio' => '0.9'],
        ['url' => 'capabilities', 'freq' => 'monthly', 'prio' => '0.8'],
        ['url' => 'innovation', 'freq' => 'monthly', 'prio' => '0.8'],
        ['url' => 'industries', 'freq' => 'monthly', 'prio' => '0.7'],
        ['url' => 'case-studies', 'freq' => 'monthly', 'prio' => '0.7'],
        ['url' => 'blog', 'freq' => 'daily', 'prio' => '0.8'],
        ['url' => 'contact', 'freq' => 'monthly', 'prio' => '0.8'],
        ['url' => 'privacy', 'freq' => 'yearly', 'prio' => '0.3'],
        ['url' => 'terms', 'freq' => 'yearly', 'prio' => '0.3'],
    ];

    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    foreach ($corePages as $cp) {
        $loc = empty($cp['url']) ? $baseUrl . '/' : $baseUrl . '/' . $cp['url'];
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>\n";
        $xml .= "    <lastmod>{$now}</lastmod>\n";
        $xml .= "    <changefreq>{$cp['freq']}</changefreq>\n";
        $xml .= "    <priority>{$cp['prio']}</priority>\n";
        $xml .= "  </url>\n";
    }

    // Products — Pretty Canonical URLs
    try {
        $products = $pdo->query("SELECT slug, created_at FROM products WHERE is_published = 1 ORDER BY id ASC")->fetchAll();
        foreach ($products as $pr) {
            $loc = $baseUrl . '/product/' . urlencode($pr['slug']);
            $mod = !empty($pr['created_at']) ? date('Y-m-d', strtotime($pr['created_at'])) : $now;
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= "    <lastmod>{$mod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.85</priority>\n";
            $xml .= "  </url>\n";
        }
    } catch (\Throwable $e) {}

    // Blog Posts — Pretty Canonical URLs
    try {
        $posts = $pdo->query("SELECT slug, published_at FROM blog_posts WHERE is_published = 1 ORDER BY published_at DESC")->fetchAll();
        foreach ($posts as $po) {
            $loc = $baseUrl . '/blog/' . urlencode($po['slug']);
            $mod = !empty($po['published_at']) ? date('Y-m-d', strtotime($po['published_at'])) : $now;
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>\n";
            $xml .= "    <lastmod>{$mod}</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.75</priority>\n";
            $xml .= "  </url>\n";
        }
    } catch (\Throwable $e) {}

    // Custom URLs
    $customUrls = get_setting('custom_sitemap_urls', '');
    if (!empty($customUrls)) {
        $lines = preg_split('/[\r\n]+/', trim($customUrls));
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $loc = (strpos($line, 'http://') === 0 || strpos($line, 'https://') === 0) ? $line : $baseUrl . '/' . ltrim($line, '/');
                $xml .= "  <url>\n";
                $xml .= "    <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>\n";
                $xml .= "    <lastmod>{$now}</lastmod>\n";
                $xml .= "    <changefreq>monthly</changefreq>\n";
                $xml .= "    <priority>0.7</priority>\n";
                $xml .= "  </url>\n";
            }
        }
    }

    $xml .= "</urlset>";
    return $xml;
}

/**
 * Generate robots.txt content with search crawler rules.
 */
function generate_robots_txt(): string {
    $custom = get_setting('robots_txt_content', '');
    if (!empty(trim($custom))) {
        return $custom;
    }
    $baseUrl = get_site_base_url();
    return "User-agent: *\n"
         . "Allow: /\n"
         . "Disallow: /admin/\n"
         . "Disallow: /includes/\n"
         . "Disallow: /scratch/\n"
         . "Disallow: /database/\n\n"
         . "Sitemap: {$baseUrl}/sitemap.xml\n";
}

/**
 * Generate llms.txt standard structured Markdown for AI agents and LLM search crawlers.
 */
function generate_llms_txt(): string {
    $custom = get_setting('llms_txt_content', '');
    if (!empty(trim($custom))) {
        return $custom;
    }
    $baseUrl = get_site_base_url();
    return "# Prince Art Packages (Private) Limited — LLMs Knowledge File\n\n"
         . "> Specialized ISO 9001:2015 & cGMP-compliant pharmaceutical secondary packaging manufacturer in Karachi, Pakistan.\n\n"
         . "## Core Capabilities & Manufacturing Infrastructure\n"
         . "- Operating two dedicated manufacturing units in Korangi Creek Industrial Park, Karachi.\n"
         . "- Certifications: ISO 9001:2015 (Cert No. KQ.2025.5393), FSC Chain of Custody (RR-COC-003348 / FSC-C222205), cGMP & WHO-GMP compliant line clearance.\n"
         . "- Primary Products: Folding & Printed Cartons, High-Density Prescribing Information Leaflets (PIL) & Outserts, Small-Diameter Vial & Ampoule Printed Labels, Glass Ampoule Honeycomb Separators, Dose Adherence Pill-Folders, Tamper-Evident Security Cartons, 3D-ENGRAVIX™ Optical Security Packaging, and ColdSeal Blister Wallets.\n\n"
         . "## Key Website Links\n"
         . "- Main Website: {$baseUrl}/\n"
         . "- Secondary Packaging Products: {$baseUrl}/products.php\n"
         . "- Quality & cGMP Assurance: {$baseUrl}/quality.php\n"
         . "- Plant Capabilities: {$baseUrl}/capabilities.php\n"
         . "- Anti-Counterfeit Innovations: {$baseUrl}/innovation.php\n"
         . "- Technical Articles: {$baseUrl}/blog.php\n"
         . "- Request a Quote: {$baseUrl}/contact.php\n\n"
         . "## Contact & Plant Coordinates\n"
         . "- Plant Unit 1: WH-17-A8, ST-1, Sector 38, Korangi Creek Industrial Park, Karachi, Pakistan\n"
         . "- Plant Unit 2: Plot 239, Opp. Masco, Main Korangi Creek Road, Karachi, Pakistan\n"
         . "- Phone: +92 21-38893400-3\n"
         . "- Official Email: info@princeartpackages.com / sales@princeartpackages.com\n";
}

/**
 * Write static robots.txt, sitemap.xml, and llms.txt to the filesystem so search engines can read them directly.
 */
function write_seo_crawler_files(): array {
    $results = [];
    $sitemapXml = generate_sitemap_xml();
    $robotsTxt  = generate_robots_txt();
    $llmsTxt    = generate_llms_txt();

    $targets = [
        __DIR__ . '/../public_site',
        __DIR__ . '/..'
    ];

    foreach ($targets as $dir) {
        if (is_dir($dir) && is_writable($dir)) {
            @file_put_contents($dir . '/sitemap.xml', $sitemapXml);
            @file_put_contents($dir . '/robots.txt', $robotsTxt);
            @file_put_contents($dir . '/llms.txt', $llmsTxt);
            $results[] = realpath($dir);
        }
    }
    return $results;
}


