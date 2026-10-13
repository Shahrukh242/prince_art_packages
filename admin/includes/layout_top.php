<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_login();
$current = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Prince Art Packages</title>
    <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
    <link rel="icon" type="image/png" href="assets/favicon.png">
    <link rel="shortcut icon" href="assets/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="admin-shell">
    <nav class="admin-sidebar">
        <div class="sidebar-brand">
            <i class="ri-box-3-line"></i>
            <span>Prince Art</span>
        </div>
        <div class="sidebar-section-label">Overview</div>
        <a href="index.php"       class="<?= $current === 'index.php'       ? 'active' : '' ?>"><i class="ri-dashboard-line"></i> Dashboard</a>
        <a href="leads.php"       class="<?= $current === 'leads.php'       ? 'active' : '' ?>"><i class="ri-user-star-line"></i> Leads</a>

        <div class="sidebar-section-label">Content</div>
        <a href="content.php"     class="<?= $current === 'content.php'     ? 'active' : '' ?>"><i class="ri-file-text-line"></i> Page Content</a>
        <a href="blog.php"        class="<?= $current === 'blog.php'        ? 'active' : '' ?>"><i class="ri-article-line"></i> Blog Articles</a>
        <a href="cta_buttons.php" class="<?= $current === 'cta_buttons.php' ? 'active' : '' ?>"><i class="ri-cursor-line"></i> CTA Buttons</a>
        <a href="navigation.php"  class="<?= $current === 'navigation.php'  ? 'active' : '' ?>"><i class="ri-navigation-line"></i> Navigation</a>

        <div class="sidebar-section-label">Configuration</div>
        <a href="media.php"       class="<?= $current === 'media.php'       ? 'active' : '' ?>"><i class="ri-image-2-line"></i> Media Library</a>
        <a href="products.php"    class="<?= $current === 'products.php'    ? 'active' : '' ?>"><i class="ri-archive-line"></i> Products</a>
        <a href="settings.php"    class="<?= $current === 'settings.php'    ? 'active' : '' ?>"><i class="ri-settings-3-line"></i> Settings</a>

        <a href="logout.php" class="logout"><i class="ri-logout-box-line"></i> Log Out</a>
    </nav>
    <main class="admin-main">
