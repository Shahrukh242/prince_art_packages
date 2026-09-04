<?php
require_once __DIR__ . '/../../includes/functions.php';
header('Content-Type: text/html; charset=UTF-8');
$meta       = get_page_meta($pageSlug ?? 'home');
$meta['meta_title'] = $metaTitle ?? $meta['meta_title'];
$meta['meta_description'] = $metaDesc ?? $meta['meta_description'];
$navCurrent = $pageSlug ?? 'home';
$rawNav     = get_nav_links(); // DB-driven nav; falls back to [] if table missing
$navLinks   = [];
$seenNav    = [];
foreach ($rawNav as $l) {
    $norm = strtolower(trim($l['label'] ?? ''));
    if (!isset($seenNav[$norm])) {
        $seenNav[$norm] = true;
        $navLinks[] = $l;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- All public routes, including /product/{slug} and /blog/{slug}, use root-relative assets. -->
  <base href="/">
  <title><?= h($meta['meta_title']) ?></title>
  <meta name="description" content="<?= h($meta['meta_description']) ?>">
  <meta name="robots" content="index, follow">
  <?php
  // Canonical URL
  $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $canonicalBase = rtrim($proto . '://' . ($_SERVER['HTTP_HOST'] ?? 'princeartpackages.com'), '/');
  // Build clean canonical for this page
  $reqUri = $_SERVER['REQUEST_URI'] ?? '/';
  // Remove query strings from canonical for clean pages (keep slug for products/blog)
  $canonPath = strtok($reqUri, '?');
  // If slug-based (product/blog), keep it clean
  if (!empty($_GET['slug'])) {
    $canonPath = $canonPath; // already clean from RewriteRule
  }
  $canonical = $canonicalBase . $canonPath;
  // OG image fallback
  $ogImage = $canonicalBase . '/assets/images/engravix.jpg';
  $ogTitle = $meta['meta_title'] ?? 'Prince Art Packages';
  $ogDesc = $meta['meta_description'] ?? 'ISO 9001:2015 certified pharmaceutical secondary packaging manufacturer in Karachi, Pakistan.';
  ?>
  <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Prince Art Packages (Private) Limited">
  <meta property="og:title" content="<?= htmlspecialchars($ogTitle, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($ogDesc, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:url" content="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($ogTitle, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($ogDesc, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">
  <link rel="preload" href="assets/fonts/remixicon.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="assets/fonts/remixicon.css">
  <link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
  <link rel="icon" type="image/png" href="assets/images/favicon.png">
  <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png">
  <link rel="shortcut icon" href="favicon.ico">
  <link rel="stylesheet" href="assets/css/prince-art.css?v=<?= file_exists(__DIR__ . '/../assets/css/prince-art.css') ? filemtime(__DIR__ . '/../assets/css/prince-art.css') : time() ?>">
  <?php
    $gscTag = get_setting('gsc_verification_tag');
    if (!empty($gscTag)):
      // Accept either Google's token or a pasted meta tag, but never echo
      // administrator-entered HTML directly into the public document.
      if (preg_match('/content\s*=\s*["\']([^"\']+)["\']/i', $gscTag, $matches)) {
        $gscTag = $matches[1];
      }
      echo '<meta name="google-site-verification" content="' . h($gscTag) . "\">\n";
    endif;
    $ga4Id = get_setting('ga4_measurement_id');
    if (!empty($ga4Id)):
  ?>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= h($ga4Id) ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', <?= json_encode($ga4Id, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
  </script>
  <?php endif; ?>
</head>
<body>

  <header class="main-header">
    <div class="container header-inner">
      <a href="index" class="brand-logo"><img src="assets/images/logo.png" alt="Prince Art Packages (Private) Limited" class="brand-logo-img"></a>
      <nav>
        <ul class="nav-links">
          <?php if (!empty($navLinks)): ?>
            <?php foreach ($navLinks as $link): ?>
              <?php
                // Clean extensionless URL
                $cleanUrl = preg_replace('/\.php$/i', '', $link['url']);
                $cleanUrl = ($cleanUrl === 'index') ? 'index' : $cleanUrl;
                // Derive a slug from the URL for active-state matching
                $linkSlug = basename($cleanUrl);
                $linkSlug = ($linkSlug === 'index' || $linkSlug === '') ? 'home' : $linkSlug;
                $isActive = ($navCurrent === $linkSlug);
              ?>
              <li>
                <a href="<?= h($cleanUrl) ?>"
                   class="<?= $isActive ? 'active' : '' ?>"
                   <?= $link['open_new_tab'] ? 'target="_blank" rel="noopener"' : '' ?>>
                  <?= h($link['label']) ?>
                </a>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <!-- Fallback hardcoded clean nav -->
            <li><a href="index"        class="<?= $navCurrent === 'home'         ? 'active' : '' ?>">Home</a></li>
            <li><a href="about"        class="<?= $navCurrent === 'about'        ? 'active' : '' ?>">About</a></li>
            <li><a href="products"     class="<?= $navCurrent === 'products'     ? 'active' : '' ?>">Products</a></li>
            <li><a href="capabilities" class="<?= $navCurrent === 'capabilities' ? 'active' : '' ?>">Capabilities</a></li>
            <li><a href="innovation"   class="<?= $navCurrent === 'innovation'   ? 'active' : '' ?>">Innovation</a></li>
            <li><a href="quality"      class="<?= $navCurrent === 'quality'      ? 'active' : '' ?>">Quality</a></li>
            <li><a href="industries"   class="<?= $navCurrent === 'industries'   ? 'active' : '' ?>">Industries</a></li>
            <li><a href="blog"         class="<?= $navCurrent === 'blog'         ? 'active' : '' ?>">Blog</a></li>
            <li><a href="contact"      class="<?= $navCurrent === 'contact'      ? 'active' : '' ?>">Contact</a></li>
          <?php endif; ?>
        </ul>
      </nav>
      <div class="header-actions">
        <?= render_cta_buttons('global', 'header', '<a href="contact" class="btn btn-gold btn-sm header-cta-btn">Request a Quote</a>') ?>
        <button id="btn-mobile-nav" class="mobile-toggle-btn" aria-label="Toggle Menu"><i class="ri-menu-3-line"></i></button>
      </div>
    </div>
  </header>
  <main class="page-section active">
