<?php
$pageSlug = 'blog';
require __DIR__ . '/includes/header.php';
?>
<div class="container section">
      <div class="section-header">
        <span class="section-subtitle">PACKAGING INSIGHTS & TECHNICAL ARTICLES</span>
        <h2>Pharmaceutical Packaging Insights</h2>
        <p>Technical publications, regulatory compliance guidelines, and anti-counterfeiting innovations from Prince Art Packages (Private) Limited.</p>
      </div>

      <!-- Category Filter Pills -->
      <div style="display:flex; justify-content:center; gap:0.75rem; flex-wrap:wrap; margin-bottom:2.5rem;" id="blog-category-filters">
        <button class="btn btn-sm btn-gold blog-filter-btn active" data-category="all">All Articles</button>
        <button class="btn btn-sm btn-outline-navy blog-filter-btn" data-category="Quality & Compliance">Quality & Compliance</button>
        <button class="btn btn-sm btn-outline-navy blog-filter-btn" data-category="Anti-Counterfeiting">Anti-Counterfeiting</button>
        <button class="btn btn-sm btn-outline-navy blog-filter-btn" data-category="Sustainability">Sustainability</button>
        <button class="btn btn-sm btn-outline-navy blog-filter-btn" data-category="Technology">Technology</button>
      </div>

      <!-- Blog Grid Container -->
      <div class="grid-3-products" id="blogs-grid-container">
        <!-- Dynamically rendered via JS from /api/blog-posts -->
      </div>
    </div>

<?php require __DIR__ . '/includes/footer.php'; ?>
