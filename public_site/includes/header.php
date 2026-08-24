<?php
require_once __DIR__ . '/../../includes/functions.php';
$meta = get_page_meta($pageSlug ?? 'home');
$navCurrent = $pageSlug ?? 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= h($meta['meta_title']) ?></title>
  <meta name="description" content="<?= h($meta['meta_description']) ?>">
  <meta name="robots" content="index, follow">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/prince-art.css">
</head>
<body>

  <header class="main-header">
    <div class="container header-inner">
      <a href="index.php" class="brand-logo"><img src="assets/images/logo.png" alt="Prince Art Packages (Private) Limited" class="brand-logo-img"></a>
      <nav>
        <ul class="nav-links">
          <li><a href="index.php" class="<?= $navCurrent === 'home' ? 'active' : '' ?>">Home</a></li>
          <li><a href="about.php" class="<?= $navCurrent === 'about' ? 'active' : '' ?>">About</a></li>
          <li><a href="products.php" class="<?= $navCurrent === 'products' ? 'active' : '' ?>">Products</a></li>
          <li><a href="capabilities.php" class="<?= $navCurrent === 'capabilities' ? 'active' : '' ?>">Capabilities</a></li>
          <li><a href="innovation.php" class="<?= $navCurrent === 'innovation' ? 'active' : '' ?>">Innovation</a></li>
          <li><a href="quality.php" class="<?= $navCurrent === 'quality' ? 'active' : '' ?>">Quality</a></li>
          <li><a href="industries.php" class="<?= $navCurrent === 'industries' ? 'active' : '' ?>">Industries</a></li>
          <li><a href="sustainability.php" class="<?= $navCurrent === 'sustainability' ? 'active' : '' ?>">Sustainability</a></li>
          <li><a href="blog.php" class="<?= $navCurrent === 'blog' ? 'active' : '' ?>">Blog</a></li>
          <li><a href="contact.php" class="<?= $navCurrent === 'contact' ? 'active' : '' ?>">Contact</a></li>
        </ul>
      </nav>
      <div class="header-actions">
        <a href="contact.php" class="btn btn-gold btn-sm header-cta-btn">Request a Quote</a>
        <button id="btn-mobile-nav" class="mobile-toggle-btn" aria-label="Toggle Menu"><i class="ri-menu-3-line"></i></button>
      </div>
    </div>
  </header>
  <main class="page-section active">
