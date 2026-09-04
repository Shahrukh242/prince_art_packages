<?php
$pageSlug = 'privacy';
require __DIR__ . '/includes/header.php';
?>
<div class="container section">
  <div class="section-header">
    <h2><?= h(get_content('privacy', 'page_title', 'Privacy Policy')) ?></h2>
  </div>
  <div style="max-width: 860px; margin: 0 auto; line-height: 1.8;">
    <?= get_content('privacy', 'page_body', '<p>Prince Art Packages (Private) Limited respects your privacy and is committed to protecting your personal information. This Privacy Policy outlines how we handle information collected through our website, quote requests, and client communications.</p><p>We collect basic business contact details (name, email, company, phone) submitted voluntarily through our quotation and inquiry forms. This information is strictly used for commercial communication, technical specification evaluations, and client order execution. We do not sell or lease your information to third parties.</p>') ?>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
