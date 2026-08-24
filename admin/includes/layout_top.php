<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_login();
$current = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin — Prince Art Packages</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="admin-shell">
    <nav class="admin-sidebar">
        <h2>Prince Art Packages</h2>
        <a href="index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="leads.php" class="<?= $current === 'leads.php' ? 'active' : '' ?>">Leads</a>
        <a href="content.php" class="<?= $current === 'content.php' ? 'active' : '' ?>">Page Content</a>
        <a href="products.php" class="<?= $current === 'products.php' ? 'active' : '' ?>">Products</a>
        <a href="seo.php" class="<?= $current === 'seo.php' ? 'active' : '' ?>">SEO Settings</a>
        <a href="logout.php" class="logout">Log Out</a>
    </nav>
    <main class="admin-main">
