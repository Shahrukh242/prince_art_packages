<?php
$pageSlug = 'terms';
require __DIR__ . '/includes/header.php';
?>
<div class="container section">
  <div class="section-header">
    <h2><?= h(get_content('terms', 'page_title', 'Terms & Conditions')) ?></h2>
  </div>
  <div style="max-width: 860px; margin: 0 auto; line-height: 1.8;">
    <?= get_content('terms', 'page_body', '<p>Welcome to the official website of Prince Art Packages (Private) Limited. By accessing and using this website, you agree to comply with and be bound by the terms and conditions outlined herein.</p><p>All content, specifications, logos, and proprietary features (including 3D-Engravix™ and ColdSeal designs) are intellectual property of Prince Art Packages (Private) Limited. Unauthorized copying, reproduction, or redistribution is strictly prohibited.</p>') ?>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
