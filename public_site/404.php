<?php
http_response_code(404);
$pageSlug = '404';
require_once __DIR__ . '/../includes/functions.php';

// Custom meta for 404 page
$meta = [
    'title' => 'Page Not Found | Prince Art Packages',
    'meta_title' => '404 — Page Not Found | Prince Art Packages (Private) Limited',
    'meta_description' => 'The page you requested could not be found. Explore our pharmaceutical secondary packaging solutions, capabilities, or contact our sales team.',
];

require __DIR__ . '/includes/header.php';
?>

  <!-- Branded 404 Error Section -->
  <section class="section error-404-section" style="padding: 6rem 0; min-height: 65vh; display: flex; align-items: center; background: linear-gradient(180deg, var(--bg-white) 0%, var(--bg-surface) 100%);">
    <div class="container" style="max-width: 760px; text-align: center;">
      <div style="margin-bottom: 1.5rem;">
        <span style="font-size: 5.5rem; font-weight: 900; line-height: 1; color: var(--teal-brand); letter-spacing: -2px; display: inline-block;">
          404
        </span>
      </div>

      <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: var(--teal-bg); color: var(--teal-brand); font-weight: 700; font-size: 0.85rem; padding: 0.35rem 1rem; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 1.25rem;">
        <i class="ri-error-warning-line"></i> Page Not Found
      </div>

      <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--navy-dark); margin-bottom: 1rem; line-height: 1.25;">
        We Couldn't Find That Page
      </h1>

      <p style="font-size: 1.1rem; color: var(--text-body); line-height: 1.6; margin-bottom: 2.5rem; max-width: 580px; margin-left: auto; margin-right: auto;">
        The link you followed may be broken, or the page may have been moved or updated. Use the links below to explore our pharmaceutical secondary packaging solutions.
      </p>

      <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-bottom: 3rem;">
        <a href="index" class="btn btn-gold btn-lg">
          <i class="ri-home-4-line"></i> Return to Homepage
        </a>
        <a href="products" class="btn btn-navy btn-lg">
          <i class="ri-box-3-line"></i> Browse Products
        </a>
        <a href="contact" class="btn btn-outline-teal btn-lg">
          <i class="ri-mail-send-line"></i> Contact Our Team
        </a>
      </div>

      <!-- Quick Helpful Navigation -->
      <div style="background: #ffffff; border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; box-shadow: var(--shadow-sm); text-align: left;">
        <div style="font-weight: 700; font-size: 0.95rem; color: var(--navy-dark); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em;">
          <i class="ri-compass-3-line text-teal" style="margin-right: 0.4rem;"></i> Popular Site Destinations:
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 0.75rem;">
          <a href="products#product-folding-cartons" style="color: var(--teal-brand); text-decoration: none; font-size: 0.95rem; font-weight: 600;">
            &rarr; Printed Cartons
          </a>
          <a href="products#product-leaf-inserts" style="color: var(--teal-brand); text-decoration: none; font-size: 0.95rem; font-weight: 600;">
            &rarr; Leaf-Inserts (PIL)
          </a>
          <a href="capabilities" style="color: var(--teal-brand); text-decoration: none; font-size: 0.95rem; font-weight: 600;">
            &rarr; Production Facilities
          </a>
          <a href="quality" style="color: var(--teal-brand); text-decoration: none; font-size: 0.95rem; font-weight: 600;">
            &rarr; Quality &amp; Certifications
          </a>
        </div>
      </div>
    </div>
  </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
